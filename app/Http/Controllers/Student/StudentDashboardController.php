<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\JobVacancy;
use App\Models\Student;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{

    public function index()
    {
        $student = Auth::user()->student;
        
        $applications = Application::where('student_id', $student->id)
            ->with('jobVacancy.company')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => $applications->total(),
            'pending' => Application::where('student_id', $student->id)->pending()->count(),
            'reviewed' => Application::where('student_id', $student->id)->reviewed()->count(),
            'accepted' => Application::where('student_id', $student->id)->accepted()->count(),
            'rejected' => Application::where('student_id', $student->id)->rejected()->count(),
        ];

        return view('student.dashboard', compact('applications', 'stats', 'student'));
    }

    public function profile()
    {
        $student = Auth::user()->student;
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::user()->student;

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'cv_path' => ['nullable', 'mimes:pdf', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('student-photos', 'public');
        }

        if ($request->hasFile('cv_path')) {
            if ($student->cv_path) {
                Storage::disk('public')->delete($student->cv_path);
            }
            $validated['cv_path'] = $request->file('cv_path')->store('student-cvs', 'public');
        }

        $student->update($validated);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function applyForm($vacancyId)
    {
        $vacancy = JobVacancy::active()->findOrFail($vacancyId);
        $student = Auth::user()->student;

        // Check if already applied
        $existingApplication = Application::where('student_id', $student->id)
            ->where('job_vacancy_id', $vacancyId)
            ->first();

        if ($existingApplication) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Anda sudah melamar lowongan ini.');
        }

        // Check if vacancy is full
        if ($vacancy->isFull()) {
            return redirect()->back()
                ->with('error', 'Maaf, kuota lowongan sudah penuh.');
        }

        return view('student.apply', compact('vacancy', 'student'));
    }

    public function submitApplication(Request $request, $vacancyId)
    {
        $vacancy = JobVacancy::active()->findOrFail($vacancyId);
        $student = Auth::user()->student;

        // Check if already applied
        $existingApplication = Application::where('student_id', $student->id)
            ->where('job_vacancy_id', $vacancyId)
            ->first();

        if ($existingApplication) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Anda sudah melamar lowongan ini.');
        }

        // Tentukan apakah menggunakan CV lama atau baru
        $useExistingCv = $request->has('use_existing_cv');

        // Validation rules yang conditional
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'birth_place' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
        ];

        // CV hanya required jika tidak menggunakan CV yang sudah tersimpan
        if (!$useExistingCv) {
            $rules['cv'] = ['required', 'mimes:pdf', 'max:5120'];
        }

        $validated = $request->validate($rules);

        // Tentukan CV path
        if ($useExistingCv) {
            // Gunakan CV yang sudah tersimpan
            if (!$student->cv_path) {
                return back()->withErrors(['cv' => 'CV tidak ditemukan. Silakan upload CV baru.']);
            }
            $cvPath = $student->cv_path;
        } else {
            // Upload CV baru
            $cvPath = $request->file('cv')->store('application-cvs', 'public');
        }

        $application = Application::create([
            'job_vacancy_id' => $vacancyId,
            'student_id' => $student->id,
            'full_name' => $validated['full_name'],
            'address' => $validated['address'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        // Send notification to company
        $vacancy->company->user->notify(new ApplicationSubmittedNotification($application));

        return redirect()->route('student.dashboard')
            ->with('success', 'Lamaran berhasil dikirim!');
    }

    public function applicationShow($id)
    {
        $student = Auth::user()->student;
        
        $application = Application::where('student_id', $student->id)
            ->with('jobVacancy.company')
            ->findOrFail($id);

        return view('student.application-detail', compact('application'));
    }
}