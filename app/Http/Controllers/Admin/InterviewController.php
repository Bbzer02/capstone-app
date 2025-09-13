<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;  
use App\Models\Applicant;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    public function create(Applicant $applicant)
{
   
    if (!$applicant->exists || !$applicant->job) {
        return redirect()
            ->route('admin.applicants.index')
            ->with('error', 'Applicant or job information missing');
    }

    
    if ($applicant->interview) {
        return redirect()
            ->route('admin.applicants.show', $applicant)
            ->with('warning', 'Interview already scheduled!');
    }

    return view('admin.interviews.create', [
        'applicant' => $applicant->load('job'), 
        'job' => $applicant->job
    ]);
}

public function store(Request $request, Applicant $applicant)
{
    $validated = $request->validate([
        'scheduled_at' => 'required|date|after:now',
        'notes' => 'nullable|string|max:500',
        'type' => 'required|in:in_person,phone,video',
        'interviewer_name' => 'nullable|string|max:255'
    ]);

    
    if (!$applicant->exists) {
        return back()->with('error', 'Applicant not found');
    }

    
    $interview = $applicant->interview()->updateOrCreate(
        ['applicant_id' => $applicant->id],
        [
            'scheduled_at' => $validated['scheduled_at'],
            'notes' => $validated['notes'],
            'type' => $validated['type'],
            'interviewer_name' => $validated['interviewer_name'],
            'status' => 'scheduled'
        ]
    );

    return redirect()
        ->route('admin.applicants.show', $applicant)
        ->with('success', 'Interview scheduled successfully!');
}

public function destroy(Interview $interview)
{
    $applicant = $interview->applicant;
    $interview->delete();
    
    return redirect()
        ->route('admin.applicants.show', $applicant)
        ->with('success', 'Interview cancelled successfully!');
}
}