@extends('admin.layout.main')

@section('title', $pageTitle ?? 'Jadwal Pelajaran')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Siswa /</span> Jadwal Pelajaran
    </h4>

    {{-- Looping per hari (Senin, Selasa, dst.) --}}
    @forelse ($schedulesByDay as $day => $schedules)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $day }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th class="text-center">Status Kehadiran Hari Ini</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </td>
                                    <td><strong>{{ $schedule->subject->name }}</strong></td>
                                    <td>{{ $schedule->teacher->full_name }}</td>
                                    <td class="text-center">
                                        @php
                                            // Cek data absensi untuk jadwal ini
                                            $attendance = $attendancesToday->get($schedule->id);
                                            $status = $attendance ? $attendance->status : null;
                                            
                                            // Tentukan warna badge berdasarkan status
                                            $badgeColor = [
                                                'hadir' => 'bg-success',
                                                'sakit' => 'bg-warning',
                                                'izin' => 'bg-info',
                                                'alpa' => 'bg-danger',
                                            ][$status] ?? 'bg-secondary';
                                        @endphp

                                        {{-- Tampilkan status dengan badge --}}
                                        <span class="badge {{ $badgeColor }}">
                                            {{ $status ? ucfirst($status) : 'Belum Absen' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center">
                <p>Jadwal pelajaran belum tersedia untuk semester ini.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection