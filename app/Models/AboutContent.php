<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    use HasFactory;

    protected $table = 'about_content';

    protected $fillable = [
        'vision',
        'mission',
        'work_programs',
        'organization_chart',
        'contact_email'
    ];

    protected $casts = [
        'work_programs' => 'array',
    ];

    // Helper methods
    public function hasVision(): bool
    {
        return !empty($this->vision);
    }

    public function hasMission(): bool
    {
        return !empty($this->mission);
    }

    public function hasWorkPrograms(): bool
    {
        return !empty($this->work_programs);
    }

    public function hasOrganizationChart(): bool
    {
        return !empty($this->organization_chart);
    }
}