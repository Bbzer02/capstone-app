@extends('layouts.app')

@section('title', $job->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Job Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                <div class="flex items-center space-x-4 text-gray-600 mb-4">
                    <span class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        {{ $job->department }}
                    </span>
                    <span class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $job->position_type }}
                    </span>
                    <span class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        {{ $job->formattedSalary() }}
                    </span>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Apply by {{ $job->application_deadline->format('M d, Y') }}
                </div>
            </div>
            <div class="ml-6">
                @auth
                    @php
                        $hasApplied = \App\Models\Applicant::where('email', auth()->user()->email)
                            ->where('job_id', $job->id)
                            ->exists();
                    @endphp
                    @if($hasApplied)
                        <span class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-800 bg-green-100">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Applied
                        </span>
                    @else
                        <button onclick="applyForJob({{ $job->id }})" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Apply Now
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login to Apply
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Job Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Job Description</h2>
                <div class="prose max-w-none">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>
        </div>

        <!-- Job Info Sidebar -->
        <div class="space-y-6">
            <!-- Job Details Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Department:</span>
                        <span class="font-medium">{{ $job->department }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Position Type:</span>
                        <span class="font-medium">{{ $job->position_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Salary:</span>
                        <span class="font-medium">{{ $job->formattedSalary() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Application Deadline:</span>
                        <span class="font-medium">{{ $job->application_deadline->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Application Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ready to Apply?</h3>
                <p class="text-gray-600 mb-4">Submit your application and take the next step in your career.</p>
                @auth
                    @if(!$hasApplied)
                        <button onclick="applyForJob({{ $job->id }})" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply for this position
                        </button>
                    @else
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <p class="text-green-600 font-medium">You've already applied for this job</p>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Login to Apply
                    </a>
                @endauth
            </div>

            <!-- Share Job -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Share this job</h3>
                <div class="flex space-x-2">
                    <button onclick="shareJob('twitter')" 
                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </button>
                    <button onclick="shareJob('linkedin')" 
                            class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                        LinkedIn
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Modal -->
<div id="applicationModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-8 mx-auto max-w-5xl w-full px-4 py-6">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            <div class="flex items-start justify-between px-6 py-5 border-b border-gray-100">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-blue-500 font-semibold mb-2">Application Wizard</p>
                    <h3 class="text-2xl font-bold text-gray-900">Apply for {{ $job->title }}</h3>
                    <p class="text-sm text-gray-500 mt-2">Complete the required information then attach any optional supporting documents. Optional uploads help explain your experience.</p>
                </div>
                <button onclick="closeApplicationModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <p class="text-sm font-semibold text-blue-900 mb-2">What you must provide</p>
                        <ul class="space-y-2 text-sm text-blue-900">
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <span><strong>Cover letter</strong> – share a short story about your experience and motivation.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                <span><strong>Resume / CV</strong> – upload a PDF or Word file (max 5MB).</span>
                            </li>
                        </ul>
                    </div>

                    <form id="applicationForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="cover_letter" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span>Cover Letter</span>
                                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Required</span>
                                </label>
                                <textarea id="cover_letter" name="cover_letter" rows="4" required
                                          class="mt-2 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Explain why you’re a strong fit for this position..."></textarea>
                                <p class="text-xs text-gray-500 mt-1">Tip: highlight accomplishments that match the role.</p>
                            </div>
                            
                            <div>
                                <label for="resume" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span>Resume / CV</span>
                                    <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Required</span>
                                </label>
                                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required
                                       class="mt-2 block w-full px-4 py-2.5 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX · Max size: 5 MB</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Optional supporting files</p>
                                    <p class="text-xs text-gray-500">Attach any file that can strengthen your application.</p>
                                </div>
                                <span class="text-xs font-semibold text-gray-500">Optional</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="cover_letter_file" class="text-sm font-medium text-gray-700">Cover Letter File</label>
                                    <input type="file" id="cover_letter_file" name="cover_letter_file" accept=".pdf,.doc,.docx"
                                           class="mt-2 block w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Upload a polished version of your letter.</p>
                                </div>
                                <div>
                                    <label for="transcript" class="text-sm font-medium text-gray-700">Transcript</label>
                                    <input type="file" id="transcript" name="transcript" accept=".pdf,.jpg,.jpeg,.png"
                                           class="mt-2 block w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Official or unofficial academic records.</p>
                                </div>
                                <div>
                                    <label for="certificate" class="text-sm font-medium text-gray-700">Certificate</label>
                                    <input type="file" id="certificate" name="certificate" accept=".pdf,.jpg,.jpeg,.png"
                                           class="mt-2 block w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Professional licenses or trainings.</p>
                                </div>
                                <div>
                                    <label for="portfolio" class="text-sm font-medium text-gray-700">Portfolio</label>
                                    <input type="file" id="portfolio" name="portfolio" accept=".pdf,.zip"
                                           class="mt-2 block w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <p class="text-xs text-gray-500 mt-1">Design samples, writing clips, or project bundle.</p>
                                </div>
                            </div>

                            <div>
                                <label for="additional_documents" class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                                    <span>Additional Documents</span>
                                    <span class="text-xs text-gray-500 font-normal">Upload multiple files at once</span>
                                </label>
                                <input type="file" id="additional_documents" name="additional_documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.zip"
                                       class="mt-2 block w-full px-3 py-2 border border-gray-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <p class="text-xs text-gray-500 mt-2">Examples: IDs, recommendation letters, lesson plans, training summaries.</p>

                                <div id="additionalDocumentsPreview" class="mt-3 space-y-2 hidden">
                                    <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Selected files</p>
                                    <ul class="text-sm text-gray-700 space-y-1"></ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="h-5 w-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-600">By submitting, you confirm the details are accurate and consent to storing your documents for hiring purposes.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                                <button type="button" onclick="closeApplicationModal()" 
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 shadow-sm">
                                    Submit Application
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-800 mb-3">What each optional document means</h4>
                        <ul class="space-y-3 text-sm text-gray-600">
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span><strong>Transcript:</strong> showcases academic standing or units completed.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span><strong>Certificates:</strong> highlight seminars, licensure exams, TESDA trainings, etc.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span><strong>Portfolio:</strong> perfect for creatives, researchers, or roles that require samples.</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <span class="mt-1 h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                <span><strong>Additional documents:</strong> recommendation letters, government IDs, COE, or any file that adds context.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <p class="text-sm font-semibold text-blue-900 mb-1">Need help?</p>
                        <p class="text-sm text-blue-800">Email <a href="mailto:hrmo@ctu.edu.ph" class="text-blue-600 underline">hrmo@ctu.edu.ph</a> if you encounter upload issues. We’ll gladly assist.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function applyForJob(jobId) {
    document.getElementById('applicationForm').action = `/jobs/${jobId}/apply`;
    document.getElementById('applicationModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeApplicationModal() {
    document.getElementById('applicationModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function shareJob(platform) {
    const url = window.location.href;
    const title = '{{ $job->title }}';
    
    if (platform === 'twitter') {
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, '_blank');
    } else if (platform === 'linkedin') {
        window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, '_blank');
    }
}

// Close modal when clicking outside
document.getElementById('applicationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplicationModal();
    }
});

// Show selected additional documents
const additionalInput = document.getElementById('additional_documents');
const previewWrapper = document.getElementById('additionalDocumentsPreview');
if (additionalInput) {
    additionalInput.addEventListener('change', function() {
        const list = previewWrapper.querySelector('ul');
        list.innerHTML = '';

        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                const li = document.createElement('li');
                li.className = 'flex items-center justify-between bg-white border border-gray-200 rounded-lg px-3 py-2';
                li.innerHTML = `
                    <span class="text-sm text-gray-700 truncate pr-2">${file.name}</span>
                    <span class="text-xs text-gray-500">${(file.size / 1024).toFixed(1)} KB</span>
                `;
                list.appendChild(li);
            });
            previewWrapper.classList.remove('hidden');
        } else {
            previewWrapper.classList.add('hidden');
        }
    });
}
</script>
@endsection
