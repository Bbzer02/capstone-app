@extends('layouts.app')

@section('title', 'QR Code Scanner - CTU Danao HRMO')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-6">
                <img src="{{ asset('images/ctu2.jpg') }}" alt="CTU Danao Logo" class="h-16 w-16 rounded-full mx-auto mb-4 shadow-lg">
                <h1 class="text-3xl font-bold ctu-blue">CTU Danao HRMO</h1>
                <p class="text-gray-600 mt-2">Scan your ID card QR code to login</p>
            </div>

            <!-- Scanner Container -->
            <div class="relative">
                <div id="scanner-container" class="relative bg-black rounded-lg overflow-hidden" style="width: 100%; max-width: 500px; margin: 0 auto;">
                    <video id="video" autoplay playsinline style="width: 100%; display: block;"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    
                    <!-- Scanner Overlay (Telegram-style) -->
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <div class="relative w-full h-full">
                            <!-- Scanning Line Animation -->
                            <div id="scanner-line" class="absolute left-0 right-0 h-1 bg-blue-500 opacity-80" style="top: 0%; animation: scanLine 2s linear infinite;"></div>
                            
                            <!-- Corner Indicators -->
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <div class="w-64 h-64 border-2 border-blue-500 rounded-lg relative">
                                    <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500"></div>
                                    <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500"></div>
                                    <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500"></div>
                                    <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Status Messages -->
                <div id="status-message" class="mt-4 text-center"></div>
                
                <!-- Manual Input Option -->
                <div class="mt-6 text-center">
                    <button onclick="toggleManualInput()" class="text-blue-600 hover:text-blue-700 text-sm">
                        Or enter QR code manually
                    </button>
                    <div id="manual-input" class="hidden mt-4">
                        <input type="text" 
                               id="manual-qr-input" 
                               placeholder="Enter QR code from ID card"
                               class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button onclick="submitManualQR()" 
                                class="mt-2 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

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
    
    #scanner-container {
        min-height: 400px;
    }
    
    #video {
        object-fit: cover;
    }
</style>

<script>
let html5QrcodeScanner = null;
let isScanning = false;

// Initialize scanner
async function initScanner() {
    try {
        html5QrcodeScanner = new Html5Qrcode("scanner-container");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0,
        };
        
        await html5QrcodeScanner.start(
            { facingMode: "environment" }, // Use back camera
            config,
            onScanSuccess,
            onScanError
        );
        
        isScanning = true;
        updateStatus('Camera started. Point at your ID card QR code.', 'info');
    } catch (err) {
        console.error('Error starting scanner:', err);
        updateStatus('Failed to access camera. Please check permissions or use manual input.', 'error');
    }
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) {
        isScanning = false;
        html5QrcodeScanner.stop().then(() => {
            processQRCode(decodedText);
        }).catch(err => {
            console.error('Error stopping scanner:', err);
            processQRCode(decodedText);
        });
    }
}

function onScanError(errorMessage) {
    // Ignore scanning errors, just keep scanning
}

async function processQRCode(qrData) {
    updateStatus('Processing QR code...', 'info');
    
    try {
        const response = await fetch('{{ route("qr.scan") }}', {
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
            updateStatus('✓ Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url;
            }, 1500);
        } else {
            updateStatus(data.message || 'Invalid QR code. Please try again.', 'error');
            // Restart scanner after 2 seconds
            setTimeout(() => {
                if (!isScanning) {
                    initScanner();
                }
            }, 2000);
        }
    } catch (error) {
        console.error('Error:', error);
        updateStatus('An error occurred. Please try again.', 'error');
        setTimeout(() => {
            if (!isScanning) {
                initScanner();
            }
        }, 2000);
    }
}

function updateStatus(message, type) {
    const statusDiv = document.getElementById('status-message');
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
        updateStatus('Please enter a QR code', 'error');
        return;
    }
    
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.stop();
        isScanning = false;
    }
    
    processQRCode(qrData);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    initScanner();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.stop().catch(err => console.error('Error stopping scanner:', err));
    }
});
</script>
@endsection

