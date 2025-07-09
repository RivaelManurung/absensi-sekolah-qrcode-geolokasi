@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Students</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                <i class="bx bx-plus me-1"></i> Add Student
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>NISN</th>
                        <th>Class</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <i class="icon-base bx bxs-user-badge icon-md text-primary me-3"></i>
                            <strong>{{ $student->full_name }}</strong>
                        </td>
                        <td>{{ $student->nisn }}</td>
                        <td>{{ $student->class->name ?? 'Not Assigned' }}</td>
                        <td>{{ $student->user->username }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-student" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editStudentModal"
                                        data-id="{{ $student->id }}"
                                        data-full-name="{{ $student->full_name }}"
                                        data-nisn="{{ $student->nisn }}"
                                        data-nis="{{ $student->nis }}"
                                        data-class-id="{{ $student->class_id }}"
                                        data-gender="{{ $student->gender }}"
                                        data-date-of-birth="{{ $student->date_of_birth }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student? This will also delete their login account.');">
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
                        <td colspan="6" class="text-center">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($students->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.students.create-modal')
@include('admin.students.edit-modal')

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-student').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.querySelector('#editStudentModal');
            const form = modal.querySelector('form');
            
            const id = this.dataset.id;
            const fullName = this.dataset.fullName;
            const nisn = this.dataset.nisn;
            const nis = this.dataset.nis;
            const classId = this.dataset.classId;
            const gender = this.dataset.gender;
            const dateOfBirth = this.dataset.dateOfBirth;

            form.action = `{{ url('admin/students') }}/${id}`;
            
            modal.querySelector('#edit-student-full-name').value = fullName;
            modal.querySelector('#edit-student-nisn').value = nisn;
            modal.querySelector('#edit-student-nis').value = nis;
            modal.querySelector('#edit-student-class-id').value = classId;
            modal.querySelector('#edit-student-gender').value = gender;
            modal.querySelector('#edit-student-date-of-birth').value = dateOfBirth;
        });
    });
});
</script>
@endsection