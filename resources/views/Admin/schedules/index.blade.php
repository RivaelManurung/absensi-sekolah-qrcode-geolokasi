@extends('admin.layout.main')

@section('title', 'Manage Schedules')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Schedules</h5>
            <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Add Schedule
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Class</th>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @php
                        // Array untuk menerjemahkan nomor hari ke nama hari
                        $days = ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jumat', '6' => 'Sabtu'];
                    @endphp

                    {{-- PERBAIKAN UTAMA: Looping menggunakan variabel $schedules, bukan $schedulesByDay --}}
                    @forelse ($schedules as $schedule)
                    <tr>
                        <td><strong>{{ $days[$schedule->day_of_week] ?? 'N/A' }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->class->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->subject->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->teacher->full_name ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex">
                                <a class="btn btn-sm btn-outline-primary me-2" href="{{ route('admin.schedules.edit', $schedule->id) }}">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No schedules found.</td>
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