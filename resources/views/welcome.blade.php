@extends('layouts.app')

@section('title', 'CTU Danao HRMO - Job Portal')

@section('content')
<div class="ctu-gradient text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-8">
            <img src="{{ asset('images/ctu2.jpg') }}" alt="CTU Danao Logo" class="h-24 w-24 rounded-full mx-auto mb-6 shadow-lg">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">CTU Danao HRMO</h1>
            <p class="text-xl md:text-2xl text-blue-200 mb-8">Human Resource Management Office</p>
            <p class="text-lg text-blue-100 max-w-3xl mx-auto">
                Welcome to the Cebu Technological University - Danao Campus Job Portal. 
                Discover exciting career opportunities and join our academic community.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Go to Dashboard
                </a>
            @else
                <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Apply for Jobs
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Login
                </a>
            @endauth
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Why Work at CTU Danao?</h2>
            <p class="text-xl text-gray-600">Join our academic community and make a difference in education</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="ctu-bg-blue w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Academic Excellence</h3>
                <p class="text-gray-600">Be part of an institution committed to providing quality education and research opportunities.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="ctu-bg-blue w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Collaborative Environment</h3>
                <p class="text-gray-600">Work with dedicated professionals in a supportive and collaborative academic environment.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="ctu-bg-blue w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Career Growth</h3>
                <p class="text-gray-600">Access to professional development opportunities and career advancement within the university.</p>
            </div>
        </div>
    </div>
</div>

<!-- Job Openings Preview -->
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Current Job Openings</h2>
            <p class="text-xl text-gray-600">Explore available positions at CTU Danao Campus</p>
        </div>
        
        <div class="text-center">
            <a href="{{ route('jobs.index') }}" class="ctu-bg-blue text-white hover:bg-blue-700 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300 inline-block">
                View All Job Openings
            </a>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="ctu-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Join Our Team?</h2>
        <p class="text-xl text-blue-200 mb-8">Start your journey with CTU Danao today</p>
        
        @guest
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Create Account
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                    Sign In
                </a>
            </div>
        @endguest
    </div>
</div>
@endsection