<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Mail\WelcomeOrganizationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function Register(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'company_email' => ['required', 'string', 'email', 'max:255', 'unique:accounts,company_email'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['accepted']
        ]);

        // Create the Account
        $account = Account::create([
            'company_name' => $data['company_name'],
            'company_email' => $data['company_email'],
            'subscription_plan' => 'basic',
            'status' => 'pending',
        ]);

        // Generate verification credentials
        $otp = (string) rand(100000, 999999);
        $token = Str::random(60);

        // Create the Admin User linked to the account
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ADMIN ?? 'admin',
            'account_id' => $account->id,
            'otp' => $otp,
            'verification_token' => $token,
        ]);

        // Send Welcome & Verification Email
        try {
            Mail::to($user->email)->send(new WelcomeOrganizationMail($user, $account));
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        return redirect()->route('verification.otp.form', ['email' => $user->email])
            ->with('success', 'Organization registered successfully! We have sent a 6-digit verification code and link to your admin email.');
    }

    public function showVerifyOtpForm(Request $request)
    {
        return view('auth.verify_otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return back()->with('error', 'Invalid or expired verification code. Please check your email and try again.');
        }

        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'verification_token' => null,
        ]);

        if ($user->account) {
            $user->account->update(['status' => 'active']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'Your organization account is now verified and active! Welcome to ' . config('app.name', 'EQTRAK') . '.');
    }

    public function verifyLink($token)
    {
        $user = User::where('verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired verification link.');
        }

        $user->update([
            'email_verified_at' => now(),
            'otp' => null,
            'verification_token' => null,
        ]);

        if ($user->account) {
            $user->account->update(['status' => 'active']);
        }

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Your organization account is now verified and active! Welcome to ' . config('app.name', 'EQTRAK') . '.');
    }

    public function LoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            if (is_null($user->email_verified_at) && !is_null($user->otp)) {
                Auth::logout();
                return redirect()->route('verification.otp.form', ['email' => $credentials['email']])
                    ->with('error', 'Please verify your organization email before logging in.');
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
