<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan daftar pengajuan izin milik siswa yang login.
     */
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $leaveRequests = LeaveRequest::where('student_id', $studentId)->latest()->paginate(10);

        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan izin baru.
     */
    public function create()
    {
        return view('leave-requests.create');
    }

    /**
     * Menyimpan pengajuan izin baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:sakit,izin_khusus', // Sesuaikan dengan enum di migrasi Anda
            'reason' => 'required|string|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
        }

        LeaveRequest::create([
            'student_id' => Auth::user()->student->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachment' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('leave-requests.index')->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}