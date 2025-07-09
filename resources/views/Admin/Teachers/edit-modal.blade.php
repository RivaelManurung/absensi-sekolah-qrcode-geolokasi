<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST"> {{-- Action diisi oleh Javascript --}}
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="edit-teacher-full-name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="edit-teacher-full-name" name="full_name" required>
                        @error('full_name')<div class="text-danger form-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit-teacher-nuptk" class="form-label">NUPTK</label>
                        <input type="text" class="form-control" id="edit-teacher-nuptk" name="nuptk" required>
                        @error('nuptk')<div class="text-danger form-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit-teacher-phone-number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="edit-teacher-phone-number" name="phone_number">
                        @error('phone_number')<div class="text-danger form-text">{{ $message }}</div>@enderror
                    </div>
                    
                    <small class="text-muted">Username and password cannot be changed from this form.</small>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>