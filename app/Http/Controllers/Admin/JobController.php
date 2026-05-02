<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::latest()->paginate(10);

        
        $jobs->transform(function ($job) {
            $job->application_deadline = Carbon::parse($job->application_deadline);
            return $job;
        });

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'position_type' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'application_deadline' => 'required|date|after_or_equal:today',
            'is_published' => 'nullable|boolean',
            'item_number' => 'nullable|string|max:255',
            'campus' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:1',
            // All requirement fields are now optional so admin can decide what to include
            'education_requirements' => 'nullable|string',
            'experience_requirements' => 'nullable|string',
            'training_requirements' => 'nullable|string',
            'eligibility_requirements' => 'nullable|string',
            'email_subject_format' => 'nullable|string|max:255'
        ]);

        $validated['application_deadline'] = Carbon::parse($validated['application_deadline']);

        if ($validated['application_deadline']->isPast()) {
            return back()->with('error', 'The application deadline cannot be in the past.')
                        ->withInput();
        }

        $validated['is_published'] = $validated['is_published'] ?? false;

        Job::create($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting created successfully!');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'required|string|max:255',
            'position_type' => 'required|string|max:255',
            'salary' => 'required|string|max:255',
            'application_deadline' => 'required|date|after_or_equal:today',
            'is_published' => 'nullable|boolean',
            'item_number' => 'nullable|string|max:255',
            'campus' => 'required|string|max:255',
            'vacancies' => 'required|integer|min:1',
            // All requirement fields are now optional so admin can decide what to keep/omit
            'education_requirements' => 'nullable|string',
            'experience_requirements' => 'nullable|string',
            'training_requirements' => 'nullable|string',
            'eligibility_requirements' => 'nullable|string',
            'email_subject_format' => 'nullable|string|max:255'
        ]);

        $validated['application_deadline'] = Carbon::parse($validated['application_deadline']);

        if ($validated['application_deadline']->isPast()) {
            return back()->with('error', 'The application deadline cannot be in the past.');
        }

        $validated['is_published'] = $validated['is_published'] ?? false;

        $job->update($validated);

        return redirect()->route('admin.jobs.index')->with('success', 'Job posting updated successfully!');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully!');
    }

    public function togglePublish(Job $job)
    {
  
        $job->update(['is_published' => !$job->is_published]);

        $status = $job->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Job {$status} successfully!");
    }
}