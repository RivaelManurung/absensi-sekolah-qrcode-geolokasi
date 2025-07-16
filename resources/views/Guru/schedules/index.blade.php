@extends('admin.layout.main') {{-- Menggunakan layout admin yang sama --}}

@section('title', $pageTitle ?? 'Jadwal Pelajaran')

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
    <h4 class="fw-bold py-3 mb-4">Jadwal Pelajaran Anda</h4>

    @php
    // Array untuk menerjemahkan nomor hari ke nama hari
    $days = [
    '1' => 'Senin',
    '2' => 'Selasa',
    '3' => 'Rabu',
    '4' => 'Kamis',
    '5' => 'Jumat',
    '6' => 'Sabtu'
    ];
    @endphp

    {{-- Looping utama berdasarkan hari --}}
    @forelse ($schedulesByDay->sortKeys() as $dayNumber => $schedules)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">{{ $days[$dayNumber] ?? 'Hari Tidak Dikenali' }}</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Mata Pelajaran</th>
                        {{-- Kolom dinamis berdasarkan role --}}
                        @if (Auth::user()->role === 'siswa')
                        <th>Guru Pengajar</th>
                        @elseif (Auth::user()->role === 'guru')
                        <th>Kelas</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    {{-- Looping jadwal pelajaran untuk hari tersebut --}}
                    @foreach ($schedules as $schedule)
                    <tr>
                        <td>
                            <i class="bx bx-time-five me-2"></i>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{
                            \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </td>
                        <td><strong>{{ $schedule->subject->name ?? 'N/A' }}</strong></td>

                        {{-- Kolom dinamis berdasarkan role --}}
                        @if (Auth::user()->role === 'siswa')
                        <td>{{ $schedule->teacher->full_name ?? 'N/A' }}</td>
                        @elseif (Auth::user()->role === 'guru')
                        <td>{{ $schedule->class->name ?? 'N/A' }}</td>
                        @endif

                        <td>
                            {{-- Tombol "Ambil Absensi" hanya muncul untuk guru --}}
                            @if(Auth::user()->role === 'guru')
                            <a href="{{ route('guru.attendances.create', $schedule->id) }}"
                                class="btn btn-sm btn-primary">Ambil Absensi</a>
                            @else
                            <span class="text-muted">--</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    {{-- Tampilan jika tidak ada jadwal sama sekali --}}
    <div class="card">
        <div class="card-body text-center">
            <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" alt="No Schedule" width="150">
            <h5 class="mt-3">Jadwal Kosong</h5>
            <p>Tidak ada jadwal pelajaran yang tersedia untuk Anda saat ini.</p>
        </div>
    </div>
    @endforelse

</div>
@endsection