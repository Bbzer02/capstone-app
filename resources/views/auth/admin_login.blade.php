@extends('layouts.app')

@section('title', 'CTU Danao HRMO - Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <img src="{{ asset('images/ctu2.jpg') }}" alt="CTU Danao Logo" class="h-20 w-20 rounded-full mx-auto mb-4 shadow-lg">
            <h2 class="text-3xl font-bold ctu-blue">CTU Danao HRMO</h2>
            <p class="text-gray-600 mt-2">Human Resource Management Office</p>
            <p class="text-2xl font-semibold text-gray-900 mt-4">Admin Login</p>
        </div>
        
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" type="email" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" 
                           name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@ctu.edu.ph">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" 
                           name="password" required
                           placeholder="Enter your password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white ctu-bg-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                        Sign in to Admin Panel
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('welcome') }}" class="ctu-blue hover:text-blue-700 font-medium">
                        ← Back to Home
                    </a>
                </p>
            </div>
        </div>
        
        <div class="text-center">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} CTU Danao HRMO. All rights reserved.
            </p>
        </div>
    </div>
</div>
@endsection