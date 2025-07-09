<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::latest()->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nuptk' => ['required', 'string', 'max:255', 'unique:teachers'],
            'phone_number' => ['nullable', 'string'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->full_name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'guru',
            ]);

            Teacher::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nuptk' => $request->nuptk,
                'phone_number' => $request->phone_number,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan guru baru.')->withInput();
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Guru baru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nuptk' => ['required', 'string', 'max:255', 'unique:teachers,nuptk,' . $teacher->id],
            'phone_number' => ['nullable', 'string'],
        ]);

        $teacher->update($request->only(['full_name', 'nuptk', 'phone_number']));
        $teacher->user->update(['name' => $request->full_name]);

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        // Pastikan guru tidak terikat sebagai wali kelas sebelum dihapus
        if ($teacher->homeroomClass) {
            return back()->with('error', 'Gagal menghapus. Guru ini masih menjadi wali kelas ' . $teacher->homeroomClass->name);
        }

        try {
            // User akan terhapus otomatis karena cascade on delete
            $teacher->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data guru.');
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus.');
    }
}
