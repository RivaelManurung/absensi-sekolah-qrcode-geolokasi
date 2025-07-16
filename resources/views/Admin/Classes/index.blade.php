@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
        {{-- Notifikasi dari Session (Sudah ada) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ SOLUSI: TAMBAHKAN BLOK INI UNTUK MENAMPILKAN ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan!</strong> Mohon periksa input Anda:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Classes</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
                <i class="bx bx-plus me-1"></i> Add Class
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Grade</th>
                        <th>Homeroom Teacher</th>
                        <th>Academic Year</th>
                        <th>Total Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($classes as $class)
                    <tr>
                        <td><strong>{{ $class->name }}</strong></td>
                        <td>{{ $class->grade_level }}</td>
                        <td>{{ $class->homeroomTeacher->full_name ?? 'N/A' }}</td>
                        <td>{{ $class->academicYear->year ?? 'N/A' }}</td>
                        <td>{{ $class->students_count }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-class" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editClassModal"
                                        data-id="{{ $class->id }}"
                                        data-name="{{ $class->name }}"
                                        data-grade-level="{{ $class->grade_level }}"
                                        data-academic-year-id="{{ $class->academic_year_id }}"
                                        data-homeroom-teacher-id="{{ $class->homeroom_teacher_id }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.classes.destroy', ['kela' => $class->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this class?');">
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
                        <td colspan="6" class="text-center">No classes found.</td>`
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.classes.create-modal')
@include('admin.classes.edit-modal')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-class').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.querySelector('#editClassModal');
            const form = modal.querySelector('form');
            
            form.action = `{{ url('admin/classes') }}/${this.dataset.id}`;
            
            modal.querySelector('#edit-class-name').value = this.dataset.name;
            modal.querySelector('#edit-class-grade-level').value = this.dataset.gradeLevel;
            modal.querySelector('#edit-class-academic-year-id').value = this.dataset.academicYearId;
            modal.querySelector('#edit-class-homeroom-teacher-id').value = this.dataset.homeroomTeacherId;
        });
    });
});
</script>
@endsection