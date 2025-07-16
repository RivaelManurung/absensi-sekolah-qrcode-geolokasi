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
            <h5 class="mb-0">Manage Subjects</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
                <i class="bx bx-plus me-1"></i> Add Subject
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Subject Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($subjects as $subject)
                    <tr>
                        <td><span class="badge bg-label-primary me-1">{{ $subject->code }}</span></td>
                        <td><strong>{{ $subject->name }}</strong></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-subject" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editSubjectModal"
                                        data-id="{{ $subject->id }}"
                                        data-name="{{ $subject->name }}"
                                        data-code="{{ $subject->code }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                        <td colspan="3" class="text-center">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.subjects.create-modal')
@include('admin.subjects.edit-modal')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-subject').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.querySelector('#editSubjectModal');
            const form = modal.querySelector('form');
            
            form.action = `{{ url('admin/subjects') }}/${this.dataset.id}`;
            modal.querySelector('#edit-subject-name').value = this.dataset.name;
            modal.querySelector('#edit-subject-code').value = this.dataset.code;
        });
    });
});
</script>
@endsection