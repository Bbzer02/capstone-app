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
                    @elseif($applicant->status === 'rejected') bg-red-100 text-red-800
                    @elseif($applicant->status === 'interview_scheduled') bg-blue-100 text-blue-800
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
                            <p class="text-lg text-gray-900">{{ $applicant->created_at->format('M j, Y \a\t g:i A') }}</p>
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
                <div class="p-6">
                    @if(!empty($applicant->resume_path))
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Resume Available</p>
                            <p class="text-sm text-gray-500">Click to download the applicant's resume</p>
                        </div>
                        <a href="{{ asset('storage/' . $applicant->resume_path) }}" 
                           class="ml-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
                           target="_blank"
                           download>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                    </div>
                    @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">No resume uploaded</p>
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
                                        @elseif($applicant->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($applicant->status === 'interview_scheduled') bg-blue-100 text-blue-800
                                        @else bg-amber-100 text-amber-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $applicant->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Change To</label>
                                <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                                    @foreach(['pending', 'shortlisted', 'rejected'] as $status)
                                        <option value="{{ $status }}" {{ $applicant->status === $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
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
            @if($applicant->interview)
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
                    @if($applicant->interview->notes)
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
                            <span>{{ $applicant->interview ? 'Reschedule Interview' : 'Schedule Interview' }}</span>
                        </a>
                        @endif
                        
                        @if($applicant->interview)
                        <form action="{{ route('admin.interviews.destroy', $applicant->interview) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2"
                                    onclick="return confirm('Are you sure you want to cancel this interview?')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Cancel Interview</span>
                            </button>
                        </form>
                        @endif
                        
                        <a href="mailto:{{ $applicant->email }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Send Email</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusForm = document.querySelector('form[action*="update-status"]');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to change this applicant\'s status?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
@endsection