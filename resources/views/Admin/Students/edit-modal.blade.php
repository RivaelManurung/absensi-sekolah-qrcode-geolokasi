{{-- resources/views/admin/students/edit-modal.blade.php --}}

<div class="modal fade" id="editStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- Action akan diisi oleh JavaScript --}}
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            {{-- Tambahkan ID di sini --}}
                            <input type="text" id="edit-student-full-name" class="form-control" name="full_name" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Class</label>
                            {{-- Tambahkan ID di sini --}}
                            <select id="edit-student-class-id" name="class_id" class="form-select" required>
                                {{-- Looping classes untuk pilihan --}}
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN</label>
                            {{-- Tambahkan ID di sini --}}
                            <input type="text" id="edit-student-nisn" class="form-control" name="nisn" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS</label>
                             {{-- Tambahkan ID di sini --}}
                            <input type="text" id="edit-student-nis" class="form-control" name="nis" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gender</label>
                            {{-- Tambahkan ID di sini --}}
                            <select id="edit-student-gender" name="gender" class="form-select" required>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            {{-- Tambahkan ID di sini --}}
                            <input type="date" id="edit-student-date-of-birth" class="form-control" name="date_of_birth" required />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>