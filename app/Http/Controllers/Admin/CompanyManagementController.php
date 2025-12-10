<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyManagementController extends Controller
{
   
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sector = $request->get('sector');

        $companies = Company::with('user')
            ->when($search, function($q, $search) {
                $q->where('name', 'ILIKE', "%{$search}%");
            })
            ->when($sector, function($q, $sector) {
                $q->where('industry_sector', $sector);
            })
            ->latest()
            ->paginate(20);

        $sectors = Company::distinct()->pluck('industry_sector');

        return view('admin.companies.index', compact('companies', 'sectors'));
    }

    public function show($id)
    {
        $company = Company::with(['user', 'jobVacancies'])
            ->findOrFail($id);

        return view('admin.companies.show', compact('company'));
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_type' => ['required', 'string'],
            'industry_sector' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        $company->update($validated);

        return redirect()->route('admin.companies.index')
            ->with('success', 'Data perusahaan berhasil diperbarui.');
    }
}