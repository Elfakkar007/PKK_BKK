<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope untuk jurusan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }

    // Relationship dengan students
    public function students()
    {
        return $this->hasMany(Student::class, 'major', 'code');
    }

    // Helper method
    public function getFullNameAttribute()
    {
        return $this->code . ' - ' . $this->name;
    }
}