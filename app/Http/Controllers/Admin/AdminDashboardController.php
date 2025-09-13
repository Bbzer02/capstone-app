<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Interview;
use App\Models\Job;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalJobs = Job::count();
        $totalApplications = Applicant::count();
        $pendingApplications = Applicant::where('status', 'pending')->count();
        $scheduledInterviews = Interview::where('status', 'scheduled')->count();
        
        // Get recent applications
        $recentApplications = Applicant::with('job')
            ->latest()
            ->take(5)
            ->get();
            
        // Get recent jobs with application counts
        $recentJobs = Job::withCount('applicants')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalJobs',
            'totalApplications',
            'pendingApplications',
            'scheduledInterviews',
            'recentApplications',
            'recentJobs'
        ));
    }
}