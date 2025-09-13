<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'name',
        'email',
        'phone',
        'cover_letter',
        'resume_path',
        'status'
    ];

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
}