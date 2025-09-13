<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser; // Make sure this matches your model name

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

        // Look for the admin in admin_users table
        $adminUser = AdminUser::where('email', $request->email)->first();

        if (!$adminUser) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Check if the password matches the stored hash
        if (!Hash::check($request->password, $adminUser->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Log the admin in
        Auth::guard('admin')->login($adminUser);

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
{
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

}
