<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // Mock logic for demo purposes
        if ($request->email == 'admin@admin.com') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('staff.dashboard');
    }

    public function logout() {
        return redirect()->route('login');
    }
}
