@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Schedules /</span> Edit</h4>
    <div class="card">
        <div class="card-header">
            <h5>Edit Schedule</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            @foreach($classes as $class) <option value="{{ $class->id }}" @if($schedule->class_id == $class->id) selected @endif>{{ $class->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            @foreach($subjects as $subject) <option value="{{ $subject->id }}" @if($schedule->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher) <option value="{{ $teacher->id }}" @if($schedule->teacher_id == $teacher->id) selected @endif>{{ $teacher->full_name }}</option> @endforeach
                        </select>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label class="form-label">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            @foreach($days as $key => $day) <option value="{{ $key }}" @if($schedule->day_of_week == $key) selected @endif>{{ $day }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start_time" value="{{ $schedule->start_time }}" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end_time" value="{{ $schedule->end_time }}" required />
                    </div>
                     <div class="col-md-12 mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year) <option value="{{ $year->id }}" @if($schedule->academic_year_id == $year->id) selected @endif>{{ $year->year }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection