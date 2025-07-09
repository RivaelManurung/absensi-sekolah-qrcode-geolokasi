@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Teachers</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeacherModal">
                <i class="bx bx-plus me-1"></i> Add Teacher
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>NUPTK</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($teachers as $teacher)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <i class="icon-base bx bxs-user-circle icon-md text-primary me-3"></i>
                            <strong>{{ $teacher->full_name }}</strong>
                        </td>
                        <td>{{ $teacher->nuptk }}</td>
                        <td>{{ $teacher->user->username }}</td>
                        <td>{{ $teacher->phone_number ?? '-' }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-teacher" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editTeacherModal"
                                        data-id="{{ $teacher->id }}"
                                        data-full-name="{{ $teacher->full_name }}"
                                        data-nuptk="{{ $teacher->nuptk }}"
                                        data-phone-number="{{ $teacher->phone_number }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this teacher? This will also delete their login account.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No teachers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($teachers->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $teachers->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.teachers.create-modal')
@include('admin.teachers.edit-modal')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Script untuk mengisi data pada Edit Modal
    document.querySelectorAll('.edit-teacher').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.querySelector('#editTeacherModal');
            const form = modal.querySelector('form');
            
            // Ambil data dari atribut data-*
            const id = this.getAttribute('data-id');
            const fullName = this.getAttribute('data-full-name');
            const nuptk = this.getAttribute('data-nuptk');
            const phoneNumber = this.getAttribute('data-phone-number');

            // Set action form untuk update
            form.action = `{{ url('admin/teachers') }}/${id}`;
            
            // Isi value pada form di dalam modal
            modal.querySelector('#edit-teacher-full-name').value = fullName || '';
            modal.querySelector('#edit-teacher-nuptk').value = nuptk || '';
            modal.querySelector('#edit-teacher-phone-number').value = phoneNumber || '';
        });
    });
});
</script>
@endsection