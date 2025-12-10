<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'type',
        'description',
        'requirements',
        'location',
        'salary_min',
        'salary_max',
        'quota',
        'deadline',
        'status',
        'rejection_reason',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'approved')
            ->where('deadline', '>=', now());
    }

    public function scopeInternship($query)
    {
        return $query->where('type', 'internship');
    }

    public function scopeFulltime($query)
    {
        return $query->where('type', 'fulltime');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Helper methods
    public function isExpired()
    {
        return $this->deadline < now();
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function remainingQuota()
    {
        $acceptedApplications = $this->applications()->where('status', 'accepted')->count();
        return max(0, $this->quota - $acceptedApplications);
    }

    public function isFull()
    {
        return $this->remainingQuota() <= 0;
    }
}