@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    @if($user->profile_picture)
                        <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile Picture" 
                             class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-300 flex items-center justify-center border-4 border-white shadow-lg">
                            <svg class="w-10 h-10 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p class="mt-1 text-lg text-gray-900">{{ $user->address ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Birth Date</label>
                        <p class="mt-1 text-lg text-gray-900">
                            {{ $user->birth_date ? $user->birth_date->format('F j, Y') : 'Not provided' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-violet-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Professional Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">LinkedIn</label>
                        <p class="mt-1 text-lg text-gray-900">
                            @if($user->linkedin_url)
                                <a href="{{ $user->linkedin_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $user->linkedin_url }}
                                </a>
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Portfolio</label>
                        <p class="mt-1 text-lg text-gray-900">
                            @if($user->portfolio_url)
                                <a href="{{ $user->portfolio_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    {{ $user->portfolio_url }}
                                </a>
                            @else
                                Not provided
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bio -->
            @if($user->bio)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden lg:col-span-2">
                <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">About Me</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-900 leading-relaxed">{{ $user->bio }}</p>
                </div>
            </div>
            @endif

            <!-- Skills -->
            @if($user->skills)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-pink-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Skills</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $user->skills) as $skill)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ trim($skill) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Experience -->
            @if($user->experience)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Experience</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($user->experience)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Education -->
            @if($user->education)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-50 to-cyan-50 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Education</h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        {!! nl2br(e($user->education)) !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
