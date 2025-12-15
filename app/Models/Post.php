<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'category',
        'is_published',
        'published_at',
        'views',
    ];

     protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     protected static function boot()
    {
        parent::boot();

        // Auto-generate slug saat creating
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        // Update slug saat title berubah (opsional)
        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Cek apakah slug sudah ada, jika ya tambahkan angka
        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

     public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Accessors
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeNews($query)
    {
        return $query->where('category', 'news');
    }

    public function scopeDocumentation($query)
    {
        return $query->where('category', 'documentation');
    }

    public function scopeGuide($query)
    {
        return $query->where('category', 'guide');
    }

    public function scopeHighlight($query)
    {
        return $query->where('category', 'highlight');
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getCategoryLabelAttribute()
    {
        return match($this->category) {
            'news' => 'Berita',
            'documentation' => 'Dokumentasi',
            'guide' => 'Panduan',
            'highlight' => 'Sorotan',
            default => $this->category
        };
    }

    public function isDocumentation()
    {
        return $this->category === 'documentation';
    }

    public function isGuide()
    {
        return $this->category === 'guide';
    }

    public function isHighlight()
    {
        return $this->category === 'highlight';
    }

    public function isNews()
    {
        return $this->category === 'news';
    }

    // Extract images from content for documentation
    public function getContentImages()
    {
        if (!$this->isDocumentation()) {
            return [];
        }

        preg_match_all('/<img[^>]+src="([^">]+)"/i', $this->content, $matches);
        return $matches[1] ?? [];
    }

    // Check if content has downloadable files
    public function hasDownloadableFiles()
    {
        return preg_match('/\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i', $this->content);
    }

    // Extract PDF links from content
    public function getPdfLinks()
    {
        preg_match_all('/<a[^>]+href="([^">]+\.pdf)"[^>]*>/i', $this->content, $matches);
        return $matches[1] ?? [];
    }
}