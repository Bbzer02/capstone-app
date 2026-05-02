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
       
        $totalJobs = Job::count();
        $totalApplications = Applicant::count();
        $pendingApplications = Applicant::where('status', 'pending')->count();
        $scheduledInterviews = Interview::where('status', 'scheduled')->count();
        
       
        $recentApplications = Applicant::with('job')
            ->latest()
            ->take(5)
            ->get();
            
       
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