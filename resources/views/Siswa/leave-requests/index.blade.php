@extends('admin.layout.main') {{-- Menggunakan layout utama yang sama --}}

@section('title', 'Riwayat Pengajuan Izin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Riwayat Pengajuan Izin Anda</h4>
        <a href="{{ route('siswa.leave-requests.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Buat Pengajuan Baru
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Tipe Izin</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($leaveRequests as $request)
                        <tr>
                            <td>
                                <i class="bx bx-calendar me-2"></i>
                                <strong>{{ \Carbon\Carbon::parse($request->start_date)->isoFormat('D MMMM YYYY') }}</strong>
                            </td>
                            <td>
                                <i class="bx bx-calendar-check me-2"></i>
                                <strong>{{ \Carbon\Carbon::parse($request->end_date)->isoFormat('D MMMM YYYY') }}</strong>
                            </td>
                            <td>
                                {{-- Mengubah 'sakit' menjadi 'Sakit' dan 'izin_khusus' menjadi 'Izin Khusus' --}}
                                {{ $request->type === 'sakit' ? 'Sakit' : 'Izin Khusus' }}
                            </td>
                            <td>
                                {{-- Badge untuk status agar lebih menarik --}}
                                @if($request->status == 'pending')
                                    <span class="badge bg-label-warning me-1">Pending</span>
                                @elseif($request->status == 'approved')
                                    <span class="badge bg-label-success me-1">Disetujui</span>
                                @else
                                    <span class="badge bg-label-danger me-1">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($request->attachment)
                                    <a href="{{ asset('storage/' . $request->attachment) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="bx bx-paperclip me-1"></i> Lihat
                                    </a>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                             <td>
                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reasonModal{{ $request->id }}">
                                   <i class="bx bx-info-circle me-1"></i> Detail
                                </button>
                            </td>
                        </tr>

                         <div class="modal fade" id="reasonModal{{ $request->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Alasan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>{{ $request->reason }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <img src="{{ asset('assets/img/illustrations/page-misc-error-light.png') }}" alt="No Data" width="150">
                                <h5 class="mt-3">Belum Ada Pengajuan</h5>
                                <p>Anda belum pernah membuat pengajuan izin.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        @if ($leaveRequests->hasPages())
            <div class="card-footer d-flex justify-content-center">
                {{ $leaveRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection