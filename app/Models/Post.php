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
    ];

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
}