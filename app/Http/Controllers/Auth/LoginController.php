<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/form login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Menangani permintaan login yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Coba untuk melakukan otentikasi
        $credentials = $request->only('username', 'password');
        $remember = $request->boolean('remember'); // Ambil nilai checkbox 'remember me'

        if (Auth::attempt($credentials, $remember)) {
            // Jika berhasil:
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();
            // Redirect ke halaman yang sesuai dengan role
            return redirect()->intended($this->redirectTo());
        }

        // 3. Jika otentikasi gagal
        // Kembalikan ke form login dengan pesan error
        throw ValidationException::withMessages([
            'username' => __('auth.failed'), // 'auth.failed' adalah pesan error default Laravel
        ]);
    }

    /**
     * Menangani proses logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah berhasil logout!');
    }

    /**
     * Menentukan halaman tujuan setelah login berdasarkan role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'guru':
                return route('guru.schedules.index');
            case 'siswa':
                return route('siswa.schedules.index');
            default:
                return '/'; // Halaman default jika role tidak dikenali
        }
    }
}
