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
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'transcript' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'portfolio' => 'nullable|file|mimes:pdf,zip|max:20480',
            'additional_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);
        
        // Check if user already applied
        $existingApplication = Applicant::where('email', Auth::user()->email)
            ->where('job_id', $job->id)
            ->first();
            
        if ($existingApplication) {
            return back()->with('error', 'You have already applied for this job.');
        }
        
        // Store resume first to satisfy non-null DB constraint
        $tempResumePath = $request->file('resume')->store('applicants', 'public');

        // Create application with resume path
        $applicant = Applicant::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => $request->phone ?? '',
            'cover_letter' => $request->cover_letter,
            'resume_path' => $tempResumePath,
            'status' => 'pending',
        ]);

        $basePath = 'applicants/' . $applicant->id;
        $updates = [];

        // Optionally re-store resume under applicant-specific folder for consistency
        if ($request->hasFile('resume')) {
            $updates['resume_path'] = $request->file('resume')->store($basePath, 'public');
        }
        if ($request->hasFile('cover_letter_file')) {
            $updates['cover_letter_path'] = $request->file('cover_letter_file')->store($basePath, 'public');
        }
        if ($request->hasFile('transcript')) {
            $updates['transcript_path'] = $request->file('transcript')->store($basePath, 'public');
        }
        if ($request->hasFile('certificate')) {
            $updates['certificate_path'] = $request->file('certificate')->store($basePath, 'public');
        }
        if ($request->hasFile('portfolio')) {
            $updates['portfolio_path'] = $request->file('portfolio')->store($basePath, 'public');
        }
        if ($request->hasFile('additional_documents')) {
            $additional = [];
            foreach ($request->file('additional_documents') as $file) {
                $stored = $file->store($basePath, 'public');
                $additional[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $stored,
                ];
            }
            $updates['additional_documents'] = $additional;
        }

        if (!empty($updates)) {
            $applicant->update($updates);
        }
        
        // Send email notification
        try {
            Mail::to($applicant->email)->send(new ApplicationReceived($applicant));
        } catch (\Exception $e) {
            // Log error but don't fail the application
            \Log::error('Failed to send application received email: ' . $e->getMessage());
        }
        
        return redirect()->route('applications.index')->with('success', 'Your application has been submitted successfully!');
    }
}