<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\QRLoginConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class QRScannerController extends Controller
{
    /**
     * Show QR scanner page
     */
    public function index()
    {
        return view('qr_scanner.index');
    }

    /**
     * Process scanned QR code and login user
     */
    public function scan(Request $request)
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

        // Log in the user
        Auth::login($user);
        $request->session()->regenerate();

        // Send email confirmation
        try {
            Mail::to($user->email)->send(new QRLoginConfirmation($user, $request->ip(), $request->userAgent()));
        } catch (\Exception $e) {
            Log::warning('Failed to send QR login confirmation email: ' . $e->getMessage());
            // Don't fail login if email fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful! Email confirmation sent.',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'redirect_url' => route('dashboard'),
        ]);
    }
}
