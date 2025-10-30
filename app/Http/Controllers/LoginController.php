<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function adminLogin()
    {
        return redirect('/')->with('info', 'Silakan gunakan halaman login utama untuk semua role.');
    }

    public function errorUnauthorized()
    {
        return view('error-unauthorized');
    }

    public function errorAdminOnly()
    {
        return view('error-admin-only');
    }

    public function storeMathCaptcha(Request $request)
    {
        session(['math_captcha_answer' => $request->answer]);
        return response()->json(['success' => true]);
    }

    public function storeCaptcha(Request $request)
    {
        session(['admin_captcha' => $request->captcha]);
        return response()->json(['success' => true]);
    }

    /**
     * Handle an authentication attempt for all roles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $captcha_answer = $request->input('captcha_answer');
        $session_answer = $request->session()->get('math_captcha_answer');
        
        if (empty($captcha_answer)) {
            return back()->with('loginError', 'Captcha harus diisi!');
        }
        
        if ((int)$captcha_answer !== (int)$session_answer) {
            return back()->with('loginError', 'Jawaban matematika salah!');
        }

        $user = User::where('username', $credentials['username'])->first();
        if (!$user) {
            return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
        }

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $request->session()->forget('math_captcha_answer'); 

            $welcomeMessage = 'Selamat Datang di Dashboard KasirKu';
            if ($user->isAdmin()) {
                $welcomeMessage = 'Selamat Datang di Dashboard Admin';
            } elseif ($user->isManajer()) {
                $welcomeMessage = 'Selamat Datang di Dashboard Manajer';
            }

            return redirect()
                ->intended('/dashboard')
                ->with('success', $welcomeMessage);
        }
        return back()->with('loginError', 'Username/Password/Captcha Anda Salah, Coba Lagi!');
    }

    /**
     * Handle an authentication attempt for admin (deprecated - redirects to main login).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticateAdmin(Request $request)
    {
        return redirect('/')->with('info', 'Silakan gunakan halaman login utama untuk semua role.');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
