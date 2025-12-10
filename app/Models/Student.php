<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'nis',
        'gender',
        'status',
        'graduation_year',
        'class',
        'major',
        'birth_date',
        'birth_place',
        'address',
        'phone',
        'photo',
        'cv_path',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'graduation_year' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Accessor
    public function getFullBirthPlaceAttribute()
    {
        return $this->birth_place . ', ' . $this->birth_date->format('d-m-Y');
    }

    // Helper methods
    public function isAlumni()
    {
        return $this->status === 'alumni';
    }

    public function isCurrentStudent()
    {
        return $this->status === 'student';
    }
}