<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Highlight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HighlightManagementController extends Controller
{
    

    public function index()
    {
        $highlights = Highlight::orderBy('order')->paginate(20);
        return view('admin.highlights.index', compact('highlights'));
    }

    public function create()
    {
        return view('admin.highlights.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['required', 'image', 'max:2048'],
            'link' => ['nullable', 'url'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('highlight-images', 'public');
        }

        Highlight::create($validated);

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight berhasil ditambahkan.');
    }

    public function edit(Highlight $highlight)
    {
        return view('admin.highlights.edit', compact('highlight'));
    }

    public function update(Request $request, Highlight $highlight)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'link' => ['nullable', 'url'],
            'order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ]);

        if ($request->hasFile('image')) {
            if ($highlight->image) {
                Storage::disk('public')->delete($highlight->image);
            }
            $validated['image'] = $request->file('image')
                ->store('highlight-images', 'public');
        }

        $highlight->update($validated);

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight berhasil diperbarui.');
    }

    public function destroy(Highlight $highlight)
    {
        if ($highlight->image) {
            Storage::disk('public')->delete($highlight->image);
        }

        $highlight->delete();

        return redirect()->route('admin.highlights.index')
            ->with('success', 'Highlight berhasil dihapus.');
    }
}
