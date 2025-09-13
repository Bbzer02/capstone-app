<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'scheduled_at',
        'notes',
        'status',
        'type',
        'interviewer_name'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}