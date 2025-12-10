<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\Application;
use App\Notifications\ApplicationStatusNotification;
use App\Notifications\VacancyApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:company', 'approved']);
    }

    public function index()
    {
        $company = Auth::user()->company;
        
        $stats = [
            'total_vacancies' => JobVacancy::where('company_id', $company->id)->count(),
            'active_vacancies' => JobVacancy::where('company_id', $company->id)->active()->count(),
            'pending_vacancies' => JobVacancy::where('company_id', $company->id)->pending()->count(),
            'total_applications' => Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->count(),
            'pending_applications' => Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })->pending()->count(),
        ];

        $recentApplications = Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->with(['jobVacancy', 'student.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('company.dashboard', compact('company', 'stats', 'recentApplications'));
    }

    public function profile()
    {
        $company = Auth::user()->company;
        return view('company.profile', compact('company'));
    }

    public function updateProfile(Request $request)
    {
        $company = Auth::user()->company;

        $validated = $request->validate([
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
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $company->update($validated);

        return back()->with('success', 'Profile perusahaan berhasil diperbarui.');
    }

    // Vacancy Management
    public function vacancies()
    {
        $company = Auth::user()->company;
        $vacancies = JobVacancy::where('company_id', $company->id)
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('company.vacancies.index', compact('vacancies'));
    }

    public function vacancyCreate()
    {
        return view('company.vacancies.create');
    }

    public function vacancyStore(Request $request)
    {
        $company = Auth::user()->company;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:internship,fulltime'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'quota' => ['required', 'integer', 'min:1'],
            'deadline' => ['required', 'date', 'after:today'],
        ]);

        $validated['company_id'] = $company->id;
        $validated['status'] = 'pending';
        $validated['is_active'] = true;

        JobVacancy::create($validated);

        return redirect()->route('company.vacancies')
            ->with('success', 'Lowongan berhasil dibuat dan menunggu persetujuan admin.');
    }

    public function vacancyEdit($id)
    {
        $company = Auth::user()->company;
        $vacancy = JobVacancy::where('company_id', $company->id)->findOrFail($id);

        return view('company.vacancies.edit', compact('vacancy'));
    }

    public function vacancyUpdate(Request $request, $id)
    {
        $company = Auth::user()->company;
        $vacancy = JobVacancy::where('company_id', $company->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:internship,fulltime'],
            'description' => ['required', 'string'],
            'requirements' => ['required', 'string'],
            'location' => ['required', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'quota' => ['required', 'integer', 'min:1'],
            'deadline' => ['required', 'date', 'after:today'],
            'is_active' => ['boolean'],
        ]);

        $vacancy->update($validated);

        return redirect()->route('company.vacancies')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function vacancyDestroy($id)
    {
        $company = Auth::user()->company;
        $vacancy = JobVacancy::where('company_id', $company->id)->findOrFail($id);
        
        $vacancy->delete();

        return redirect()->route('company.vacancies')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    // Application Management
    public function applications(Request $request)
    {
        $company = Auth::user()->company;
        $status = $request->get('status', 'all');

        $applications = Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->with(['jobVacancy', 'student.user'])
            ->when($status !== 'all', function($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('company.applications.index', compact('applications', 'status'));
    }

    public function applicationShow($id)
    {
        $company = Auth::user()->company;
        
        $application = Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->with(['jobVacancy', 'student.user'])
            ->findOrFail($id);

        return view('company.applications.show', compact('application'));
    }

    public function applicationUpdateStatus(Request $request, $id)
    {
        $company = Auth::user()->company;
        
        $application = Application::whereHas('jobVacancy', function($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,reviewed,accepted,rejected'],
            'company_notes' => ['nullable', 'string'],
        ]);

        $application->update([
            'status' => $validated['status'],
            'company_notes' => $validated['company_notes'],
            'reviewed_at' => now(),
        ]);

        // Send notification to student
        $application->student->user->notify(new ApplicationStatusNotification($application));

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}