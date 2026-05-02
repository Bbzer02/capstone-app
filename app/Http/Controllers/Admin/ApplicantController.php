<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Http\Request;


class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['job', 'interview', 'user']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('job', function($jobQuery) use ($search) {
                      $jobQuery->where('title', 'like', "%{$search}%")
                               ->orWhere('department', 'like', "%{$search}%");
                  });
            });
        }

        $applicants = $query->latest()->paginate(10);

        return view('admin.applicants.index', compact('applicants'));
    }

    public function show(Applicant $applicant)
    {
        $applicant = $applicant->load(['job', 'interview', 'user']);
        return view('admin.applicants.show', compact('applicant'));
    }

    public function updateStatus(Request $request, Applicant $applicant)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,shortlisted,interview_scheduled,approved,hired,rejected',
            'rejection_reason' => 'nullable|string|max:255',
            'is_editable_by_user' => 'nullable|boolean'
        ]);

        $update = ['status' => $validated['status']];

        if ($validated['status'] === 'rejected') {
            $update['rejection_reason'] = $validated['rejection_reason'] ?? 'Not specified';
        } else {
            // Clear rejection reason if moving away from rejected
            $update['rejection_reason'] = null;
        }

        $update['is_editable_by_user'] = (bool) $request->boolean('is_editable_by_user');

        $applicant->update($update);
        return back()->with('success', 'Applicant status updated!');
        
    }
    

    
}