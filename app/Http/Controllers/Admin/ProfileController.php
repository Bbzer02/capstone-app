<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        
        // Check if admin has a corresponding user with QR code
        $user = User::where('email', $admin->email)->first();
        
        // If user doesn't exist, create one for the admin
        if (!$user) {
            $user = User::create([
                'name' => $admin->name,
                'email' => $admin->email,
                'password' => bcrypt(str()->random(32)), // Random password since admin uses admin_users table
            ]);
        }
        
        // Generate QR code if user doesn't have one
        if (!$user->qr_code) {
            $hash = substr(md5($user->email . $user->id . config('app.key')), 0, 8);
            $user->qr_code = 'CTU-' . $user->id . '-' . strtoupper($hash);
            $user->save();
        }
        
        $hasQRCode = $user && $user->qr_code;
        
        return view('admin.profile.show', compact('admin', 'user', 'hasQRCode'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admin_users,email,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }
        $admin->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Download QR code for admin login
     */
    public function downloadQR()
    {
        $admin = Auth::guard('admin')->user();
        
        // Find user by email
        $user = User::where('email', $admin->email)->first();
        
        if (!$user) {
            return back()->with('error', 'No user account found with your email. Please contact administrator.');
        }
        
        if (!$user->qr_code) {
            return back()->with('error', 'QR code not generated yet. Please contact administrator to generate your QR code.');
        }

        try {
            // Try PNG first, fallback to SVG if GD extension is not available
            $usePng = extension_loaded('gd');
            
            if ($usePng) {
                $builder = new Builder(
                    writer: new PngWriter(),
                    data: $user->qr_code,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 400,
                    margin: 10
                );
                
                $result = $builder->build();
                
                return response($result->getString())
                    ->header('Content-Type', 'image/png')
                    ->header('Content-Disposition', 'attachment; filename="admin-qr-code-' . $admin->id . '.png"');
            } else {
                // Use SVG as fallback (doesn't require GD extension)
                $builder = new Builder(
                    writer: new SvgWriter(),
                    data: $user->qr_code,
                    encoding: new Encoding('UTF-8'),
                    errorCorrectionLevel: ErrorCorrectionLevel::High,
                    size: 400,
                    margin: 10
                );
                
                $result = $builder->build();
                
                return response($result->getString())
                    ->header('Content-Type', 'image/svg+xml')
                    ->header('Content-Disposition', 'attachment; filename="admin-qr-code-' . $admin->id . '.svg"');
            }
        } catch (\Exception $e) {
            \Log::error('QR Code generation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to generate QR code image: ' . $e->getMessage());
        }
    }
}


