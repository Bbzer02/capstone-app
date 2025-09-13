<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationReceived;

class JobListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::where('is_published', true)
                   ->where('application_deadline', '>=', now());
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        
        if ($request->filled('position_type')) {
            $query->where('position_type', $request->position_type);
        }
        
        $jobs = $query->latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    public function show(Job $job)
    {
        if (!$job->is_published || $job->application_deadline < now()) {
            abort(404);
        }

        return view('jobs.show', compact('job'));
    }
    
    public function apply(Request $request, Job $job)
    {
        $request->validate([
            'cover_letter' => 'required|string|max:2000',
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);
        
        // Check if user already applied
        $existingApplication = Applicant::where('email', Auth::user()->email)
            ->where('job_id', $job->id)
            ->first();
            
        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this job.');
        }
        
        // Store resume
        $resumePath = $request->file('resume')->store('resumes', 'public');
        
        // Create application
        $applicant = Applicant::create([
            'job_id' => $job->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => $request->phone ?? '',
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);
        
        // Send email notification
        try {
            Mail::to($applicant->email)->send(new ApplicationReceived($applicant));
        } catch (\Exception $e) {
            // Log error but don't fail the application
            \Log::error('Failed to send application received email: ' . $e->getMessage());
        }
        
        return back()->with('success', 'Your application has been submitted successfully!');
    }
}