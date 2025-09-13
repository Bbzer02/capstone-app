<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department',
        'position_type',
        'salary',
        'application_deadline',
        'is_published',
        'item_number',
        'campus',
        'vacancies',
        'education_requirements',
        'experience_requirements',
        'training_requirements',
        'eligibility_requirements',
        'email_subject_format'
    ];

    protected $casts = [
        'application_deadline' => 'datetime',
        'is_published' => 'boolean',
        'salary' => 'decimal:2'
    ];

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function formattedSalary()
    {
        if (!$this->salary) {
            return 'Salary not specified';
        }
        
        return '₱' . number_format($this->salary, 2);
    }

    public function getFormattedDeadlineAttribute()
    {
        if (!$this->application_deadline) {
            return 'No deadline specified';
        }
        
        // Ensure it's a Carbon instance
        if (is_string($this->application_deadline)) {
            $this->application_deadline = \Carbon\Carbon::parse($this->application_deadline);
        }
        
        return $this->application_deadline->format('M d, Y');
    }

    public function isExpired()
    {
        if (!$this->application_deadline) {
            return false;
        }
        
        // Ensure it's a Carbon instance
        if (is_string($this->application_deadline)) {
            $this->application_deadline = \Carbon\Carbon::parse($this->application_deadline);
        }
        
        return $this->application_deadline->isPast();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true)
                    ->where(function($q) {
                        $q->whereNull('application_deadline')
                          ->orWhere('application_deadline', '>=', now()->toDateTimeString());
                    });
    }
}