<div class="modal fade" id="editClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input type="text" id="edit-class-name" class="form-control" name="name" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade Level</label>
                        <input type="number" id="edit-class-grade-level" class="form-control" name="grade_level" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <select id="edit-class-academic-year-id" name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->year }} {{ $year->status == 'active' ? '(Active)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Homeroom Teacher (Wali Kelas)</label>
                        <select id="edit-class-homeroom-teacher-id" name="homeroom_teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>