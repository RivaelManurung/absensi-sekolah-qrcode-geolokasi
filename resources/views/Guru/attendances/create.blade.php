@extends('admin.layout.main')

@section('title', $pageTitle ?? 'Ambil Absensi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
         {{-- Notifikasi dari Session (Sudah ada) --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ✅ SOLUSI: TAMBAHKAN BLOK INI UNTUK MENAMPILKAN ERROR VALIDASI --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi Kesalahan!</strong> Mohon periksa input Anda:
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
            <form action="{{ route('guru.attendances.store', $schedule->id) }}" method="POST">
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
                                            {{-- ✅ PERBAIKAN: Logika pengecekan disederhanakan dan dipindah ke dalam atribut input --}}
                                            @php
                                                // Ambil data absensi yang sudah ada untuk siswa ini, jika tidak ada, default-nya 'hadir'
                                                $status = $existingAttendance[$student->id]->status ?? 'hadir';
                                            @endphp

                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="hadir-{{ $student->id }}" value="hadir" {{ $status == 'hadir' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="hadir-{{ $student->id }}">Hadir</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="sakit-{{ $student->id }}" value="sakit" {{ $status == 'sakit' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sakit-{{ $student->id }}">Sakit</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="izin-{{ $student->id }}" value="izin" {{ $status == 'izin' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="izin-{{ $student->id }}">Izin</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="attendances[{{ $student->id }}][status]" id="alpa-{{ $student->id }}" value="alpa" {{ $status == 'alpa' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="alpa-{{ $student->id }}">Alpa</label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{-- ✅ PERBAIKAN: Menampilkan catatan yang sudah ada --}}
                                        <input type="text" name="attendances[{{ $student->id }}][notes]" class="form-control form-control-sm" placeholder="e.g., Izin acara keluarga" value="{{ $existingAttendance[$student->id]->notes ?? '' }}">
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
                    <a href="{{ route('guru.schedules.index') }}" class="btn btn-secondary">Kembali ke Jadwal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection