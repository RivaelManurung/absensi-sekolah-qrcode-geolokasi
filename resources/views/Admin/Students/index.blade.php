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

    {{-- Sisa kode di bawah ini tidak perlu diubah --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Students</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createStudentModal">
                <i class="bx bx-plus me-1"></i> Add Student
            </button>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                {{-- ... isi tabel ... --}}
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
                        <td>{{ $students->firstItem() + $loop->index }}</td>
                        <td>
                            <i class="icon-base bx bxs-user-badge icon-md text-primary me-3"></i>
                            <strong>{{ $student->full_name }}</strong>
                        </td>
                        <td>{{ $student->nisn }}</td>
                        <td>{{ $student->class->name ?? 'Not Assigned' }}</td>
                        <td>{{ $student->user->username }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-student" data-bs-toggle="modal"
                                        data-bs-target="#editStudentModal" data-id="{{ $student->id }}"
                                        data-full-name="{{ $student->full_name }}" data-nisn="{{ $student->nisn }}"
                                        data-nis="{{ $student->nis }}" data-class-id="{{ $student->class_id }}"
                                        data-gender="{{ $student->gender }}"
                                        data-date-of-birth="{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : '' }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? Akun login mereka juga akan terhapus.');">
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
{{-- Script Anda sudah benar --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-student');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const dataset = this.dataset;
                const modal = document.querySelector('#editStudentModal');
                const form = modal.querySelector('form');

                form.action = `{{ url('admin/students') }}/${dataset.id}`;
                
                modal.querySelector('#edit-student-full-name').value = dataset.fullName;
                modal.querySelector('#edit-student-nisn').value = dataset.nisn;
                modal.querySelector('#edit-student-nis').value = dataset.nis;
                modal.querySelector('#edit-student-class-id').value = dataset.classId;
                modal.querySelector('#edit-student-gender').value = dataset.gender;
                modal.querySelector('#edit-student-date-of-birth').value = dataset.dateOfBirth;
            });
        });
    });
</script>
@endsection