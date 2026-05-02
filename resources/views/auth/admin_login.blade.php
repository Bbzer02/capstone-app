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
        
        <!-- Login Tabs -->
        <div class="bg-white py-8 px-6 shadow-lg rounded-lg" x-data="{ activeTab: 'password' }">
            <div class="flex border-b border-gray-200 mb-6">
                <button @click="activeTab = 'password'" 
                        :class="activeTab === 'password' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2 px-4 text-center font-medium transition-colors">
                    Password Login
                </button>
                <button @click="activeTab = 'qr'; setTimeout(() => { if(typeof initAdminScanner === 'function' && !window.isScanning) { initAdminScanner(); } }, 400)" 
                        :class="activeTab === 'qr' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2 px-4 text-center font-medium transition-colors">
                    QR Code Login
                </button>
            </div>

            <!-- Password Login Form -->
            <div x-show="activeTab === 'password'">
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
            </div>

            <!-- QR Code Login - Camera Scanner -->
            <div x-show="activeTab === 'qr'"
                 x-init="
                    $watch('activeTab', value => {
                        if (value === 'qr') {
                            setTimeout(() => {
                                if (typeof initAdminScanner === 'function' && !window.isScanning) {
                                    console.log('Tab switched to QR, initializing scanner...');
                                    initAdminScanner();
                                }
                            }, 300);
                        }
                    });
                 ">
                <div class="space-y-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600 mb-4">
                            Scan your ID card QR code to login as admin
                        </p>
                        
                        <!-- Small Scanner Container -->
                        <div class="flex justify-center">
                            <div id="scanner-container" class="relative bg-gray-900 rounded-lg overflow-hidden border-2 border-gray-300 shadow-lg" style="width: 280px; height: 280px; min-height: 280px;">
                                <!-- Loading/Placeholder -->
                                <div id="scanner-placeholder" class="absolute inset-0 flex items-center justify-center bg-gray-900 text-white z-20">
                                    <div class="text-center">
                                        <svg class="animate-spin h-8 w-8 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-sm">Starting camera...</p>
                                    </div>
                                </div>
                                
                                <!-- Scanner Overlay (Telegram-style) -->
                                <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none z-10" style="display: none;">
                                    <div class="relative w-full h-full">
                                        <!-- Scanning Line Animation -->
                                        <div id="scanner-line" class="absolute left-0 right-0 h-1 bg-blue-500 opacity-80" style="top: 0%; animation: scanLine 2s linear infinite;"></div>
                                        
                                        <!-- Corner Indicators -->
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                            <div class="w-48 h-48 border-2 border-blue-500 rounded-lg relative">
                                                <div class="absolute top-0 left-0 w-6 h-6 border-t-3 border-l-3 border-blue-500"></div>
                                                <div class="absolute top-0 right-0 w-6 h-6 border-t-3 border-r-3 border-blue-500"></div>
                                                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-3 border-l-3 border-blue-500"></div>
                                                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-3 border-r-3 border-blue-500"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Messages -->
                        <div id="qr-status" class="mt-3 text-center text-sm"></div>
                        
                        <!-- Manual Input Option -->
                        <div class="mt-3 text-center">
                            <button onclick="toggleManualInput()" class="text-blue-600 hover:text-blue-700 text-xs">
                                Or enter QR code manually
                            </button>
                            <div id="manual-input" class="hidden mt-3">
                                <input type="text" 
                                       id="manual-qr-input" 
                                       placeholder="Enter QR code from ID card"
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button onclick="submitManualQR()" 
                                        class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
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

<style>
    @keyframes scanLine {
        0% {
            top: 0%;
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
        100% {
            top: 100%;
            opacity: 1;
        }
    }
</style>

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let adminScanner = null;
let isScanning = false;
window.isScanning = false; // Make it globally accessible

// Wait for library to load
window.addEventListener('load', function() {
    if (typeof Html5Qrcode !== 'undefined') {
        console.log('Html5Qrcode library loaded');
    } else {
        console.error('Html5Qrcode library failed to load');
    }
});

// Initialize admin scanner
async function initAdminScanner() {
    if (isScanning) {
        console.log('Scanner already running');
        return;
    }
    
    // Check if library is loaded
    if (typeof Html5Qrcode === 'undefined') {
        console.error('Html5Qrcode library not loaded');
        updateAdminStatus('QR scanner library not loaded. Please refresh the page.', 'error');
        document.getElementById('scanner-placeholder').innerHTML = '<div class="text-center text-white p-4"><p>Library not loaded</p></div>';
        return;
    }
    
    const container = document.getElementById('scanner-container');
    if (!container) {
        console.error('Scanner container not found');
        updateAdminStatus('Scanner container not found.', 'error');
        return;
    }
    
    // Show placeholder
    const placeholder = document.getElementById('scanner-placeholder');
    const overlay = document.getElementById('scanner-overlay');
    
    try {
        updateAdminStatus('Starting camera...', 'info');
        
        adminScanner = new Html5Qrcode("scanner-container");
        
        const config = {
            fps: 10,
            qrbox: { width: 200, height: 200 },
            aspectRatio: 1.0,
        };
        
        // Start the camera
        await adminScanner.start(
            { facingMode: "environment" }, // Use back camera
            config,
            onAdminScanSuccess,
            onAdminScanError
        );
        
        // Hide placeholder and show overlay when camera starts
        if (placeholder) {
            placeholder.style.display = 'none';
        }
        if (overlay) {
            overlay.style.display = 'flex';
        }
        
        isScanning = true;
        window.isScanning = true;
        updateAdminStatus('Camera active. Position QR code within the frame.', 'info');
    } catch (err) {
        console.error('Error starting scanner:', err);
        isScanning = false;
        window.isScanning = false;
        
        // Show error in placeholder
        if (placeholder) {
            placeholder.innerHTML = `
                <div class="text-center text-white p-4">
                    <svg class="h-12 w-12 mx-auto mb-2 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p class="text-sm">Camera Error</p>
                    <p class="text-xs mt-2">${err.message || 'Please allow camera access'}</p>
                </div>
            `;
        }
        
        let errorMsg = 'Failed to access camera. ';
        if (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError') {
            errorMsg += 'Please allow camera permissions and refresh.';
        } else if (err.name === 'NotFoundError' || err.name === 'DevicesNotFoundError') {
            errorMsg += 'No camera found on this device.';
        } else {
            errorMsg += 'Please check permissions or use manual input.';
        }
        updateAdminStatus(errorMsg, 'error');
    }
}

function onAdminScanSuccess(decodedText, decodedResult) {
    if (isScanning) {
        isScanning = false;
        adminScanner.stop().then(() => {
            processAdminQRCode(decodedText);
        }).catch(err => {
            console.error('Error stopping scanner:', err);
            processAdminQRCode(decodedText);
        });
    }
}

function onAdminScanError(errorMessage) {
    // Ignore scanning errors, just keep scanning
}

async function processAdminQRCode(qrData) {
    updateAdminStatus('Processing QR code...', 'info');
    
    try {
        const response = await fetch('{{ route("admin.qr.scan-admin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                qr_data: qrData
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateAdminStatus('✓ Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1500);
        } else {
            updateAdminStatus(data.message || 'Invalid QR code or not an admin. Please try again.', 'error');
            // Restart scanner after 2 seconds
            setTimeout(() => {
                if (!isScanning) {
                    initAdminScanner();
                }
            }, 2000);
        }
    } catch (error) {
        console.error('Error:', error);
        updateAdminStatus('An error occurred. Please try again.', 'error');
        setTimeout(() => {
            if (!isScanning) {
                initAdminScanner();
            }
        }, 2000);
    }
}

function updateAdminStatus(message, type) {
    const statusDiv = document.getElementById('qr-status');
    const colors = {
        info: 'text-blue-600',
        success: 'text-green-600',
        error: 'text-red-600'
    };
    statusDiv.innerHTML = `<p class="${colors[type] || 'text-gray-600'} font-medium">${message}</p>`;
}

function toggleManualInput() {
    const manualInput = document.getElementById('manual-input');
    manualInput.classList.toggle('hidden');
}

function submitManualQR() {
    const qrInput = document.getElementById('manual-qr-input');
    const qrData = qrInput.value.trim();
    
    if (!qrData) {
        updateAdminStatus('Please enter a QR code', 'error');
        return;
    }
    
    if (adminScanner && isScanning) {
        adminScanner.stop();
        isScanning = false;
    }
    
    processAdminQRCode(qrData);
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (adminScanner && isScanning) {
        adminScanner.stop().catch(err => console.error('Error stopping scanner:', err));
    }
});
</script>
@endsection