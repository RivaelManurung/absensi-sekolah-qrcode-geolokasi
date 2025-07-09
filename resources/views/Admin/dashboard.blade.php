@extends('admin.layout.main')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Welcome Card --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat Datang, {{ Auth::user()->name }}! 👋</h5>
                            <p class="mb-4">Anda login sebagai admin. Berikut adalah ringkasan data dari sistem absensi sekolah.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="140" alt="Man with laptop" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistic Cards --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/user-check.png') }}" alt="Siswa" class="rounded" />
                        </div>
                    </div>
                    <span>Jumlah Siswa</span>
                    <h4 class="card-title mb-1">{{ $studentCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/briefcase-alt.png') }}" alt="Guru" class="rounded" />
                        </div>
                    </div>
                    <span>Jumlah Guru</span>
                    <h4 class="card-title mb-1">{{ $teacherCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/building.png') }}" alt="Kelas" class="rounded" />
                        </div>
                    </div>
                    <span>Jumlah Kelas</span>
                    <h4 class="card-title mb-1">{{ $classCount }}</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/calendar-alt.png') }}" alt="Tahun Ajaran" class="rounded" />
                        </div>
                    </div>
                    <span>Tahun Ajaran Aktif</span>
                    <h4 class="card-title text-primary mb-1">{{ $activeAcademicYear->year ?? 'Belum Diatur' }}</h4>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        {{-- Attendance Chart --}}
        <div class="col-md-6 col-12 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Ringkasan Absensi Hari Ini</h5>
                    <small class="text-muted">{{ \Carbon\Carbon::now()->format('d F Y') }}</small>
                </div>
                <div class="card-body">
                    @if(array_sum($attendanceChartData['series']) > 0)
                        <div id="attendanceTodayChart"></div>
                    @else
                        <div class="text-center p-5">
                            <p>Belum ada data absensi untuk hari ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Leave Requests --}}
        <div class="col-md-6 col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0 me-2">Pengajuan Izin Terbaru</h5>
                </div>
                <div class="card-body">
                     <ul class="p-0 m-0">
                        @forelse ($recentLeaveRequests as $leave)
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-envelope"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">{{ $leave->student->full_name }}</h6>
                                    <small class="text-muted d-block">
                                        Kelas {{ $leave->student->class->name ?? '' }} | {{ Str::ucfirst($leave->type) }}
                                    </small>
                                </div>
                                <div class="user-progress">
                                    @if ($leave->status == 'pending')
                                        <span class="badge bg-label-warning">{{ Str::ucfirst($leave->status) }}</span>
                                    @elseif ($leave->status == 'approved')
                                        <span class="badge bg-label-success">{{ Str::ucfirst($leave->status) }}</span>
                                    @else
                                        <span class="badge bg-label-danger">{{ Str::ucfirst($leave->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center">Tidak ada pengajuan izin terbaru.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library ApexCharts --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hanya render jika ada data
    if (@json(array_sum($attendanceChartData['series'])) > 0) {
        var options = {
            series: @json($attendanceChartData['series']),
            chart: {
                type: 'donut',
                height: 380,
            },
            labels: @json($attendanceChartData['labels']),
            colors: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'], // Hadir, Sakit, Izin, Alpa
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Siswa',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => { return a + b }, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val, opts) {
                    return opts.w.config.labels[opts.seriesIndex]
                },
            },
            legend: {
                position: 'bottom'
            },
        };

        var chart = new ApexCharts(document.querySelector("#attendanceTodayChart"), options);
        chart.render();
    }
});
</script>
@endpush