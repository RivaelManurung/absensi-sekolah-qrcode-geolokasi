@extends('admin.layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Academic Years</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAcademicYearModal">
                <i class="bx bx-plus me-1"></i> Add Year
            </button>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Year</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse ($academicYears as $year)
                    <tr>
                        <td>{{ $academicYears->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $year->year }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($year->start_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($year->end_date)->format('d M Y') }}</td>
                        <td>
                            @if ($year->status == 'active')
                                <span class="badge bg-label-success">Active</span>
                            @else
                                <span class="badge bg-label-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item edit-year" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editAcademicYearModal"
                                        data-id="{{ $year->id }}"
                                        data-year="{{ $year->year }}"
                                        data-start-date="{{ $year->start_date }}"
                                        data-end-date="{{ $year->end_date }}"
                                        data-status="{{ $year->status }}">
                                        <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.academic-years.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-base bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No academic years found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($academicYears->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $academicYears->links() }}
            </div>
        @endif
    </div>
</div>

@include('admin.academic-years.create-modal')
@include('admin.academic-years.edit-modal')
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-year').forEach(button => {
        button.addEventListener('click', function () {
            const modal = document.querySelector('#editAcademicYearModal');
            const form = modal.querySelector('form');
            
            form.action = `{{ url('admin/academic-years') }}/${this.dataset.id}`;
            
            modal.querySelector('#edit-year-year').value = this.dataset.year;
            modal.querySelector('#edit-year-start-date').value = this.dataset.startDate;
            modal.querySelector('#edit-year-end-date').value = this.dataset.endDate;
            modal.querySelector('#edit-year-status').value = this.dataset.status;
        });
    });
});
</script>
@endsection