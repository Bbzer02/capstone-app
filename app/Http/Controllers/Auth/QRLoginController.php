<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\QRLoginToken;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginConfirmation;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class QRLoginController extends Controller
{
    /**
     * Generate QR code token for admin user
     * This endpoint can be called from login page (with email) or by authenticated admin
     */
    public function generateToken(Request $request)
    {
        $admin = null;
        
        // If authenticated, use current admin
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
        } else {
            // If not authenticated, require email to identify admin
            try {
                $request->validate([
                    'email' => 'required|email|exists:admin_users,email',
                ], [
                    'email.required' => 'Email address is required.',
                    'email.email' => 'Please enter a valid email address.',
                    'email.exists' => 'This email is not registered as an admin user.',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->validator->errors()->first('email') ?? 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }
            
            $admin = AdminUser::where('email', $request->email)->first();
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin user not found'
                ], 404);
            }
        }
        
        try {
            // Clean up expired tokens
            QRLoginToken::cleanupExpired();

            // Create new token
            $qrToken = QRLoginToken::createToken(
                $admin->id,
                $request->ip(),
                $request->userAgent()
            );

            // Generate QR code URL
            $qrUrl = route('admin.qr.scan', ['token' => $qrToken->token]);

            // Generate QR code image (base64)
            $qrCodeImage = $this->generateQRCodeImage($qrUrl);

            return response()->json([
                'success' => true,
                'token' => $qrToken->token,
                'qr_url' => $qrUrl,
                'qr_image' => $qrCodeImage, // Base64 encoded image
                'expires_at' => $qrToken->expires_at->toIso8601String(),
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('QR Token generation database error: ' . $e->getMessage());
            
            // Check if table doesn't exist
            if (str_contains($e->getMessage(), "doesn't exist") || str_contains($e->getMessage(), 'Base table or view not found')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database table not found. Please run migrations: php artisan migrate'
                ], 500);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred. Please contact administrator.'
            ], 500);
        } catch (\Exception $e) {
            Log::error('QR Token generation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Check token status (polling endpoint)
     */
    public function checkToken(Request $request, string $token)
    {
        $qrToken = QRLoginToken::where('token', $token)
            ->where('is_used', false)
            ->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'status' => 'invalid',
                'message' => 'Token not found or already used'
            ]);
        }

        if ($qrToken->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'status' => 'expired',
                'message' => 'Token has expired'
            ]);
        }

        if ($qrToken->is_used) {
            return response()->json([
                'success' => true,
                'status' => 'used',
                'message' => 'Token has been used'
            ]);
        }

        return response()->json([
            'success' => true,
            'status' => 'pending',
            'expires_at' => $qrToken->expires_at->toIso8601String(),
        ]);
    }

    /**
     * Verify and login with QR token
     * This endpoint is called when someone scans the QR code
     */
    public function verifyToken(Request $request, string $token)
    {
        $qrToken = QRLoginToken::where('token', $token)
            ->where('is_used', false)
            ->with('adminUser')
            ->first();

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token'
            ], 404);
        }

        if ($qrToken->expires_at->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Token has expired'
            ], 410);
        }

        // Mark token as used
        $qrToken->markAsUsed();

        // Log in the admin user
        $admin = $qrToken->adminUser;
        
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        // Update admin presence
        try {
            $admin->forceFill([
                'last_login_at' => now(),
                'is_online' => true,
            ])->save();
        } catch (\Exception $e) {
            Log::warning('Failed to update admin presence: ' . $e->getMessage());
        }

        // Send login confirmation email (send immediately, not queued)
        try {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            Log::info('Sending admin login confirmation email to: ' . $admin->email);
            Mail::to($admin->email)->send(new AdminLoginConfirmation($admin, $ipAddress, $userAgent, 'QR Code Token'));
            Log::info('Admin login confirmation email sent successfully to: ' . $admin->email);
        } catch (\Exception $e) {
            // Log error but don't fail login if email fails
            Log::error('Failed to send admin login confirmation email: ' . $e->getMessage());
            Log::error('Email error trace: ' . $e->getTraceAsString());
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect_url' => route('admin.dashboard'),
        ]);
    }

    /**
     * Show QR scan page (for mobile/device scanning)
     */
    public function showScanPage(Request $request, string $token)
    {
        $qrToken = QRLoginToken::where('token', $token)
            ->where('is_used', false)
            ->with('adminUser')
            ->first();

        if (!$qrToken || $qrToken->expires_at->isPast()) {
            return view('auth.qr_scan_error', [
                'message' => 'Invalid or expired QR code'
            ]);
        }

        // Generate QR code image for the scan page
        $qrUrl = route('admin.qr.scan', ['token' => $token]);
        $qrCodeImage = $this->generateQRCodeImage($qrUrl);

        return view('auth.qr_scan', [
            'token' => $token,
            'admin' => $qrToken->adminUser,
            'expires_at' => $qrToken->expires_at,
            'qr_image' => $qrCodeImage,
        ]);
    }

    /**
     * Generate QR code image as base64 string
     */
    private function generateQRCodeImage(string $data): string
    {
        try {
            // Try PNG first, fallback to SVG if GD extension is not available
            $usePng = extension_loaded('gd');
            
            if ($usePng) {
                $builder = new Builder(
                    writer: new PngWriter(),
                    data: $data,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 256,
                    margin: 2
                );
                
                $result = $builder->build();
                
                // Return as base64 data URI
                return 'data:image/png;base64,' . base64_encode($result->getString());
            } else {
                // Use SVG as fallback (doesn't require GD extension)
                $builder = new Builder(
                    writer: new SvgWriter(),
                    data: $data,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 256,
                    margin: 2
                );
                
                $result = $builder->build();
                
                // Return as base64 data URI for SVG
                return 'data:image/svg+xml;base64,' . base64_encode($result->getString());
            }
        } catch (\Exception $e) {
            Log::error('QR Code generation error: ' . $e->getMessage());
            // Return empty string on error, client will handle fallback
            return '';
        }
    }
}
