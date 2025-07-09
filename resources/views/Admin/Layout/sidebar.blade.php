<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/Logo.png') }}" alt="Logo" width="30" /> {{-- Ganti dengan logo Anda --}}
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
        <li class="menu-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Manajemen Utama</span>
        </li>
        <li class="menu-item {{ Request::is('admin/schedules*') ? 'active' : '' }}">
            <a href="#" class="menu-link"> {{-- Ganti # dengan route jadwal jika sudah dibuat --}}
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div class="text-truncate" data-i18n="Schedules">Jadwal Pelajaran</div>
            </a>
        </li>
        <li class="menu-item {{ Request::is('admin/attendances*') ? 'active' : '' }}">
            <a href="#" class="menu-link"> {{-- Ganti # dengan route absensi jika sudah dibuat --}}
                <i class="menu-icon tf-icons bx bx-check-square"></i>
                <div class="text-truncate" data-i18n="Attendances">Data Absensi</div>
            </a>
        </li>

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
            <a href="#" class="menu-link"> {{-- Ganti # dengan route tahun ajaran jika sudah dibuat --}}
                <i class="menu-icon tf-icons bx bx-calendar-edit"></i>
                <div class="text-truncate" data-i18n="Academic Years">Tahun Ajaran</div>
            </a>
        </li>
    </ul>
</aside>