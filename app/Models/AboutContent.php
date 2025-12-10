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
    ];

    protected $casts = [
        'work_programs' => 'array',
    ];

    // Helper methods
    public function hasVision()
    {
        return !empty($this->vision);
    }

    public function hasMission()
    {
        return !empty($this->mission);
    }

    public function hasWorkPrograms()
    {
        return !empty($this->work_programs);
    }

    public function hasOrganizationChart()
    {
        return !empty($this->organization_chart);
    }
}