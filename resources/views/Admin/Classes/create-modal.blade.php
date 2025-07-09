<div class="modal fade" id="createClassModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Class</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.classes.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Class Name</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g., 10 IPA 1" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade Level</label>
                        <input type="number" class="form-control" name="grade_level" placeholder="e.g., 10" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->year }} {{ $year->status == 'active' ? '(Active)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Homeroom Teacher (Wali Kelas)</label>
                        <select name="homeroom_teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Class</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>