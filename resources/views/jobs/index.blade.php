@extends('layouts.app')

@section('title', 'Browse Jobs')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Browse Jobs</h1>
        <p class="mt-2 text-gray-600">Find your next career opportunity</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Job title, company, or keywords"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <select name="department" id="department" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Departments</option>
                        <option value="Engineering" {{ request('department') === 'Engineering' ? 'selected' : '' }}>Engineering</option>
                        <option value="Marketing" {{ request('department') === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Sales" {{ request('department') === 'Sales' ? 'selected' : '' }}>Sales</option>
                        <option value="HR" {{ request('department') === 'HR' ? 'selected' : '' }}>Human Resources</option>
                        <option value="Finance" {{ request('department') === 'Finance' ? 'selected' : '' }}>Finance</option>
                    </select>
                </div>
                <div>
                    <label for="position_type" class="block text-sm font-medium text-gray-700">Position Type</label>
                    <select name="position_type" id="position_type" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">All Types</option>
                        <option value="Full-time" {{ request('position_type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="Part-time" {{ request('position_type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="Contract" {{ request('position_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                        <option value="Remote" {{ request('position_type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Search Jobs
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Job Listings -->
    <div class="space-y-6">
        @forelse($jobs as $job)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            <h3 class="text-xl font-semibold text-gray-900">
                                <a href="{{ route('jobs.show', $job) }}" class="hover:text-blue-600">
                                    {{ $job->title }}
                                </a>
                            </h3>
                            @if($job->is_published)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </div>
                        
                        <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $job->department }}
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $job->position_type }}
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                {{ $job->formattedSalary() }}
                            </span>
                        </div>
                        
                        <p class="mt-3 text-gray-600 line-clamp-2">
                            {{ Str::limit($job->description, 200) }}
                        </p>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Apply by {{ $job->application_deadline->format('M d, Y') }}
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="{{ route('jobs.show', $job) }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    View Details
                                </a>
                                @auth
                                    <button onclick="applyForJob({{ $job->id }})" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Apply Now
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Login to Apply
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links() }}
        </div>
    @endif
</div>

<!-- Application Modal -->
<div id="applicationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Apply for Job</h3>
                <button onclick="closeApplicationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="applicationForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="cover_letter" class="block text-sm font-medium text-gray-700">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter" rows="4" required
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                  placeholder="Tell us why you're interested in this position..."></textarea>
                    </div>
                    
                    <div>
                        <label for="resume" class="block text-sm font-medium text-gray-700">Resume (PDF)</label>
                        <input type="file" id="resume" name="resume" accept=".pdf" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeApplicationModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function applyForJob(jobId) {
    document.getElementById('applicationForm').action = `/jobs/${jobId}/apply`;
    document.getElementById('applicationModal').classList.remove('hidden');
}

function closeApplicationModal() {
    document.getElementById('applicationModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplicationModal();
    }
});
</script>
@endsection