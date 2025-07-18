@extends('admin.layout.main')

@section('title', 'Ubah Jadwal')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Jadwal /</span> Ubah</h4>

    {{-- ✅ PERBAIKAN UTAMA: Blok untuk menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali data yang Anda masukkan.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5>Ubah Jadwal</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="class_id" class="form-select" required>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" @if($schedule->class_id == $class->id) selected @endif>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mata Pelajaran</label>
                        <select name="subject_id" class="form-select" required>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" @if($schedule->subject_id == $subject->id) selected @endif>{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Guru</label>
                        <select name="teacher_id" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @if($schedule->teacher_id == $teacher->id) selected @endif>{{ $teacher->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hari</label>
                        <select name="day_of_week" class="form-select" required>
                            @foreach($days as $key => $day)
                                <option value="{{ $key }}" @if($schedule->day_of_week == $key) selected @endif>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Mulai</label>
                        {{-- Perbaikan: Format waktu agar sesuai dengan input type="time" --}}
                        <input type="time" class="form-control" name="start_time" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" required />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" name="end_time" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" required />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="academic_year_id" class="form-select" required>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" @if($schedule->academic_year_id == $year->id) selected @endif>{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Perbarui Jadwal</button>
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection