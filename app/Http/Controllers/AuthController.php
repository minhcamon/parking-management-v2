<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Chặn đăng nhập nếu tài khoản bị Inactive
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị ngừng hoạt động. Vui lòng liên hệ Admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('staff.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
