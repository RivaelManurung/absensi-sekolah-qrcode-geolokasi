<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::latest()->paginate(10);
        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function create()
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|string|unique:academic_years,year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($request) {
            // Jika yang baru diatur sebagai 'active', nonaktifkan yang lain
            if ($request->status === 'active') {
                AcademicYear::where('status', 'active')->update(['status' => 'inactive']);
            }
            AcademicYear::create($request->all());
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear)
    {
        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'year' => 'required|string|unique:academic_years,year,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($request, $academicYear) {
            if ($request->status === 'active') {
                // Nonaktifkan tahun ajaran lain yang aktif, KECUALI yang sedang diedit ini
                AcademicYear::where('id', '!=', $academicYear->id)->where('status', 'active')->update(['status' => 'inactive']);
            }
            $academicYear->update($request->all());
        });

        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        // Pencegahan: Jangan hapus jika masih digunakan oleh kelas
        if ($academicYear->classes()->exists()) {
            return back()->with('error', 'Gagal menghapus! Tahun ajaran ini masih digunakan oleh data kelas.');
        }

        $academicYear->delete();
        return redirect()->route('admin.academic-years.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}