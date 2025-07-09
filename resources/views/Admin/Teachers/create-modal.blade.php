<div class="modal fade" id="createTeacherModal" tabindex="-1" aria-labelledby="createTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTeacherModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.teachers.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="teacher-full-name">Full Name</label>
                            <input type="text" class="form-control" id="teacher-full-name" name="full_name" placeholder="Enter full name" value="{{ old('full_name') }}" required />
                            @error('full_name')<div class="text-danger form-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="teacher-nuptk">NUPTK</label>
                            <input type="text" class="form-control" id="teacher-nuptk" name="nuptk" placeholder="Enter NUPTK" value="{{ old('nuptk') }}" required />
                            @error('nuptk')<div class="text-danger form-text">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="teacher-phone-number">Phone Number</label>
                        <input type="text" class="form-control" id="teacher-phone-number" name="phone_number" placeholder="Optional" value="{{ old('phone_number') }}" />
                        @error('phone_number')<div class="text-danger form-text">{{ $message }}</div>@enderror
                    </div>

                    <hr>
                    <p class="text-muted">Login Account</p>

                    <div class="mb-3">
                        <label class="form-label" for="teacher-username">Username</label>
                        <input type="text" class="form-control" id="teacher-username" name="username" placeholder="Enter username for login" value="{{ old('username') }}" required />
                        @error('username')<div class="text-danger form-text">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="teacher-password">Password</label>
                            <input type="password" class="form-control" id="teacher-password" name="password" required />
                            @error('password')<div class="text-danger form-text">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="teacher-password-confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="teacher-password-confirmation" name="password_confirmation" required />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Teacher</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>