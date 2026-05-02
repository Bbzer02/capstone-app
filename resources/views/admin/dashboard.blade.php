@extends('layouts.admin')

@section('title', 'CTU Danao HRMO - Admin Dashboard')

@section('content')
<!-- Modern Header with Gradient -->
<div class="relative mb-8 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800"></div>
    <div class="relative px-8 py-12">
        <div class="flex items-center justify-between">
            <div class="text-white">
                <h1 class="text-4xl font-bold mb-2">Welcome to HRMO Dashboard</h1>
                <p class="text-blue-100 text-lg">Manage your hiring process efficiently</p>
                <div class="mt-4 flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm text-blue-100">System Online</span>
                    </div>
                    <div class="text-sm text-blue-200">
                        Last updated: {{ now()->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>
            </div>
            
            <!-- Search and Quick Actions -->
            <div class="flex items-center space-x-4">
                <!-- Enhanced Search -->
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none pl-4">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input class="w-80 pl-12 pr-4 py-3 bg-white/90 backdrop-blur-sm border-0 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50 focus:bg-white shadow-lg" 
                           placeholder="Search applications, jobs, candidates..." type="search">
                </div>
                
                <!-- Quick Action Buttons -->
                <div class="flex space-x-3">
                    <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>New Job</span>
                    </a>
                    <a href="{{ route('admin.applicants.index') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
</div>

<!-- Modern Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Jobs Card -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-blue-200/50">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-blue-600 mb-1">Total Job Postings</p>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $totalJobs ?? 0 }}</p>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>+12% this month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Applications Card -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-green-100 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-green-200/50">
        <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-green-600 mb-1">Total Applications</p>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $totalApplications ?? 0 }}</p>
                    <div class="flex items-center text-sm text-green-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>+8% this month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Applications Card -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-amber-200/50">
        <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-amber-600 mb-1">Pending Review</p>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $pendingApplications ?? 0 }}</p>
                    <div class="flex items-center text-sm text-amber-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <span>Needs attention</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Interviews Scheduled Card -->
    <div class="group relative overflow-hidden bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 border border-purple-200/50">
        <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent"></div>
        <div class="relative p-6">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-purple-600 mb-1">Interviews Scheduled</p>
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $scheduledInterviews ?? 0 }}</p>
                    <div class="flex items-center text-sm text-purple-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span>This week</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Recent Applications -->
    <div class="lg:col-span-2">
        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-gray-200/50 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Applications</h3>
                    </div>
                    <a href="{{ route('admin.applicants.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 text-sm font-medium flex items-center space-x-2">
                        <span>View all</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($recentApplications ?? [] as $application)
                    <a href="{{ route('admin.applicants.show', $application) }}" class="group flex items-center justify-between py-4 px-4 rounded-xl hover:bg-gray-50/50 transition-all duration-200 border-b border-gray-100/50 last:border-b-0">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">{{ substr($application->name, 0, 1) }}</span>
                                </div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                            </div>
                            <div>
                                <h4 class="text-base font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $application->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $application->job->title ?? 'No job title' }}</p>
                                <p class="text-xs text-gray-400 flex items-center space-x-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $application->created_at->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold 
                                @if($application->status === 'pending') bg-amber-100 text-amber-800 border border-amber-200
                                @elseif($application->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                @else bg-red-100 text-red-800 border border-red-200 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                            <button class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No applications yet</h3>
                        <p class="text-gray-500 mb-4">Applications will appear here when candidates apply.</p>
                        <a href="{{ route('admin.jobs.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 text-sm font-medium">
                            Create Job Posting
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions & Recent Jobs -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-gray-200/50 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200/50">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                </div>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.jobs.create') }}" class="group flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl hover:from-blue-100 hover:to-blue-200 transition-all duration-200 border border-blue-200/50">
                    <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-gray-900 group-hover:text-blue-700">Post New Job</p>
                        <p class="text-sm text-gray-600">Create a new job posting</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.applicants.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl hover:from-green-100 hover:to-green-200 transition-all duration-200 border border-green-200/50">
                    <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-gray-900 group-hover:text-green-700">Review Applications</p>
                        <p class="text-sm text-gray-600">Manage candidate applications</p>
                    </div>
                </a>
                
                <a href="{{ route('admin.applicants.index') }}" class="group flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl hover:from-purple-100 hover:to-purple-200 transition-all duration-200 border border-purple-200/50">
                    <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-gray-900 group-hover:text-purple-700">Schedule Interview</p>
                        <p class="text-sm text-gray-600">Set up candidate interviews</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Job Postings -->
        <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-gray-200/50 overflow-hidden">
            <div class="px-6 py-5 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Recent Jobs</h3>
                    </div>
                    <a href="{{ route('admin.jobs.index') }}" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors duration-200 text-sm font-medium">
                        View all
                    </a>
                </div>
            </div>
            <div class="p-6">
                @forelse($recentJobs ?? [] as $job)
                    <div class="group py-4 px-4 rounded-xl hover:bg-gray-50/50 transition-all duration-200 border-b border-gray-100/50 last:border-b-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-base font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $job->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $job->department }} • {{ $job->position_type }}</p>
                                <p class="text-sm text-gray-500 mt-1 font-medium">{{ $job->formattedSalary() }}</p>
                            </div>
                            <div class="ml-3 flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $job->is_published ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                    {{ $job->is_published ? 'Published' : 'Draft' }}
                                </span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ $job->applicants_count ?? 0 }} applications</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500">No job postings yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modern Activity Feed -->
<div class="mt-8">
    <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-2xl border border-gray-200/50 overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-rose-50 to-pink-50 border-b border-gray-200/50">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
                <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Activity Item 1 -->
                <div class="flex items-start space-x-4 group">
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-blue-600 transition-colors">New job posting created</p>
                                <p class="text-sm text-gray-600 mt-1">Assistant Professor - Computer Science</p>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">2h ago</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Item 2 -->
                <div class="flex items-start space-x-4 group">
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-blue-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-green-600 transition-colors">New application received</p>
                                <p class="text-sm text-gray-600 mt-1">John Doe applied for IT Support Specialist</p>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">4h ago</span>
                        </div>
                    </div>
                </div>

                <!-- Activity Item 3 -->
                <div class="flex items-start space-x-4 group">
                    <div class="relative">
                        <div class="h-12 w-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-purple-400 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-purple-600 transition-colors">Interview scheduled</p>
                                <p class="text-sm text-gray-600 mt-1">Jane Smith - Librarian position</p>
                            </div>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">1d ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setInterval(function() {
        location.reload();
    }, 300000);
</script>
@endsection
