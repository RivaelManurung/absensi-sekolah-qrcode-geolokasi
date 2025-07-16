<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; // ✅ 1. Tambahkan fasad Log

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class', 'user')->latest()->paginate(10);
        $classes = Kelas::orderBy('name')->get();
        return view('admin.students.index', compact('students', 'classes'));
    }

    public function store(Request $request)
    {
        // LOG: Mencatat upaya penambahan siswa baru
        Log::info('Attempting to store a new student.', $request->except('password', 'password_confirmation'));

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => 'required|string|numeric|unique:students,nisn',
            'nis' => 'required|string|numeric|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:laki-laki,perempuan',
            'date_of_birth' => 'required|date',
            'username' => 'required|string|min:4|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validatedData['full_name'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'siswa',
            ]);

            $student = new Student($validatedData);
            $student->user_id = $user->id;
            $student->save();

            DB::commit();

            // LOG: Mencatat keberhasilan
            Log::info("Successfully stored new student with ID: {$student->id} and User ID: {$user->id}.");

            return redirect()->route('admin.students.index')->with('success', 'Siswa baru berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // LOG: Mencatat error yang terjadi
            Log::error("Failed to store new student: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Gagal menambahkan siswa baru. Silakan coba lagi.')->withInput();
        }
    }

    public function update(Request $request, Student $student)
    {
        // LOG: Mencatat upaya update
        Log::info("Attempting to update student with ID: {$student->id}.");

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'nisn' => ['required', 'string', 'numeric', Rule::unique('students')->ignore($student->id)],
            'nis' => ['required', 'string', 'numeric', Rule::unique('students')->ignore($student->id)],
            'class_id' => 'required|exists:classes,id',
            'gender' => 'required|in:laki-laki,perempuan',
            'date_of_birth' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $student->update($validatedData);

            if ($student->user) {
                $student->user->update(['name' => $request->full_name]);
            }

            DB::commit();

            // LOG: Mencatat keberhasilan update
            Log::info("Successfully updated student with ID: {$student->id}.");

            return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            // LOG: Mencatat error saat update
            Log::error("Failed to update student with ID: {$student->id}. Error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal memperbarui data siswa.')->withInput();
        }
    }

    public function destroy(Student $student)
    {
        // LOG: Mencatat upaya penghapusan
        Log::info("Attempting to delete student with ID: {$student->id} and associated User ID: {$student->user_id}.");

        DB::beginTransaction();
        try {
            $user = $student->user;
            $student->delete();

            if ($user) {
                $user->delete();
            }

            DB::commit();

            // LOG: Mencatat keberhasilan penghapusan
            Log::info("Successfully deleted student with ID: {$student->id}.");

            return redirect()->route('admin.students.index')->with('success', 'Data siswa dan akun login terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // LOG: Mencatat error saat penghapusan
            Log::error("Failed to delete student with ID: {$student->id}. Error: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Gagal menghapus siswa.');
        }
    }
}