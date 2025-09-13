@extends('layouts.admin')

@section('title', 'CTU Danao HRMO - Create Job Posting')

@section('content')
<div class="p-6">
    <!-- Modern Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Job Posting</h1>
                <p class="text-gray-600">Add a new job position following CTU Danao HRMO format</p>
            </div>
            <a href="{{ route('admin.jobs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Back to Jobs</span>
            </a>
        </div>
    </div>

    <!-- Job Creation Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Header Section -->
        <div class="px-6 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-2 bg-white rounded-full"></div>
                <h3 class="text-xl font-bold">NOTICE OF VACANT POSITIONS</h3>
            </div>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('admin.jobs.store') }}" id="jobCreateForm">
                @csrf

                <!-- Job Details Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Job Details</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Position -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="e.g., INSTRUCTOR" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Item Number -->
                        <div>
                            <label for="item_number" class="block text-sm font-medium text-gray-700 mb-2">Item Number</label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('item_number') border-red-500 @enderror" 
                                   id="item_number" name="item_number" value="{{ old('item_number') }}" 
                                   placeholder="e.g., N/A">
                            @error('item_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campus -->
                        <div>
                            <label for="campus" class="block text-sm font-medium text-gray-700 mb-2">Campus <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('campus') border-red-500 @enderror" 
                                    id="campus" name="campus" required>
                                <option value="">Select Campus</option>
                                <option value="CTU - Danao Campus" {{ old('campus') == 'CTU - Danao Campus' ? 'selected' : '' }}>CTU - Danao Campus</option>
                                <option value="CTU - Main Campus" {{ old('campus') == 'CTU - Main Campus' ? 'selected' : '' }}>CTU - Main Campus</option>
                                <option value="CTU - Argao Campus" {{ old('campus') == 'CTU - Argao Campus' ? 'selected' : '' }}>CTU - Argao Campus</option>
                                <option value="CTU - Barili Campus" {{ old('campus') == 'CTU - Barili Campus' ? 'selected' : '' }}>CTU - Barili Campus</option>
                                <option value="CTU - Carmen Campus" {{ old('campus') == 'CTU - Carmen Campus' ? 'selected' : '' }}>CTU - Carmen Campus</option>
                                <option value="CTU - Daanbantayan Campus" {{ old('campus') == 'CTU - Daanbantayan Campus' ? 'selected' : '' }}>CTU - Daanbantayan Campus</option>
                                <option value="CTU - Dumanjug Campus" {{ old('campus') == 'CTU - Dumanjug Campus' ? 'selected' : '' }}>CTU - Dumanjug Campus</option>
                                <option value="CTU - Moalboal Campus" {{ old('campus') == 'CTU - Moalboal Campus' ? 'selected' : '' }}>CTU - Moalboal Campus</option>
                                <option value="CTU - Naga Extension Campus" {{ old('campus') == 'CTU - Naga Extension Campus' ? 'selected' : '' }}>CTU - Naga Extension Campus</option>
                                <option value="CTU - Pinamungajan Campus" {{ old('campus') == 'CTU - Pinamungajan Campus' ? 'selected' : '' }}>CTU - Pinamungajan Campus</option>
                                <option value="CTU - San Francisco Campus" {{ old('campus') == 'CTU - San Francisco Campus' ? 'selected' : '' }}>CTU - San Francisco Campus</option>
                                <option value="CTU - San Fernando Campus" {{ old('campus') == 'CTU - San Fernando Campus' ? 'selected' : '' }}>CTU - San Fernando Campus</option>
                                <option value="CTU - Tuburan Campus" {{ old('campus') == 'CTU - Tuburan Campus' ? 'selected' : '' }}>CTU - Tuburan Campus</option>
                            </select>
                            @error('campus')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- College/Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">College/Department <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('department') border-red-500 @enderror" 
                                    id="department" name="department" required>
                                <option value="">Select College/Department</option>
                                <option value="College of Engineering" {{ old('department') == 'College of Engineering' ? 'selected' : '' }}>College of Engineering</option>
                                <option value="College of Education" {{ old('department') == 'College of Education' ? 'selected' : '' }}>College of Education</option>
                                <option value="College of Arts and Sciences" {{ old('department') == 'College of Arts and Sciences' ? 'selected' : '' }}>College of Arts and Sciences</option>
                                <option value="College of Business and Management" {{ old('department') == 'College of Business and Management' ? 'selected' : '' }}>College of Business and Management</option>
                                <option value="College of Information and Communications Technology" {{ old('department') == 'College of Information and Communications Technology' ? 'selected' : '' }}>College of Information and Communications Technology</option>
                                <option value="College of Industrial Technology" {{ old('department') == 'College of Industrial Technology' ? 'selected' : '' }}>College of Industrial Technology</option>
                                <option value="College of Nursing" {{ old('department') == 'College of Nursing' ? 'selected' : '' }}>College of Nursing</option>
                                <option value="College of Agriculture" {{ old('department') == 'College of Agriculture' ? 'selected' : '' }}>College of Agriculture</option>
                                <option value="Office of the Campus Director" {{ old('department') == 'Office of the Campus Director' ? 'selected' : '' }}>Office of the Campus Director</option>
                                <option value="Registrar's Office" {{ old('department') == 'Registrar\'s Office' ? 'selected' : '' }}>Registrar's Office</option>
                                <option value="Library" {{ old('department') == 'Library' ? 'selected' : '' }}>Library</option>
                                <option value="Guidance and Counseling" {{ old('department') == 'Guidance and Counseling' ? 'selected' : '' }}>Guidance and Counseling</option>
                            </select>
                            @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Number of Vacancies -->
                        <div>
                            <label for="vacancies" class="block text-sm font-medium text-gray-700 mb-2">Number of Vacancies <span class="text-red-500">*</span></label>
                            <input type="number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('vacancies') border-red-500 @enderror" 
                                   id="vacancies" name="vacancies" value="{{ old('vacancies', 1) }}" min="1" required>
                            @error('vacancies')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Appointment Type -->
                        <div>
                            <label for="position_type" class="block text-sm font-medium text-gray-700 mb-2">Appointment Type <span class="text-red-500">*</span></label>
                            <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('position_type') border-red-500 @enderror" 
                                    id="position_type" name="position_type" required>
                                <option value="">Select Appointment Type</option>
                                <option value="Full-time" {{ old('position_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('position_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="Contract of Service" {{ old('position_type') == 'Contract of Service' ? 'selected' : '' }}>Contract of Service</option>
                                <option value="Job Order" {{ old('position_type') == 'Job Order' ? 'selected' : '' }}>Job Order</option>
                                <option value="Temporary" {{ old('position_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                            </select>
                            @error('position_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Salary Rate -->
                        <div>
                            <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Salary Rate <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('salary') border-red-500 @enderror" 
                                   id="salary" name="salary" value="{{ old('salary') }}" 
                                   placeholder="e.g., 160/hour, 25,000/month, SG-12" required>
                            @error('salary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Requirements Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Requirements</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Education Requirements -->
                        <div>
                            <label for="education_requirements" class="block text-sm font-medium text-gray-700 mb-2">Education Requirements <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('education_requirements') border-red-500 @enderror" 
                                      id="education_requirements" name="education_requirements" rows="4" 
                                      placeholder="e.g., Must be a Bachelor of Science in Chemical Engineering graduate&#10;Must possess at least Master of Engineering in Chemical Engineering" required>{{ old('education_requirements') }}</textarea>
                            @error('education_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Experience Requirements -->
                        <div>
                            <label for="experience_requirements" class="block text-sm font-medium text-gray-700 mb-2">Experience Requirements</label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('experience_requirements') border-red-500 @enderror" 
                                      id="experience_requirements" name="experience_requirements" rows="4" 
                                      placeholder="e.g., None required, At least 2 years teaching experience">{{ old('experience_requirements') }}</textarea>
                            @error('experience_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Training Requirements -->
                        <div>
                            <label for="training_requirements" class="block text-sm font-medium text-gray-700 mb-2">Training Requirements</label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('training_requirements') border-red-500 @enderror" 
                                      id="training_requirements" name="training_requirements" rows="4" 
                                      placeholder="e.g., None required, At least 40 hours of relevant training">{{ old('training_requirements') }}</textarea>
                            @error('training_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Eligibility/Licenses -->
                        <div>
                            <label for="eligibility_requirements" class="block text-sm font-medium text-gray-700 mb-2">Eligibility/Licenses <span class="text-red-500">*</span></label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('eligibility_requirements') border-red-500 @enderror" 
                                      id="eligibility_requirements" name="eligibility_requirements" rows="4" 
                                      placeholder="e.g., Licensed Chemical Engineer, Civil Service Professional" required>{{ old('eligibility_requirements') }}</textarea>
                            @error('eligibility_requirements')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Application Instructions Section -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Application Instructions</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Application Deadline -->
                        <div>
                            <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">Application Deadline <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('application_deadline') border-red-500 @enderror" 
                                   id="application_deadline" name="application_deadline" 
                                   value="{{ old('application_deadline') }}" min="{{ now()->format('Y-m-d') }}" required>
                            @error('application_deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Subject Format -->
                        <div>
                            <label for="email_subject_format" class="block text-sm font-medium text-gray-700 mb-2">Email Subject Format</label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email_subject_format') border-red-500 @enderror" 
                                   id="email_subject_format" name="email_subject_format" value="{{ old('email_subject_format', '(Position + Plantilla Number and Campus Assignment)') }}" 
                                   placeholder="e.g., (Position + Plantilla Number and Campus Assignment)">
                            @error('email_subject_format')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Job Description <span class="text-red-500">*</span></label>
                        <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror" 
                                  id="description" name="description" rows="6" 
                                  placeholder="Describe the job responsibilities, duties, and other relevant information..." required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Publication Settings -->
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 border-b border-gray-200 pb-2">Publication Settings</h4>
                    
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                   id="is_published" name="is_published" value="1"
                                   {{ old('is_published') ? 'checked' : '' }}>
                            <span class="ml-2 text-sm text-gray-700">Publish immediately</span>
                        </label>
                        <p class="text-sm text-gray-500">If unchecked, job will be saved as draft</p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.jobs.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Create Job Posting</span>
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
        
        // Auto-generate email subject format when position and campus are selected
        const positionField = document.getElementById('title');
        const campusField = document.getElementById('campus');
        const emailSubjectField = document.getElementById('email_subject_format');
        
        function updateEmailSubject() {
            const position = positionField.value;
            const campus = campusField.value;
            if (position && campus) {
                emailSubjectField.value = `(${position} - ${campus})`;
            }
        }
        
        positionField.addEventListener('input', updateEmailSubject);
        campusField.addEventListener('change', updateEmailSubject);
        
        // Form submission handling
        const form = document.getElementById('jobCreateForm');
        form.addEventListener('submit', function(e) {
            // Add any client-side validation here if needed
        });
    });
</script>
@endsection