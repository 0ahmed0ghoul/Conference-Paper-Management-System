<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm(){return view('auth.login');}
    public function showRegisterForm(){return view('auth.signup');}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:author,chair,reviewer'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role !== $request->role) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Selected role does not match your account role.'
                ])->withInput();
            }

            if ($user->role !== 'author' && $user->status !== 'approved') {
                return redirect()->route('waiting.page');
            }

            // Simple check: If chair email is superchair@example.com, redirect to superchair dashboard
            if ($user->role === 'chair' && $user->email === 'superchair@example.com') {
                return redirect()->route('chair.index');
            }

            if ($user->role === 'author') {
                return redirect()->route('author.dashboard');
            } elseif ($user->role === 'reviewer') {
                return redirect()->route('reviewer.dashboard');
            } elseif ($user->role === 'chair') {
                return redirect()->route('chair.index');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:author,reviewer,chair',
        ]);

        $status = $request->role === 'author' ? 'approved' : 'pending'; // Authors auto-approved

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $status,
        ]);

        Auth::login($user);

        if ($user->status === 'pending') {
            return redirect()->route('waiting.page');
        }

        // approved users
        if ($user->role === 'author') {
            return redirect()->route('author.dashboard');
        } elseif ($user->role === 'reviewer') {
            return redirect()->route('reviewer.dashboard');
        } elseif ($user->role === 'chair') {
            return redirect()->route('chair.dashboard');
        }

        Auth::logout();
        return redirect()->route('login')->withErrors(['role' => 'Unknown user role.']);
    }
}
