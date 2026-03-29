<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SecurityResetController extends Controller
{
    /**
     * Show the forgot password form (email entry).
     */
    public function showRequest()
    {
        return view('auth.forgot-password-security');
    }

    /**
     * Handle the email submission and redirect to challenge.
     */
    public function handleRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email address.']);
        }

        if (empty($user->security_question)) {
            return back()->withErrors(['email' => 'This account does not have a security question set. Please contact an administrator.']);
        }

        session(['reset_user_id' => $user->id]);

        return redirect()->route('password.security.challenge');
    }

    /**
     * Show the security question challenge.
     */
    public function showChallenge()
    {
        $userId = session('reset_user_id');
        if (!$userId) return redirect()->route('password.request');

        $user = User::findOrFail($userId);

        return view('auth.security-challenge', compact('user'));
    }

    /**
     * Verify the security answer.
     */
    public function verifyAnswer(Request $request)
    {
        $userId = session('reset_user_id');
        if (!$userId) return redirect()->route('password.request');

        $user = User::findOrFail($userId);

        $request->validate(['answer' => 'required|string']);

        // Case-insensitive comparison
        if (strtolower(trim($request->answer)) !== strtolower(trim($user->security_answer))) {
            return back()->withErrors(['answer' => 'Incorrect answer. Please try again.']);
        }

        session(['security_passed' => true]);

        return redirect()->route('password.security.reset');
    }

    /**
     * Show the reset password form.
     */
    public function showReset()
    {
        if (!session('security_passed')) return redirect()->route('password.request');

        return view('auth.reset-password-security');
    }

    /**
     * Handle the password reset.
     */
    public function handleReset(Request $request)
    {
        if (!session('security_passed')) return redirect()->route('password.request');

        $userId = session('reset_user_id');
        $user = User::findOrFail($userId);

        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        session()->forget(['reset_user_id', 'security_passed']);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully. You can now log in.');
    }
}
