@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600">Here's what's happening with your job search.</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('jobs.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Browse Jobs</h3>
                    <p class="text-gray-600">Find your next opportunity</p>
                </div>
            </div>
        </a>

        <a href="{{ route('profile.show') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">My Profile</h3>
                    <p class="text-gray-600">View your account information</p>
                </div>
            </div>
        </a>

        <a href="{{ route('applications.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">My Applications</h3>
                    <p class="text-gray-600">Track your job applications</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
            </div>
            <div class="p-6">
                @if(isset($userApplications) && $userApplications->count() > 0)
                    <div class="space-y-4">
                        @foreach($userApplications as $application)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex-1">
                                    <a href="{{ route('applications.show', $application) }}" class="block">
                                        <h3 class="font-medium text-gray-900 hover:text-blue-600 transition-colors duration-200">{{ $application->job->title ?? 'Job Title' }}</h3>
                                        <p class="text-sm text-gray-600">{{ $application->job->department ?? 'Department' }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Applied {{ $application->created_at->format('M j, Y') }}</p>
                                    </a>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                                        @if($application->status === 'pending') bg-blue-100 text-blue-800
                                        @elseif($application->status === 'shortlisted') bg-yellow-100 text-yellow-800
                                        @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                                        @elseif($application->status === 'approved') bg-green-100 text-green-800
                                        @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                    <a href="{{ route('applications.show', $application) }}" 
                                       class="px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>View</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start applying to jobs to see your applications here.</p>
                        <div class="mt-6">
                            <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Browse Jobs
                            </a>
                        </div>
                    </div>
                @endif
                
                @if(isset($userApplications) && $userApplications->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('applications.index') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            View All Applications
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Featured Jobs -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Featured Jobs</h2>
            </div>
            <div class="p-6">
                @if(isset($recentJobs) && $recentJobs->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentJobs as $job)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $job->title }}</h3>
                                        <p class="text-sm text-gray-600">{{ $job->department }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ $job->formattedSalary() }}</p>
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('jobs.show', $job) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Job
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('jobs.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Jobs
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs available</h3>
                        <p class="mt-1 text-sm text-gray-500">Check back later for new job postings.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
