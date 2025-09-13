<?php

use Illuminate\Support\Str;

return [
    'driver' => env('SESSION_DRIVER', 'database'),
    
    // Session duration (2 hours for admin panel is reasonable)
    'lifetime' => (int) env('SESSION_LIFETIME', 120),
    
    // Important for security - session should expire when browser closes
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', true),
    
    // Always encrypt session data for admin panels
    'encrypt' => env('SESSION_ENCRYPT', true),
    
    'files' => storage_path('framework/sessions'),
    
    // For database driver, specify connection
    'connection' => env('SESSION_CONNECTION', 'mysql'),
    
    // Custom sessions table if needed
    'table' => env('SESSION_TABLE', 'sessions'),
    
    // For cache-based drivers
    'store' => env('SESSION_STORE'),
    
    // Garbage collection probability
    'lottery' => [2, 100],
    
    // Cookie name with application prefix
    'cookie' => env(
        'SESSION_COOKIE',
        Str::slug(env('APP_NAME', 'laravel'), '_').'_admin_session' // Added admin prefix
    ),
    
    'path' => env('SESSION_PATH', '/'),
    
    // For cross-subdomain support if needed
    'domain' => env('SESSION_DOMAIN', null),
    
    // Force HTTPS in production
    'secure' => env('SESSION_SECURE_COOKIE', env('APP_ENV') === 'production'),
    
    // Prevent JavaScript access
    'http_only' => env('SESSION_HTTP_ONLY', true),
    
    // Balanced security for CSRF protection
    'same_site' => env('SESSION_SAME_SITE', 'lax'),
    
    // For advanced cookie partitioning
    'partitioned' => env('SESSION_PARTITIONED_COOKIE', false),
];