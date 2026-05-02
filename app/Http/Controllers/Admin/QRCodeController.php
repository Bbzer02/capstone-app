<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Artisan;

class QRCodeController extends Controller
{
    /**
     * Show QR codes page with list of users
     */
    public function index()
    {
        $users = User::whereNotNull('qr_code')
            ->orderBy('name')
            ->paginate(20);
        
        return view('admin.qr_codes.index', compact('users'));
    }

    /**
     * Show QR code for a specific user
     */
    public function show(User $user)
    {
        if (!$user->qr_code) {
            return redirect()->route('admin.qr-codes.index')
                ->with('error', 'User does not have a QR code. Please generate one first.');
        }

        return view('admin.qr_codes.show', compact('user'));
    }

    /**
     * Generate QR code image for download/printing
     */
    public function download(User $user)
    {
        if (!$user->qr_code) {
            abort(404, 'User does not have a QR code');
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
                    ->header('Content-Disposition', 'attachment; filename="qr-code-' . $user->id . '.png"');
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
                    ->header('Content-Disposition', 'attachment; filename="qr-code-' . $user->id . '.svg"');
            }
        } catch (\Exception $e) {
            abort(500, 'Failed to generate QR code image');
        }
    }

    /**
     * Generate QR code for a user
     */
    public function generate(User $user)
    {
        // Generate unique QR code
        $hash = substr(md5($user->email . $user->id . config('app.key')), 0, 8);
        $qrCode = 'CTU-' . $user->id . '-' . strtoupper($hash);
        
        $user->qr_code = $qrCode;
        $user->save();

        return redirect()->route('admin.qr-codes.show', $user)
            ->with('success', 'QR code generated successfully!');
    }

    /**
     * Generate QR codes for all users
     */
    public function generateAll()
    {
        Artisan::call('user:generate-qr', ['--all' => true]);
        
        return redirect()->route('admin.qr-codes.index')
            ->with('success', 'QR codes generated for all users!');
    }
}
