<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class JobPosting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'department',
        'position_type',
        'salary',
        'application_deadline',
        'is_published'
    ];

    protected $casts = [
        'application_deadline' => 'date', // This ensures Carbon instance
        'salary' => 'decimal:2',
        'is_published' => 'boolean'
    ];

    // Relationships
    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    // Scopes
    public function scopePublished(Builder $query)
    {
        return $query->where('is_published', true);
    }

    public function scopeActive(Builder $query)
    {
        return $query->published()
            ->where('application_deadline', '>=', now());
    }

    public function scopeExpired(Builder $query)
    {
        return $query->published()
            ->where('application_deadline', '<', now());
    }

    // Helpers
    public function isActive()
    {
        return $this->is_published && !$this->isExpired();
    }

    public function isExpired()
    {
        return $this->application_deadline->isPast();
    }

    public function daysRemaining()
    {
        return now()->diffInDays($this->application_deadline, false);
    }

    public function formattedSalary()
    {
        return '$' . number_format($this->salary, 2);
    }
}
