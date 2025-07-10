<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with('student.class')->latest()->paginate(15);
        return view('admin.leave-requests.index', compact('leaveRequests'));
    }
    
    /**
     * Memproses persetujuan atau penolakan pengajuan izin.
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        // Ambil ID user admin yang sedang login
        $approverId = Auth::id();

        // Cari profil guru yang terhubung dengan user admin (jika ada)
        // Jika tidak ada, Anda mungkin perlu membuat kolom 'approved_by_admin_id'
        $adminTeacherProfile = Auth::user()->teacher;

        $leaveRequest->update([
            'status' => $request->status,
            'approved_by_teacher_id' => $adminTeacherProfile ? $adminTeacherProfile->id : null,
        ]);

        return back()->with('success', 'Status pengajuan izin berhasil diperbarui.');
    }
}