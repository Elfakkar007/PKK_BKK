<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutManagementController extends Controller
{
   

    public function edit()
    {
        $about = AboutContent::first() ?? new AboutContent();
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'work_programs' => ['nullable', 'array'],
            'organization_chart' => ['nullable', 'image', 'max:5120'],
        ]);

        $about = AboutContent::first() ?? new AboutContent();

        if ($request->hasFile('organization_chart')) {
            if ($about->organization_chart) {
                Storage::disk('public')->delete($about->organization_chart);
            }
            $validated['organization_chart'] = $request->file('organization_chart')
                ->store('organization-charts', 'public');
        }

        if ($about->exists) {
            $about->update($validated);
        } else {
            AboutContent::create($validated);
        }

        return redirect()->route('admin.about.edit')
            ->with('success', 'Konten tentang BKK berhasil diperbarui.');
    }
}