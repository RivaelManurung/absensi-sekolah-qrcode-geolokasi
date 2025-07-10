@extends('admin.layout.main')

@section('title', $pageTitle ?? 'Ambil Absensi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Ambil Absensi</h4>
            <div class="d-flex flex-column flex-md-row justify-content-between">
                <div>
                    <p class="mb-0"><strong>Kelas:</strong> {{ $schedule->class->name ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Mata Pelajaran:</strong> {{ $schedule->subject->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="mb-0"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    <p class="mb-0"><strong>Tanggal:</strong> {{ now()->format('d F Y') }}</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('attendances.store', $schedule->id) }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Nama Siswa</th>
                                <th class="text-center" style="width: 40%;">Status Kehadiran</th>
                                <th>Catatan (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $student->full_name }}</td>
                                    <td>
                                        <input type="hidden" name="attendances[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                        
                                        <div class="d-flex justify-content-around">
                                            @php
                                                // --- PERUBAHAN DI SINI ---
                                                // Cek apakah ada data absensi untuk siswa ini
                                                $attendanceRecord = $existingAttendance->get($student->id);
                                                // Jika ada, ambil statusnya. Jika tidak, default-nya 'hadir'
                                                $currentStatus = $attendanceRecord ? $attendanceRecord->status : 'hadir';
                                            @endphp
                                            
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="hadir-{{ $student->id }}" value="hadir" @if($currentStatus == 'hadir') checked @endif>
                                                <label class="form-check-label" for="hadir-{{ $student->id }}">Hadir</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="sakit-{{ $student->id }}" value="sakit" @if($currentStatus == 'sakit') checked @endif>
                                                <label class="form-check-label" for="sakit-{{ $student->id }}">Sakit</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="izin-{{ $student->id }}" value="izin" @if($currentStatus == 'izin') checked @endif>
                                                <label class="form-check-label" for="izin-{{ $student->id }}">Izin</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="alpa-{{ $student->id }}" value="alpa" @if($currentStatus == 'alpa') checked @endif>
                                                <label class="form-check-label" for="alpa-{{ $student->id }}">Alpa</label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- PERUBAHAN DI SINI: Tampilkan catatan yang sudah ada --}}
                                        <input type="text" name="attendances[{{ $student->id }}][notes]" class="form-control form-control-sm" placeholder="e.g., Izin acara keluarga" value="{{ $attendanceRecord->notes ?? '' }}">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada siswa di kelas ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    {{-- PERBAIKAN: Menggunakan nama route yang benar --}}
                    <a href="{{ route('user.schedules.index') }}" class="btn btn-secondary">Kembali ke Jadwal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection