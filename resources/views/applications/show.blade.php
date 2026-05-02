@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $application->job->title }}</h1>
                        <p class="text-gray-600 mt-2">{{ $application->job->department }} • Applied {{ $application->created_at->format('M j, Y') }}</p>
                    </div>
                    <a href="{{ route('applications.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Applications
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Application Tracking Timeline -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">Application Progress</h3>
                        <p class="text-gray-600 mt-1">Track your application status</p>
                    </div>
                    <div class="p-6">
                        <!-- Timeline -->
                        <div class="space-y-6">
                            @php
                                $allStatuses = ['pending', 'shortlisted', 'interview_scheduled', 'approved', 'hired'];
                                $currentStatus = $application->status;
                                $currentStep = app('App\Http\Controllers\ApplicationController')->getStatusProgress($currentStatus)['step'];
                            @endphp

                            @foreach($allStatuses as $index => $status)
                                @php
                                    $statusInfo = app('App\Http\Controllers\ApplicationController')->getStatusProgress($status);
                                    $isCompleted = $currentStep > $statusInfo['step'];
                                    $isCurrent = $currentStatus === $status;
                                    $isRejected = $currentStatus === 'rejected';
                                @endphp

                                <div class="flex items-start space-x-4">
                                    <!-- Timeline Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                                            @if($isCompleted) bg-green-100 text-green-600
                                            @elseif($isCurrent && !$isRejected) bg-blue-100 text-blue-600
                                            @elseif($isRejected && $status === 'rejected') bg-red-100 text-red-600
                                            @else bg-gray-100 text-gray-400
                                            @endif">
                                            @if($isCompleted)
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusInfo['icon'] }}"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Timeline Content -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $statusInfo['title'] }}</h4>
                                            @if($isCurrent && !$isRejected)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Current
                                                </span>
                                            @elseif($isCompleted)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 mt-1">{{ $statusInfo['description'] }}</p>
                                        
                                        @if($isCurrent && $status === 'interview_scheduled' && $application->interview && $application->interview->scheduled_at)
                                            <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <div>
                                                        <p class="font-medium text-gray-900">Interview Details</p>
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
                                    </div>
                                </div>

                                <!-- Timeline Connector -->
                                @if($index < count($allStatuses) - 1)
                                    <div class="ml-5 border-l-2 border-gray-200 h-6"></div>
                                @endif
                            @endforeach

                            @if($currentStatus === 'rejected')
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-red-100 text-red-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-medium text-gray-900">Not Selected</h4>
                                        <p class="text-gray-600 mt-1">Unfortunately, you were not selected for this position. Keep applying for other opportunities!</p>
                                        @if($application->rejection_reason)
                                        <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded">
                                            <p class="text-sm text-red-700"><span class="font-medium">Reason:</span> {{ $application->rejection_reason }}</p>
                                        </div>
                                        @endif
                                        <div class="mt-4">
                                            <a href="{{ route('applications.edit-documents', $application) }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Update Submitted Documents
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mt-8">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-900">Application Details</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Application ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Applied Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->created_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Position Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($application->job->position_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Salary Range</dt>
                                <dd class="mt-1 text-sm text-gray-900">₱{{ number_format($application->job->salary, 2) }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Cover Letter</dt>
                                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $application->cover_letter ?: 'No cover letter provided' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('applications.edit-documents', $application) }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Submitted Documents
                        </a>
                        @if($application->status !== 'rejected' && $application->status !== 'hired')
                            <a href="{{ route('chat.show', ['applicant' => $application, 'job' => $application->job]) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Chat with HR
                            </a>
                        @endif
                        <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 hover:bg-red-50 text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Delete Application
                            </button>
                        </form>
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Update Profile
                        </a>
                    </div>
                </div>

                <!-- Job Information -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Job Information</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Department</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->job->department }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Location</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->job->campus ?? 'CTU Danao' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Vacancies</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $application->job->vacancies ?? 'Not specified' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Application Deadline</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $application->job->application_deadline ? $application->job->application_deadline->format('M j, Y') : 'No deadline' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Documents Section -->
                @if(!empty($application->resume_path) || !empty($application->cover_letter_path) || !empty($application->transcript_path) || !empty($application->certificate_path) || !empty($application->portfolio_path) || !empty($application->additional_documents))
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Your Submitted Documents</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if(!empty($application->resume_path))
                        <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Resume</span>
                            </div>
                            <a href="{{ route('documents.resume', $application) }}" 
                               class="text-green-600 hover:text-green-800 text-sm font-medium transition-colors duration-200">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!empty($application->cover_letter_path))
                        <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Cover Letter</span>
                            </div>
                            <a href="{{ route('documents.cover-letter', $application) }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!empty($application->transcript_path))
                        <div class="flex items-center justify-between p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Transcript</span>
                            </div>
                            <a href="{{ route('documents.transcript', $application) }}" 
                               class="text-purple-600 hover:text-purple-800 text-sm font-medium transition-colors duration-200">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!empty($application->certificate_path))
                        <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Certificate</span>
                            </div>
                            <a href="{{ route('documents.certificate', $application) }}" 
                               class="text-yellow-600 hover:text-yellow-800 text-sm font-medium transition-colors duration-200">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!empty($application->portfolio_path))
                        <div class="flex items-center justify-between p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-900">Portfolio</span>
                            </div>
                            <a href="{{ route('documents.portfolio', $application) }}" 
                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors duration-200">
                                Download
                            </a>
                        </div>
                        @endif

                        @if(!empty($application->additional_documents) && is_array($application->additional_documents))
                            @foreach($application->additional_documents as $index => $document)
                            <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">{{ $document['name'] ?? 'Additional Document' }}</span>
                                </div>
                                <a href="{{ route('documents.additional', ['applicant' => $application, 'index' => $index]) }}" 
                                   class="text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors duration-200">
                                    Download
                                </a>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endif

                <!-- Contact Information -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">Need Help?</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">If you have any questions about your application, please contact us:</p>
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                hrmo@ctu.edu.ph
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                (032) 123-4567
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
