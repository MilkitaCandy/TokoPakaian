<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Controller untuk mengatur login, register, logout
class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    // Fitur untuk melakukan login
    public function authenticate(Request $request) {
        $credentials = $request->validate(['username' => 'required', 'password' => 'required']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            if (Auth::user()->role === 'admin') {
                // SALAH: return redirect()->intended('admin.dashboard');
                // BENER: Pakai URL yang ada di routes/web.php
                return redirect()->intended('/dashboard-admin');
            }
            // SALAH: return redirect()->intended('customer.home');
            // BENER: Pakai URL yang ada di routes/web.php
            return redirect()->intended('/home');
        }
        return back()->with('error', 'Username atau password salah bro!');
    }

    // Fitur untuk melakukan register
    public function register(Request $request) {
        $request->validate(['username' => 'required|unique:users', 'password' => 'required|min:5']);
        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'customer'
        ]);
        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}