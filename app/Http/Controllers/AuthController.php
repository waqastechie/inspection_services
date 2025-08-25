<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Update last login
            Auth::user()->update(['last_login' => now()]);
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Admin dashboard with user management
            $totalUsers = User::count();
            $activeUsers = User::where('is_active', true)->count();
            $inspectors = User::where('role', 'inspector')->count();
            $recentUsers = User::latest()->take(5)->get();
            
            return view('dashboard', compact('totalUsers', 'activeUsers', 'inspectors', 'recentUsers'));
        } else {
            // Regular user dashboard
            return view('dashboard');
        }
    }
}
