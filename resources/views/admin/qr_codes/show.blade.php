@extends('layouts.admin')

@section('title', 'QR Code - ' . $user->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('admin.qr-codes.index') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
            ← Back to QR Codes List
        </a>
        <h1 class="text-3xl font-bold text-gray-900">QR Code for {{ $user->name }}</h1>
        <p class="text-gray-600 mt-2">Print this QR code for the employee's ID card</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="max-w-2xl mx-auto">
            <!-- User Info -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-semibold text-gray-900">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-sm text-gray-500 mt-2 font-mono">{{ $user->qr_code }}</p>
            </div>

            <!-- QR Code Display -->
            <div class="flex justify-center mb-6">
                <div class="bg-white p-6 rounded-lg border-2 border-gray-200 shadow-md">
                    <img src="{{ route('admin.qr-codes.download', $user) }}" 
                         alt="QR Code" 
                         class="w-64 h-64 mx-auto">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('admin.qr-codes.download', $user) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Download QR Code
                </a>
                <button onclick="window.print()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print QR Code
                </button>
            </div>

            <!-- Instructions -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h3 class="font-semibold text-blue-900 mb-2">Instructions:</h3>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>Download or print this QR code</li>
                    <li>Attach it to the employee's ID card</li>
                    <li>The employee can scan this QR code to login</li>
                    <li>QR Code value: <strong class="font-mono">{{ $user->qr_code }}</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none;
    }
}
</style>
@endsection

