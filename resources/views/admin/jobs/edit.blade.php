@extends('layouts.admin')

@section('title', 'CTU Danao HRMO - Edit Job Posting')

@section('content')
<div class="p-6">
    <!-- Modern Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Job Posting</h1>
                <p class="text-gray-600">Update job position details in the CTU Danao HRMO system</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Jobs</span>
            </a>
        </div>
    </div>

    <!-- Job Edit Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <h3 class="text-xl font-bold text-gray-900">Job Details</h3>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" id="jobEditForm">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Job Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Job Title <span class="text-red-500">*</span></label>
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" 
                               id="title" name="title" value="{{ old('title', $job->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position Type -->
                    <div>
                        <label for="position_type" class="block text-sm font-medium text-gray-700 mb-2">Position Type <span class="text-red-500">*</span></label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position_type') border-red-500 @enderror" 
                                id="position_type" name="position_type" required>
                            <option value="">Select Position Type</option>
                            <option value="Full-time" {{ old('position_type', $job->position_type) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                            <option value="Part-time" {{ old('position_type', $job->position_type) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                            <option value="Contract" {{ old('position_type', $job->position_type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                            <option value="Temporary" {{ old('position_type', $job->position_type) == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                        </select>
                        @error('position_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                        <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror" 
                                id="department" name="department" required>
                            <option value="">Select Department</option>
                            @foreach(['Academic Affairs', 'Administration', 'Finance', 'Human Resources', 'Information Technology', 'Student Affairs', 'Research and Development'] as $dept)
                                <option value="{{ $dept }}" {{ old('department', $job->department) == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Salary -->
                    <div>
                        <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Salary (₱)</label>
                        <input type="number" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary') border-red-500 @enderror" 
                               id="salary" name="salary" value="{{ old('salary', $job->salary) }}" min="0" step="0.01">
                        @error('salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Job Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Job Description <span class="text-red-500">*</span></label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                              id="description" name="description" rows="6" required>{{ old('description', $job->description) }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Describe responsibilities, requirements, and benefits</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Application Deadline -->
                    <div>
                        <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">Application Deadline <span class="text-red-500">*</span></label>
                        <input type="date" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('application_deadline') border-red-500 @enderror" 
                               id="application_deadline" name="application_deadline" 
                               value="{{ old('application_deadline', $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}" min="{{ now()->format('Y-m-d') }}" required>
                        @error('application_deadline')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Publish Toggle -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Publication Status</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       id="is_published" name="is_published" value="1"
                                       {{ old('is_published', $job->is_published) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">Publish immediately</span>
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">If unchecked, job will be saved as draft</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.jobs.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 font-medium shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Job
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
        // Set minimum date for deadline (today)
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('application_deadline').min = today;
        
        // Form submission handling
        const form = document.getElementById('jobEditForm');
        form.addEventListener('submit', function(e) {
            // Add any client-side validation here if needed
        });
    });
</script>
@endsection