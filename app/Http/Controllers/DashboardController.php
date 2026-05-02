<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Applicant;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get recent job postings
        $recentJobs = Job::published()
            ->active()
            ->latest()
            ->take(6)
            ->get();
            
        // Get user's applications if they have any
        $userApplications = collect();
        if ($user) {
            $userApplications = Applicant::where('user_id', $user->id)
                ->with('job')
                ->latest()
                ->take(5)
                ->get();
        }
        
        return view('dashboard', compact('recentJobs', 'userApplications'));
    }
}