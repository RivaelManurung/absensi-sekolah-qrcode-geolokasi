<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        {{-- Tautan brand logo mengarah ke dashboard sesuai role --}}
        @php
            $homeRoute = 'login'; // Default route jika tidak ada role
            if (Auth::check()) {
                $role = Auth::user()->role;
                if ($role === 'admin') {
                    $homeRoute = 'admin.dashboard';
                } elseif ($role === 'guru') {
                    $homeRoute = 'guru.schedules.index';
                } elseif ($role === 'siswa') {
                    $homeRoute = 'siswa.schedules.index';
                }
            }
        @endphp
        <a href="{{ route($homeRoute) }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo" width="30" />
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Absensi</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
        </a>
    </div>
    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- =============================================== --}}
        {{-- |                 MENU UNTUK ADMIN            | --}}
        {{-- =============================================== --}}
        @if (Auth::user()->role === 'admin')
            <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-smile"></i>
                    <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
                </a>
            </li>

            {{-- Manajemen Utama --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Utama</span>
            </li>
            <li class="menu-item {{ Request::is('admin/schedules*') ? 'active' : '' }}">
                <a href="{{ route('admin.schedules.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div class="text-truncate" data-i18n="Schedules">Jadwal Pelajaran</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/attendances*') ? 'active' : '' }}">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-check-square"></i>
                    <div class="text-truncate" data-i18n="Attendances">Laporan Absensi</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/leave-requests*') ? 'active' : '' }}">
                <a href="{{ route('admin.leave-requests.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-envelope"></i>
                    <div class="text-truncate" data-i18n="Leave Requests">Pengajuan Izin</div>
                </a>
            </li>

            {{-- Data Master --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Data Master</span>
            </li>
            <li class="menu-item {{ Request::is('admin/students*') ? 'active' : '' }}">
                <a href="{{ route('admin.students.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-badge"></i>
                    <div class="text-truncate" data-i18n="Students">Data Siswa</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/teachers*') ? 'active' : '' }}">
                <a href="{{ route('admin.teachers.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-briefcase-alt"></i>
                    <div class="text-truncate" data-i18n="Teachers">Data Guru</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/classes*') ? 'active' : '' }}">
                <a href="{{ route('admin.classes.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div class="text-truncate" data-i18n="Classes">Data Kelas</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/subjects*') ? 'active' : '' }}">
                <a href="{{ route('admin.subjects.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-book"></i>
                    <div class="text-truncate" data-i18n="Subjects">Data Mapel</div>
                </a>
            </li>
            <li class="menu-item {{ Request::is('admin/academic-years*') ? 'active' : '' }}">
                <a href="{{ route('admin.academic-years.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                    <div class="text-truncate" data-i18n="Academic Years">Tahun Ajaran</div>
                </a>
            </li>

        {{-- =============================================== --}}
        {{-- |                  MENU UNTUK GURU            | --}}
        {{-- =============================================== --}}
        @elseif (Auth::user()->role === 'guru')
            <li class="menu-item {{ Request::routeIs('guru.schedules.index') ? 'active' : '' }}">
                <a href="{{ route('guru.schedules.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                    <div class="text-truncate" data-i18n="Schedules">Jadwal Mengajar</div>
                </a>
            </li>
            {{-- Tambahkan menu lain untuk guru jika ada, contoh: --}}
            <li class="menu-item {{-- Request::is('guru/attendances/report*') ? 'active' : '' --}}">
                <a href="#" class="menu-link"> {{-- Arahkan ke route laporan absensi guru --}}
                    <i class="menu-icon tf-icons bx bx-check-square"></i>
                    <div class="text-truncate" data-i18n="Attendance Report">Laporan Absensi</div>
                </a>
            </li>

        {{-- =============================================== --}}
        {{-- |                  MENU UNTUK SISWA           | --}}
        {{-- =============================================== --}}
        @elseif (Auth::user()->role === 'siswa')
            <li class="menu-item {{ Request::routeIs('siswa.schedules.index') ? 'active' : '' }}">
                <a href="{{ route('siswa.schedules.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-week"></i>
                    <div class="text-truncate" data-i18n="Schedules">Jadwal Pelajaran</div>
                </a>
            </li>
            {{-- Tambahkan menu lain untuk siswa jika ada, contoh: --}}
            <li class="menu-item {{-- Request::is('siswa/leave-request*') ? 'active' : '' --}}">
                <a href="#" class="menu-link"> {{-- Arahkan ke route pengajuan izin siswa --}}
                    <i class="menu-icon tf-icons bx bx-envelope"></i>
                    <div class="text-truncate" data-i18n="Leave Request">Ajukan Izin</div>
                </a>
            </li>
        @endif
    </ul>
</aside>