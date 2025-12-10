<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Company;
use App\Notifications\RegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if (!$user->isApproved()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui oleh admin.',
                ]);
            }

            // Redirect based on role
            return match($user->role) {
                'admin' => redirect()->intended('/admin/dashboard'),
                'student' => redirect()->intended('/student/dashboard'),
                'company' => redirect()->intended('/company/dashboard'),
                default => redirect()->intended('/')
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRegisterStudent()
    {
        return view('auth.register-student');
    }

    public function registerStudent(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'full_name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'unique:students,nis'],
            'gender' => ['required', 'in:male,female'],
            'status' => ['required', 'in:student,alumni'],
            'graduation_year' => ['required_if:status,alumni', 'nullable', 'integer', 'min:2000', 'max:2099'],
            'class' => ['required_if:status,student', 'nullable', 'string'],
            'major' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['required', 'string'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'status' => 'pending',
        ]);

        Student::create([
            'user_id' => $user->id,
            'full_name' => $validated['full_name'],
            'nis' => $validated['nis'],
            'gender' => $validated['gender'],
            'status' => $validated['status'],
            'graduation_year' => $validated['graduation_year'] ?? null,
            'class' => $validated['class'] ?? null,
            'major' => $validated['major'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
        ]);

        // Send notification to user
        $user->notify(new RegistrationNotification());

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan tunggu approval dari admin.');
    }

    public function showRegisterCompany()
    {
        return view('auth.register-company');
    }

    public function registerCompany(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'name' => ['required', 'string', 'max:255'],
            'company_type' => ['required', 'string'],
            'industry_sector' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'head_office_address' => ['required', 'string'],
            'branch_addresses' => ['nullable', 'array'],
            'pic_name' => ['required', 'string'],
            'pic_phone' => ['required', 'string'],
            'pic_email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'legality_doc' => ['nullable', 'mimes:pdf', 'max:5120'],
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'company',
            'status' => 'pending',
        ]);

        $companyData = [
            'user_id' => $user->id,
            'name' => $validated['name'],
            'company_type' => $validated['company_type'],
            'industry_sector' => $validated['industry_sector'],
            'description' => $validated['description'] ?? null,
            'head_office_address' => $validated['head_office_address'],
            'branch_addresses' => $validated['branch_addresses'] ?? null,
            'pic_name' => $validated['pic_name'],
            'pic_phone' => $validated['pic_phone'],
            'pic_email' => $validated['pic_email'] ?? null,
            'website' => $validated['website'] ?? null,
        ];

        if ($request->hasFile('logo')) {
            $companyData['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        if ($request->hasFile('legality_doc')) {
            $companyData['legality_doc'] = $request->file('legality_doc')->store('company-docs', 'public');
        }

        Company::create($companyData);

        // Send notification
        $user->notify(new RegistrationNotification());

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan tunggu approval dari admin.');
    }
}