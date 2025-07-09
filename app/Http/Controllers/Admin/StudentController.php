<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class', 'user')->latest()->paginate(10);
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = Kelas::orderBy('name')->get(); // Menggunakan model Kelas
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        // Validasi data yang masuk
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn',
            'nis' => 'required|string|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:laki-laki,perempuan',
            'date_of_birth' => 'required|date',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Gunakan transaction untuk menjaga konsistensi data
        DB::beginTransaction();
        try {
            // 1. Buat data user
            $user = User::create([
                'name' => $request->full_name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            // 2. Buat data siswa
            Student::create([
                'user_id' => $user->id,
                'full_name' => $request->full_name,
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'class_id' => $request->class_id,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Optional: log error $e->getMessage()
            return back()->with('error', 'Gagal menambahkan siswa baru.')->withInput();
        }

        return redirect()->route('admin.students.index')->with('success', 'Siswa baru berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        // Tampilkan detail spesifik jika perlu, atau redirect ke edit
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = Kelas::orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|unique:students,nisn,' . $student->id,
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:laki-laki,perempuan',
            'date_of_birth' => 'required|date',
        ]);
        
        // Update data di tabel students
        $student->update($request->all());

        // Jika ingin update username/nama di tabel user juga
        $student->user->update([
            'name' => $request->full_name,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        // Karena ada onDelete('cascade') di migration, saat data siswa dihapus,
        // data user yang terhubung juga akan ikut terhapus.
        try {
            $student->delete();
        } catch (\Exception $e) {
             return back()->with('error', 'Gagal menghapus siswa.');
        }
        
        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}