@extends('admin.layout.main')

@section('title', 'Kelola Jadwal')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Notifikasi dari Session --}}
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

    {{-- Menampilkan Error Validasi --}}
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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kelola Jadwal</h5>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Jadwal
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Waktu</th>
                        <th>Kelas</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                    $days = ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu'];
                    @endphp
                    
                    @forelse ($schedules as $schedule)
                    <tr>
                        <td><strong>{{ $days[$schedule->day_of_week] ?? 'N/A' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->class->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->teacher->full_name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex">
                                <a class="btn btn-sm btn-outline-primary me-2" href="{{ route('admin.schedules.edit', $schedule->id) }}">Ubah</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada jadwal ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Menampilkan link pagination --}}
        @if ($schedules->hasPages())
        <div class="card-footer d-flex justify-content-end">
            {{ $schedules->links() }}
        </div>
        @endif
    </div>
</div>
@endsection