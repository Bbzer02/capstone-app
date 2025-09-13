@extends('layouts.admin')

@section('title', 'CTU Danao HRMO - Schedule Interview')

@section('content')
<div class="p-6">
    <!-- Modern Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.applicants.show', $applicant) }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Back to Applicant</span>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Schedule Interview</h1>
                    <p class="text-gray-600">Set up interview details for {{ $applicant->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applicant Info Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <h3 class="text-xl font-bold text-gray-900">Applicant Information</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Applicant Name</label>
                    <p class="text-lg text-gray-900">{{ $applicant->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position Applied</label>
                    <p class="text-lg text-gray-900">{{ $job->title ?? 'No position specified' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Application Deadline</label>
                    <p class="text-lg text-gray-900">
                        @if($job->application_deadline)
                            {{ $job->application_deadline->format('M d, Y') }}
                            <span class="text-sm text-gray-500 block">{{ $job->application_deadline->diffForHumans() }}</span>
                        @else
                            Not set
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Interview Scheduling Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <h3 class="text-xl font-bold text-gray-900">Interview Details</h3>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.interviews.store', $applicant) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interview Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" 
                               name="scheduled_at" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('scheduled_at') border-red-500 @enderror"
                               min="{{ now()->addDay()->format('Y-m-d\TH:i') }}"
                               value="{{ old('scheduled_at') }}"
                               required>
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Interview Type <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('type') border-red-500 @enderror" required>
                            <option value="">Select Interview Type</option>
                            <option value="in_person" {{ old('type') == 'in_person' ? 'selected' : '' }}>In-Person</option>
                            <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>Phone Call</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video Call</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interviewer Name</label>
                    <input type="text" 
                           name="interviewer_name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('interviewer_name') border-red-500 @enderror"
                           placeholder="Enter interviewer's name"
                           value="{{ old('interviewer_name') }}">
                    @error('interviewer_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interview Notes</label>
                    <textarea name="notes" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('notes') border-red-500 @enderror" 
                              rows="4" 
                              placeholder="Interview details, location, special instructions, or any additional notes...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($applicant->interview)
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-amber-800 font-medium">Note: This will replace any existing interview schedule</p>
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.applicants.show', $applicant) }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Schedule Interview</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const defaultTime = new Date(now);
        
        // Set to next day if after 5pm
        if (now.getHours() >= 17) {
            defaultTime.setDate(now.getDate() + 1);
        }
        
        // Set to next business hour (10am)
        defaultTime.setHours(10, 0, 0, 0);
        
        // Format for datetime-local input
        const formatted = defaultTime.toISOString().slice(0, 16);
        const datetimeInput = document.querySelector('input[type="datetime-local"]');
        if (datetimeInput && !datetimeInput.value) {
            datetimeInput.value = formatted;
        }
    });
</script>
@endsection