<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\AdminUser;
use App\Models\User;
use App\Mail\AdminLoginConfirmation;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Trim whitespace from email
        $email = trim($request->email);
        $password = $request->password;

        // Look for the admin in admin_users table
        $adminUser = AdminUser::where('email', $email)->first();

        if (!$adminUser) {
            \Log::warning('Admin login failed: User not found', ['email' => $email]);
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        }

        // Get the raw password hash from database (bypass casting)
        $passwordHash = $adminUser->getAttributes()['password'] ?? $adminUser->password;
        
        // Check if the password matches the stored hash
        if (!Hash::check($password, $passwordHash)) {
            \Log::warning('Admin login failed: Password mismatch', [
                'email' => $email,
                'password_length' => strlen($password)
            ]);
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput($request->only('email'));
        }

        // Log the admin in and record presence
        Auth::guard('admin')->login($adminUser, $request->boolean('remember'));
        
        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();
        
        // Update admin presence
        try {
            $adminUser->forceFill([
                'last_login_at' => now(),
                'is_online' => true,
            ])->save();
        } catch (\Exception $e) {
            // Log error but don't fail login if presence update fails
            \Log::warning('Failed to update admin presence: ' . $e->getMessage());
        }

        // Send login confirmation email (send immediately, not queued)
        try {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            \Log::info('Sending admin login confirmation email to: ' . $adminUser->email);
            Mail::to($adminUser->email)->send(new AdminLoginConfirmation($adminUser, $ipAddress, $userAgent, 'Email/Password'));
            \Log::info('Admin login confirmation email sent successfully to: ' . $adminUser->email);
        } catch (\Exception $e) {
            // Log error but don't fail login if email fails
            \Log::error('Failed to send admin login confirmation email: ' . $e->getMessage());
            \Log::error('Email error trace: ' . $e->getMessage());
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($admin) {
            $admin->forceFill([
                'last_logout_at' => now(),
                'is_online' => false,
            ])->save();
        }
        Auth::guard('admin')->logout(); // Log out the admin
        $request->session()->invalidate(); // Clear session
        $request->session()->regenerateToken(); // Security

        return redirect()->route('admin.login')
            ->withHeaders([
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
    }

    /**
     * Process scanned QR code for admin login
     */
    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_data' => 'required|string',
        ]);

        $qrData = trim($request->qr_data);

        // Find user by QR code
        $user = User::where('qr_code', $qrData)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code. Please check your ID card.'
            ], 404);
        }

        // Check if user is an admin
        $admin = AdminUser::where('email', $user->email)->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'This QR code does not belong to an admin user.'
            ], 403);
        }

        // Log in as admin
        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        // Update admin presence
        try {
            $admin->forceFill([
                'last_login_at' => now(),
                'is_online' => true,
            ])->save();
        } catch (\Exception $e) {
            \Log::warning('Failed to update admin presence: ' . $e->getMessage());
        }

        // Send login confirmation email (send immediately, not queued)
        try {
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            \Log::info('Sending admin login confirmation email to: ' . $admin->email);
            Mail::to($admin->email)->send(new AdminLoginConfirmation($admin, $ipAddress, $userAgent, 'QR Code'));
            \Log::info('Admin login confirmation email sent successfully to: ' . $admin->email);
        } catch (\Exception $e) {
            // Log error but don't fail login if email fails
            \Log::error('Failed to send admin login confirmation email: ' . $e->getMessage());
            \Log::error('Email error trace: ' . $e->getTraceAsString());
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin login successful!',
            'user' => [
                'name' => $admin->name,
                'email' => $admin->email,
            ],
            'redirect_url' => route('admin.dashboard'),
        ]);
    }

}
