<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     */
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    /**
     * Memperbarui informasi profil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            // Tambahkan validasi lain jika ada field yang bisa diubah
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        // Update juga tabel profil terkait (guru/siswa) jika ada field yang sama
        if ($user->role === 'guru' && $user->teacher) {
            $user->teacher->update(['full_name' => $request->name]);
        } elseif ($user->role === 'siswa' && $user->student) {
            $user->student->update(['full_name' => $request->name]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Mengubah password pengguna.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}