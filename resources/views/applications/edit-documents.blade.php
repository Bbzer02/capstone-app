@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Update Submitted Documents</h1>
                <p class="text-gray-600 mt-1">{{ $application->job->title }}</p>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Documents</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">Resume</div>
                            <div class="flex items-center space-x-3">
                                @if($application->resume_path)
                                    <a href="{{ route('documents.resume', $application) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                    <form method="POST" action="{{ route('applications.documents.delete', [$application, 'resume']) }}" onsubmit="return confirm('Delete resume?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">Cover Letter</div>
                            <div class="flex items-center space-x-3">
                                @if($application->cover_letter_path)
                                    <a href="{{ route('documents.cover-letter', $application) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                    <form method="POST" action="{{ route('applications.documents.delete', [$application, 'cover_letter']) }}" onsubmit="return confirm('Delete cover letter?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">Transcript</div>
                            <div class="flex items-center space-x-3">
                                @if($application->transcript_path)
                                    <a href="{{ route('documents.transcript', $application) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                    <form method="POST" action="{{ route('applications.documents.delete', [$application, 'transcript']) }}" onsubmit="return confirm('Delete transcript?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">Certificate</div>
                            <div class="flex items-center space-x-3">
                                @if($application->certificate_path)
                                    <a href="{{ route('documents.certificate', $application) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                    <form method="POST" action="{{ route('applications.documents.delete', [$application, 'certificate']) }}" onsubmit="return confirm('Delete certificate?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900">Portfolio</div>
                            <div class="flex items-center space-x-3">
                                @if($application->portfolio_path)
                                    <a href="{{ route('documents.portfolio', $application) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                    <form method="POST" action="{{ route('applications.documents.delete', [$application, 'portfolio']) }}" onsubmit="return confirm('Delete portfolio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                    </form>
                                @else
                                    <span class="text-sm text-gray-500">None</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="text-sm font-medium text-gray-900 mb-2">Additional Documents</div>
                            @if(!empty($application->additional_documents))
                                <div class="space-y-2">
                                    @foreach($application->additional_documents as $i => $doc)
                                        <div class="flex items-center justify-between">
                                            <div class="text-sm text-gray-900 truncate">{{ $doc['name'] ?? 'Additional Document' }}</div>
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('documents.additional', ['applicant' => $application, 'index' => $i]) }}" class="text-green-600 hover:text-green-800 text-sm">Download</a>
                                                <form method="POST" action="{{ route('applications.documents.additional.delete', [$application, $i]) }}" onsubmit="return confirm('Delete this document?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-500">None</div>
                            @endif
                        </div>
                    </div>

                <form method="POST" action="{{ route('applications.update-documents', $application) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Resume (PDF/DOC, max 5MB)</label>
                        <input type="file" name="resume" accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cover Letter (PDF/DOC, max 5MB)</label>
                        <input type="file" name="cover_letter_file" accept=".pdf,.doc,.docx" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Transcript (PDF/Image, max 5MB)</label>
                            <input type="file" name="transcript" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certificate (PDF/Image, max 5MB)</label>
                            <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Portfolio (PDF/ZIP, max 20MB)</label>
                        <input type="file" name="portfolio" accept=".pdf,.zip" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Additional Documents (multiple allowed)</label>
                        <input type="file" name="additional_documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.zip" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                        <p class="mt-1 text-xs text-gray-500">You can upload additional supporting documents.</p>
                    </div>

                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('applications.show', $application) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


