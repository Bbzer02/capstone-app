<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR Code - Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Admin Login</h1>
                <p class="text-gray-600">Scan this QR code to login</p>
                <p class="text-sm text-gray-500 mt-2">Login as: <strong>{{ $admin->name }}</strong></p>
            </div>

            <div class="relative bg-white p-4 rounded-lg border-2 border-gray-200 mb-4" id="qr-display">
                <div id="qrcode" class="mx-auto"></div>
                
                <!-- Telegram-style Scanner Overlay -->
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="relative w-full h-full">
                        <!-- Scanning Line Animation -->
                        <div class="absolute left-0 right-0 h-1 bg-blue-500 opacity-80" style="top: 0%; animation: scanLine 2s linear infinite;"></div>
                        
                        <!-- Corner Indicators -->
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500"></div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-500 mb-4" id="expiry-info"></p>
                <button onclick="verifyLogin()" 
                        id="verify-btn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Verify & Login
                </button>
                <p class="text-xs text-gray-400 mt-4" id="status-message"></p>
            </div>
        </div>
    </div>

    <script>
        const token = '{{ $token }}';
        const verifyUrl = '{{ route("admin.qr.verify", ["token" => ":token"]) }}'.replace(':token', token);
        
        // Use server-generated QR code image
        const qrContainer = document.getElementById('qrcode');
        @if(isset($qr_image) && !empty($qr_image))
            // Server-generated QR code (base64)
            const img = document.createElement('img');
            img.src = '{{ $qr_image }}';
            img.alt = 'QR Code';
            img.className = 'mx-auto block';
            img.style.width = '256px';
            img.style.height = '256px';
            img.onload = function() {
                console.log('QR Code displayed successfully');
            };
            qrContainer.appendChild(img);
        @else
            // Fallback to API if server generation failed
            const qrUrl = '{{ route("admin.qr.scan", ["token" => $token]) }}';
            const apiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=256x256&margin=2&data=' + encodeURIComponent(qrUrl);
            const img = document.createElement('img');
            img.src = apiUrl;
            img.alt = 'QR Code';
            img.className = 'mx-auto block';
            img.style.width = '256px';
            img.style.height = '256px';
            img.onerror = function() {
                // Fallback to alternative API
                const altApiUrl = 'https://chart.googleapis.com/chart?chs=256x256&cht=qr&chl=' + encodeURIComponent(qrUrl);
                img.src = altApiUrl;
            };
            qrContainer.appendChild(img);
        @endif

        // Expiry countdown
        const expiresAt = new Date('{{ $expires_at }}');
        function updateExpiry() {
            const now = new Date();
            const diff = expiresAt - now;
            
            if (diff <= 0) {
                document.getElementById('expiry-info').textContent = 'QR code expired';
                document.getElementById('verify-btn').disabled = true;
                document.getElementById('verify-btn').classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }
            
            const minutes = Math.floor(diff / 60000);
            const seconds = Math.floor((diff % 60000) / 1000);
            document.getElementById('expiry-info').textContent = `Expires in: ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        updateExpiry();
        setInterval(updateExpiry, 1000);

        async function verifyLogin() {
            const btn = document.getElementById('verify-btn');
            const statusMsg = document.getElementById('status-message');
            
            btn.disabled = true;
            btn.textContent = 'Verifying...';
            statusMsg.textContent = '';
            
            try {
                const response = await fetch(verifyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    statusMsg.textContent = '✓ Login successful! Redirecting...';
                    statusMsg.classList.remove('text-gray-400');
                    statusMsg.classList.add('text-green-600');
                    
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1000);
                } else {
                    statusMsg.textContent = data.message || 'Verification failed. Please try again.';
                    statusMsg.classList.remove('text-gray-400');
                    statusMsg.classList.add('text-red-600');
                    btn.disabled = false;
                    btn.textContent = 'Verify & Login';
                }
            } catch (error) {
                console.error('Error:', error);
                statusMsg.textContent = 'An error occurred. Please try again.';
                statusMsg.classList.remove('text-gray-400');
                statusMsg.classList.add('text-red-600');
                btn.disabled = false;
                btn.textContent = 'Verify & Login';
            }
        }
    </script>
</body>
</html>

