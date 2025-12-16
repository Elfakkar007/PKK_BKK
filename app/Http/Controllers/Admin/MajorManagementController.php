<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorManagementController extends Controller
{
    public function index()
    {
        $majors = Major::orderBy('order')->orderBy('code')->paginate(20);
        return view('admin.majors.index', compact('majors'));
    }

    public function create()
    {
        return view('admin.majors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:majors,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        Major::create($validated);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $major = Major::findOrFail($id);
        return view('admin.majors.edit', compact('major'));
    }

    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'unique:majors,code,' . $id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $major->update($validated);

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $major = Major::findOrFail($id);
        
        // Check if major is used by students
        if ($major->students()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus jurusan yang masih digunakan oleh siswa.');
        }

        $major->delete();

        return redirect()->route('admin.majors.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $major = Major::findOrFail($id);
        $major->update(['is_active' => !$major->is_active]);

        $status = $major->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "Jurusan berhasil {$status}.");
    }
}