@extends('layouts.admin')

@section('title', 'CTU Danao HRMO - Applicant Details' . (isset($applicant) ? ' - ' . $applicant->name : ''))

@section('content')
@if(!isset($applicant))
    <div class="p-6">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            Applicant not found
        </div>
    </div>
@else
<div class="p-6">
    <!-- Modern Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.applicants.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Applicants</span>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $applicant->name ?? 'No name available' }}</h1>
                    <p class="text-gray-600">Application Details & Management</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($applicant->status === 'shortlisted') bg-green-100 text-green-800
                    @elseif($applicant->status === 'approved') bg-blue-100 text-blue-800
                    @elseif($applicant->status === 'hired') bg-emerald-100 text-emerald-800
                    @elseif($applicant->status === 'rejected') bg-red-100 text-red-800
                    @elseif($applicant->status === 'interview_scheduled') bg-purple-100 text-purple-800
                    @else bg-amber-100 text-amber-800 @endif">
                    {{ ucfirst(str_replace('_', ' ', $applicant->status)) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Applicant Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <p class="text-lg text-gray-900">{{ $applicant->name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <a href="mailto:{{ $applicant->email }}" class="text-lg text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                {{ $applicant->email ?? 'Not provided' }}
                            </a>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                            <p class="text-lg text-gray-900">{{ $applicant->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Applied Position</label>
                            <p class="text-lg text-gray-900">{{ $applicant->job->title ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                            <p class="text-lg text-gray-900">{{ $applicant->job->department ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Applied On</label>
                            <p class="text-lg text-gray-900">
                                @if($applicant->created_at)
                                    {{ $applicant->created_at->format('M j, Y \a\t g:i A') }}
                                @else
                                    Not available
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Documents</h3>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Resume -->
                    @if(!empty($applicant->resume_path))
                    <div class="flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Resume</p>
                                <p class="text-sm text-gray-500">Professional resume document</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.resume', $applicant) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @endif

                    <!-- Cover Letter -->
                    @if(!empty($applicant->cover_letter_path))
                    <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Cover Letter</p>
                                <p class="text-sm text-gray-500">Cover letter document</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.cover-letter', $applicant) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @endif

                    <!-- Transcript -->
                    @if(!empty($applicant->transcript_path))
                    <div class="flex items-center justify-between p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Transcript</p>
                                <p class="text-sm text-gray-500">Academic transcript</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.transcript', $applicant) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @endif

                    <!-- Certificate -->
                    @if(!empty($applicant->certificate_path))
                    <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Certificate</p>
                                <p class="text-sm text-gray-500">Professional certificate</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.certificate', $applicant) }}" 
                           class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @endif

                    <!-- Portfolio -->
                    @if(!empty($applicant->portfolio_path))
                    <div class="flex items-center justify-between p-4 bg-indigo-50 border border-indigo-200 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Portfolio</p>
                                <p class="text-sm text-gray-500">Work portfolio</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.portfolio', $applicant) }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @endif

                    <!-- Additional Documents -->
                    @if(!empty($applicant->additional_documents) && is_array($applicant->additional_documents))
                        @foreach($applicant->additional_documents as $index => $document)
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="p-3 bg-gray-100 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $document['name'] ?? 'Additional Document' }}</p>
                                    <p class="text-sm text-gray-500">Additional document</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.documents.additional', ['applicant' => $applicant, 'index' => $index]) }}" 
                               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Download</span>
                            </a>
                        </div>
                        @endforeach
                    @endif

                    @if(empty($applicant->resume_path) && empty($applicant->cover_letter_path) && empty($applicant->transcript_path) && empty($applicant->certificate_path) && empty($applicant->portfolio_path) && empty($applicant->additional_documents))
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No documents uploaded</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Management Card -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Status Management</h3>
                    </div>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.applicants.update-status', $applicant) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Status</label>
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium
                                        @if($applicant->status === 'shortlisted') bg-green-100 text-green-800
                                        @elseif($applicant->status === 'approved') bg-blue-100 text-blue-800
                                        @elseif($applicant->status === 'hired') bg-emerald-100 text-emerald-800
                                        @elseif($applicant->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($applicant->status === 'interview_scheduled') bg-purple-100 text-purple-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $applicant->status)) }}
                                    </span>
                                    @if($applicant->status === 'rejected' && $applicant->rejection_reason)
                                    <div class="mt-2 text-sm text-red-700">
                                        Reason: {{ $applicant->rejection_reason }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Change To</label>
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required onchange="document.getElementById('rejection-reason-wrapper').style.display = this.value === 'rejected' ? 'block' : 'none'">
                                    @foreach(['pending', 'shortlisted', 'interview_scheduled', 'approved', 'hired', 'rejected'] as $status)
                                        <option value="{{ $status }}" {{ $applicant->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="rejection-reason-wrapper" style="display: {{ $applicant->status === 'rejected' ? 'block' : 'none' }};">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                                <input type="text" name="rejection_reason" value="{{ old('rejection_reason', $applicant->rejection_reason) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="e.g., Not qualified for the job, Incomplete requirements, etc.">
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <label class="flex items-center space-x-2 text-sm text-gray-700">
                                    <input type="checkbox" name="is_editable_by_user" value="1" {{ $applicant->is_editable_by_user ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span>Allow applicant to edit documents</span>
                                </label>
                                <span class="text-xs text-gray-400">When enabled, applicant can update files</span>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Update Status</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Interview Section -->
            @if($applicant->interview && $applicant->interview->scheduled_at)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Scheduled Interview</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date & Time</label>
                            <p class="text-lg text-gray-900">{{ $applicant->interview->scheduled_at->format('l, F j, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Interviewer</label>
                            <p class="text-lg text-gray-900">{{ $applicant->interview->interviewer_name ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($applicant->interview->status) }}
                            </span>
                        </div>
                    </div>
                    @if($applicant->interview && $applicant->interview->id && $applicant->interview->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-900">{{ $applicant->interview->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($applicant->status === 'shortlisted')
                        <a href="{{ route('admin.interviews.create', $applicant) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ $applicant->interview && $applicant->interview->id ? 'Reschedule Interview' : 'Schedule Interview' }}</span>
                        </a>
                        @endif
                        
                        @if($applicant->interview && $applicant->interview->id)
                        <form id="cancel-interview-form" action="{{ route('admin.interviews.destroy', $applicant->interview) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 cancel-interview-btn">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Cancel Interview</span>
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('admin.chat.show', [$applicant->id, $applicant->job_id]) }}" 
                           class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>Chat with Applicant</span>
                        </a>
                        
                        <!-- Send Email Button -->
                        <button onclick="openGmail('{{ $applicant->email }}', 'Application Update - {{ $applicant->job->title }}', 'Dear {{ $applicant->name }},\n\nThank you for your interest in the {{ $applicant->job->title }} position at CTU Danao HRMO.\n\nWe would like to inform you about the status of your application.\n\nIf you have any questions, please don\'t hesitate to contact us.\n\nBest regards,\nCTU Danao HRMO Team')" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Send Email</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cancel interview confirmation
        const cancelInterviewBtn = document.querySelector('.cancel-interview-btn');
        if (cancelInterviewBtn) {
            cancelInterviewBtn.addEventListener('click', function() {
                const form = document.getElementById('cancel-interview-form');
                Swal.fire({
                    title: 'Cancel Interview?',
                    html: 'Are you sure you want to cancel this interview?<br>This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition-all duration-200 mx-2',
                        cancelButton: 'px-6 py-2.5 rounded-lg font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all duration-200 mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

        // Status change confirmation
        const statusForm = document.querySelector('form[action*="update-status"]');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Change Applicant Status?',
                    html: 'Are you sure you want to change this applicant\'s status?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'px-6 py-2.5 rounded-lg font-medium bg-green-600 text-white hover:bg-green-700 transition-all duration-200 mx-2',
                        cancelButton: 'px-6 py-2.5 rounded-lg font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all duration-200 mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }
    });

    function openGmail(email, subject, body) {
        // Try to open Gmail directly
        const gmailUrl = `https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}&su=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        
        // Open Gmail in a new tab
        const gmailWindow = window.open(gmailUrl, '_blank');
        
        // If Gmail doesn't open, fallback to mailto
        if (!gmailWindow || gmailWindow.closed || typeof gmailWindow.closed == 'undefined') {
            const mailtoUrl = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = mailtoUrl;
        }
    }
</script>
@endsection