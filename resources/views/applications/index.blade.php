@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">My Applications</h1>
                        <p class="text-gray-600 mt-2">Track the status of your job applications</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('jobs.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Apply for Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if($applications->count() > 0)
            <!-- Applications Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($applications as $application)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <!-- Application Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 line-clamp-2">{{ $application->job->title }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $application->job->department }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Applied {{ $application->created_at->format('M j, Y') }}</p>
                                </div>
                                <div class="ml-4">
                                    @php
                                        $statusInfo = app('App\Http\Controllers\ApplicationController')->getStatusProgress($application->status);
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($application->status === 'pending') bg-blue-100 text-blue-800
                                        @elseif($application->status === 'shortlisted') bg-yellow-100 text-yellow-800
                                        @elseif($application->status === 'interview_scheduled') bg-purple-100 text-purple-800
                                        @elseif($application->status === 'approved') bg-green-100 text-green-800
                                        @elseif($application->status === 'hired') bg-emerald-100 text-emerald-800
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="p-6">
                            <!-- Status Progress -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                    <span>Progress</span>
                                    <span>{{ $statusInfo['step'] }}/5</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        @if($application->status === 'pending') bg-blue-500 w-1/5
                                        @elseif($application->status === 'shortlisted') bg-yellow-500 w-2/5
                                        @elseif($application->status === 'interview_scheduled') bg-purple-500 w-3/5
                                        @elseif($application->status === 'approved') bg-green-500 w-4/5
                                        @elseif($application->status === 'hired') bg-emerald-500 w-full
                                        @elseif($application->status === 'rejected') bg-red-500 w-full
                                        @endif">
                                    </div>
                                </div>
                            </div>

                            <!-- Current Status -->
                            <div class="mb-4 p-3 rounded-lg 
                                @if($application->status === 'pending') bg-blue-50 border border-blue-200
                                @elseif($application->status === 'shortlisted') bg-yellow-50 border border-yellow-200
                                @elseif($application->status === 'interview_scheduled') bg-purple-50 border border-purple-200
                                @elseif($application->status === 'approved') bg-green-50 border border-green-200
                                @elseif($application->status === 'hired') bg-emerald-50 border border-emerald-200
                                @elseif($application->status === 'rejected') bg-red-50 border border-red-200
                                @endif">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 mr-2 
                                        @if($application->status === 'pending') text-blue-600
                                        @elseif($application->status === 'shortlisted') text-yellow-600
                                        @elseif($application->status === 'interview_scheduled') text-purple-600
                                        @elseif($application->status === 'approved') text-green-600
                                        @elseif($application->status === 'hired') text-emerald-600
                                        @elseif($application->status === 'rejected') text-red-600
                                        @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusInfo['icon'] }}"></path>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $statusInfo['title'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $statusInfo['description'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Interview Information -->
                            @if($application->interview && $application->interview->scheduled_at)
                                <div class="mb-4 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="font-medium text-gray-900">Interview Scheduled</p>
                                            <p class="text-sm text-gray-600">
                                                {{ $application->interview->scheduled_at->format('M j, Y \a\t g:i A') }}
                                            </p>
                                            @if($application->interview->interviewer_name)
                                                <p class="text-sm text-gray-500">Interviewer: {{ $application->interview->interviewer_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <a href="{{ route('applications.show', $application) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>
                                
                                @if($application->status !== 'rejected' && $application->status !== 'hired')
                                    <a href="{{ route('chat.show', ['applicant' => $application, 'job' => $application->job]) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        Chat
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by applying for a job position.</p>
                    <div class="mt-6">
                        <a href="{{ route('jobs.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Browse Jobs
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
