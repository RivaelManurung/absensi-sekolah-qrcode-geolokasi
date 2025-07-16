<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::where('student_id', Auth::user()->student->id)
            ->latest()
            ->paginate(10);
        return view('siswa.leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        return view('siswa.leave-requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:sakit,izin_khusus',
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
        ]);

        return redirect()->route('siswa.leave-requests.index')->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}