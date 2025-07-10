@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Manage Leave Requests</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Class</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($leaveRequests as $request)
                    <tr>
                        <td>{{ $leaveRequests->firstItem() + $loop->index }}</td>
                        <td>{{ $request->student->full_name ?? 'N/A' }}</td>
                        <td>{{ $request->student->class->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($request->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('d M Y') }}</td>
                        <td>{{ Str::limit($request->reason, 30) }}</td>
                        <td>
                            @if ($request->status == 'approved')
                                <span class="badge bg-label-success">Approved</span>
                            @elseif ($request->status == 'rejected')
                                <span class="badge bg-label-danger">Rejected</span>
                            @else
                                <span class="badge bg-label-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if ($request->status == 'pending')
                                <div class="d-flex">
                                    <form action="{{ route('admin.leave-requests.updateStatus', $request->id) }}" method="POST" class="me-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.leave-requests.updateStatus', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                </div>
                            @else
                                <small class="text-muted">Processed</small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($leaveRequests->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection