<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting; // Pastikan Anda membuat model Setting
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Menampilkan halaman form pengaturan
    public function edit()
    {
        // Ambil data dari database, jika tidak ada, gunakan nilai default
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.edit', compact('settings'));
    }

    // Menyimpan perubahan pengaturan
    public function update(Request $request)
    {
        $request->validate([
            'school_latitude' => 'required|numeric',
            'school_longitude' => 'required|numeric',
        ]);

        // Simpan atau perbarui setiap pengaturan
        Setting::updateOrCreate(['key' => 'school_latitude'], ['value' => $request->school_latitude]);
        Setting::updateOrCreate(['key' => 'school_longitude'], ['value' => $request->school_longitude]);

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}