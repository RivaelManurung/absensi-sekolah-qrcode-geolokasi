@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Schedules /</span> Add New</h4>
    <div class="card">
        <div class="card-header">
            <h5>Add New Schedule</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select" required>
                            @foreach($classes as $class) <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-select" required>
                            @foreach($subjects as $subject) <option value="{{ $subject->id }}">{{ $subject->name }}
                            </option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher) <option value="{{ $teacher->id }}">{{ $teacher->full_name }}
                            </option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Day</label>
                        <select name="day_of_week" class="form-select" required>
                            @foreach($days as $key => $day) <option value="{{ $key }}">{{ $day }}</option> @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Time</label>
                        <input type="time" class="form-control" name="start_time" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Time</label>
                        <input type="time" class="form-control" name="end_time" required />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Academic Year</label>
                        <select name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year) <option value="{{ $year->id }}" @if($year->status ==
                                'active') selected @endif>{{ $year->year }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection