<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        // withCount untuk menghitung jumlah siswa secara efisien
        $classes = Kelas::with('homeroomTeacher', 'academicYear')->withCount('students')->latest()->paginate(10);
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        return view('admin.classes.create', compact('teachers', 'academicYears'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'integer', 'min:1'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'homeroom_teacher_id' => ['required', 'exists:teachers,id', 'unique:classes,homeroom_teacher_id'],
        ], [
            'homeroom_teacher_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
        ]);

        Kelas::create($request->all());

        return redirect()->route('admin.classes.index')->with('success', 'Kelas baru berhasil dibuat.');
    }

    public function edit(Kelas $kela) // Variabel $kela sesuai dengan nama model
    {
        $teachers = Teacher::orderBy('full_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        return view('admin.classes.edit', compact('kela', 'teachers', 'academicYears'));
    }

    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'grade_level' => ['required', 'integer', 'min:1'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'homeroom_teacher_id' => ['required', 'exists:teachers,id', 'unique:classes,homeroom_teacher_id,' . $kela->id],
        ], [
            'homeroom_teacher_id.unique' => 'Guru ini sudah menjadi wali kelas di kelas lain.'
        ]);

        $kela->update($request->all());

        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->students()->count() > 0) {
            return back()->with('error', 'Gagal menghapus. Masih ada siswa di dalam kelas ini.');
        }

        if ($kela->schedules()->count() > 0) {
            return back()->with('error', 'Gagal menghapus. Masih ada jadwal pelajaran yang terikat dengan kelas ini.');
        }

        $kela->delete();

        return redirect()->route('admin.classes.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}