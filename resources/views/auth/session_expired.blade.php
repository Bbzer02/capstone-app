<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700" rel="stylesheet" />
    <style>
        body{margin:0;display:flex;align-items:center;justify-content:center;min-height:100vh;background:linear-gradient(135deg,#1e3a8a 0%,#3b82f6 50%,#60a5fa 100%);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif}
        .card{background:white;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,.15);padding:32px;max-width:420px;width:92%}
        .spinner{width:56px;height:56px;border:6px solid #e5e7eb;border-top-color:#3b82f6;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 16px}
        @keyframes spin{to{transform:rotate(360deg)}}
        .title{font-size:20px;font-weight:700;color:#111827;text-align:center;margin-bottom:8px}
        .subtitle{font-size:14px;color:#4b5563;text-align:center;margin-bottom:16px}
        .badge{display:inline-flex;align-items:center;gap:6px;background:#f3f4f6;color:#374151;border-radius:999px;padding:6px 10px;font-size:12px;margin:0 auto 10px}
        .redirect{font-size:12px;color:#6b7280;text-align:center;margin-top:8px}
        .btn{display:inline-block;margin-top:16px;background:#3b82f6;color:#fff;padding:10px 14px;border-radius:10px;text-decoration:none;font-weight:600}
        .center{text-align:center}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var seconds = {{ $seconds }};
            var span = document.getElementById('countdown');
            function tick(){
                seconds--; if (seconds <= 0) { window.location.href = '{{ $loginUrl }}'; return; }
                span.textContent = seconds; setTimeout(tick, 1000);
            }
            setTimeout(tick, 1000);
        });
    </script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<body>
    <div class="card center">
        <div class="spinner"></div>
        <div class="badge">No more access</div>
        <div class="title">Your session has ended</div>
        <div class="subtitle">Please log in again to continue.</div>
        <div class="redirect">Redirecting in <span id="countdown">{{ $seconds }}</span> seconds…</div>
        <a href="{{ $loginUrl }}" class="btn">Go to Login</a>
    </div>
</body>
</html>


