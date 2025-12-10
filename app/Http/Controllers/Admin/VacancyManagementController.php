<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Notifications\VacancyApprovedNotification;
use App\Notifications\VacancyRejectedNotification;
use Illuminate\Http\Request;

class VacancyManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $type = $request->get('type', 'all');
        $search = $request->get('search');

        $vacancies = JobVacancy::with('company')
            ->when($status !== 'all', function($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($type !== 'all', function($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($search, function($q, $search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhereHas('company', function($query) use ($search) {
                      $query->where('name', 'ILIKE', "%{$search}%");
                  });
            })
            ->latest()
            ->paginate(20);

        return view('admin.vacancies.index', compact('vacancies', 'status', 'type'));
    }

    public function pending()
    {
        $pendingVacancies = JobVacancy::with('company')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.vacancies.pending', compact('pendingVacancies'));
    }

    public function show($id)
    {
        $vacancy = JobVacancy::with(['company.user', 'applications'])
            ->findOrFail($id);

        return view('admin.vacancies.show', compact('vacancy'));
    }

    public function approve($id)
    {
        $vacancy = JobVacancy::findOrFail($id);
        
        $vacancy->update([
            'status' => 'approved',
            'rejection_reason' => null,
        ]);

        // Send notification to company
        $vacancy->company->user->notify(new VacancyApprovedNotification($vacancy));

        return back()->with('success', 'Lowongan berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $vacancy = JobVacancy::findOrFail($id);
        
        $vacancy->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Send notification to company
        $vacancy->company->user->notify(new VacancyRejectedNotification($vacancy));

        return back()->with('success', 'Lowongan berhasil ditolak.');
    }

    public function destroy($id)
    {
        $vacancy = JobVacancy::findOrFail($id);
        $vacancy->delete();

        return back()->with('success', 'Lowongan berhasil dihapus.');
    }
}