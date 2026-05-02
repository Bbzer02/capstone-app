<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'cover_letter_path',
        'transcript_path',
        'certificate_path',
        'portfolio_path',
        'additional_documents',
        'status',
        'rejection_reason',
        'is_editable_by_user'
    ];

    protected $casts = [
        'additional_documents' => 'array',
        'is_editable_by_user' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class)->withDefault([
            'title' => 'No Job Assigned',
            'application_deadline' => null
        ]);
    }

    public function interview()
    {
        return $this->hasOne(Interview::class)->withDefault([
            'scheduled_at' => null,
            'status' => 'not scheduled'
        ]);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}