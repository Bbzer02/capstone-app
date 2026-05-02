<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'job_id',
        'sender_type',
        'admin_id',
        'message',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'admin_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForApplicant($query, $applicantId)
    {
        return $query->where('applicant_id', $applicantId);
    }

    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_id', $jobId);
    }

    // Accessors
    public function getFormattedTimeAttribute()
    {
        $timestamp = $this->created_at ? $this->created_at->copy()->timezone($this->displayTimezone()) : null;
        return $timestamp ? $timestamp->format('M j, Y g:i A') : '';
    }

    public function getTimeAgoAttribute()
    {
        if (!$this->created_at) {
            return '';
        }

        $timezone = $this->displayTimezone();
        $now = now($timezone);
        $messageTime = $this->created_at->copy()->timezone($timezone);
        
        if ($messageTime->isSameDay($now)) {
            return $messageTime->format('g:i A');
        } elseif ($messageTime->isYesterday()) {
            return 'Yesterday at ' . $messageTime->format('g:i A');
        } elseif ($messageTime->isCurrentWeek()) {
            return $messageTime->format('l g:i A');
        }

        return $messageTime->format('M j, g:i A');
    }

    protected function displayTimezone(): string
    {
        return config('app.timezone', 'Asia/Manila');
    }

    // Methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function isFromApplicant()
    {
        return $this->sender_type === 'applicant';
    }

    public function isFromAdmin()
    {
        return $this->sender_type === 'admin';
    }
}