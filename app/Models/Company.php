<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'company_type',
        'industry_sector',
        'description',
        'head_office_address',
        'branch_addresses',
        'pic_name',
        'pic_phone',
        'pic_email',
        'website',
        'logo',
        'legality_doc',
    ];

    protected $casts = [
        'branch_addresses' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobVacancies()
    {
        return $this->hasMany(JobVacancy::class);
    }

    public function activeVacancies()
    {
        return $this->hasMany(JobVacancy::class)
            ->where('is_active', true)
            ->where('status', 'approved')
            ->where('deadline', '>=', now());
    }

    // Accessor
    public function getFullNameAttribute()
    {
        return $this->company_type . ' ' . $this->name;
    }
}