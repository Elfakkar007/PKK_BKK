<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostManagementController extends Controller
{
   
    public function index(Request $request)
    {
        $category = $request->get('category');
        $is_published = $request->get('is_published');

        $posts = Post::with('author')
            ->when($category, function($q, $category) {
                $q->where('category', $category);
            })
            ->when($is_published !== null, function($q) use ($is_published) {
                $q->where('is_published', $is_published);
            })
            ->latest()
            ->paginate(20);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:news,documentation,guide,highlight'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
        ]);

        $validated['author_id'] = auth()->id();
        
        // Auto-generate slug (akan di-handle oleh Model, tapi bisa juga di sini)
        // $validated['slug'] sudah di-handle di Model Post::boot()
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('post-images', 'public');
        }

        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        try {
            Post::create($validated);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Postingan berhasil dibuat.');
        } catch (\Exception $e) {
            // Jika ada error (misalnya duplicate slug), hapus gambar yang sudah diupload
            if (isset($validated['featured_image'])) {
                Storage::disk('public')->delete($validated['featured_image']);
            }

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat postingan: ' . $e->getMessage());
        }
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:news,documentation,guide,highlight'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
            'is_published' => ['boolean'],
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('post-images', 'public');
        }

        if ($request->boolean('is_published') && !$post->is_published) {
            $validated['published_at'] = now();
        }

        try {
            $post->update($validated);

            return redirect()->route('admin.posts.index')
                ->with('success', 'Postingan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui postingan: ' . $e->getMessage());
        }
    }

    public function destroy(Post $post)
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil dihapus.');
    }

    /**
     * Handle TinyMCE image upload
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('file')) {
                // Store image
                $path = $request->file('file')->store('post-content-images', 'public');
                
                // Return URL for TinyMCE
                return response()->json([
                    'location' => Storage::url($path)
                ]);
            }

            return response()->json([
                'error' => 'No file uploaded'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}