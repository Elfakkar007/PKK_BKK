<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('post-images', 'public');
        }

        if ($request->boolean('is_published')) {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil dibuat.');
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

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Postingan berhasil diperbarui.');
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
}