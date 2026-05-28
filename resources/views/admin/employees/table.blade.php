<div id="employees-table-container">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Employee ID</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Join Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employee-table-body">

                @foreach ($employees as $employee)
                    @php
                        $statusBadge = [
                            'active' => 'bg-label-success',
                            'on_leave' => 'bg-label-warning',
                            'inactive' => 'bg-label-secondary',
                        ];

                    @endphp
                    <tr>
                        <td>
                            <div class="avatar avatar-sm">
                                @if ($employee->profile_photo)
                                    <img src="{{ asset('storage/' . $employee->profile_photo) }}"
                                        alt="Avatar" class="rounded-circle"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name ?? 'Employee') }}&background=7367f0&color=fff"
                                        alt="Avatar" class="rounded-circle"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-medium text-heading">{{ $employee->name }}</span>
                                <small class="text-muted">{{ $employee->email }}</small>
                            </div>
                        </td>
                        <td><span class="fw-medium">{{ $employee->employee_id }}</span></td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->join_date }}</td>
                        <td><span
                                class="badge {{ $statusBadge[strtolower($employee->status)] ?? 'bg-label-secondary' }}">
                                {{ $employee->status }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-btn" href="javascript:void(0);"
                                        data-id="{{ $employee->id }}">
                                        <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                    </a>
                                    <a class="dropdown-item delete-btn" href="javascript:void(0);"
                                        data-id="{{ $employee->id }}">
                                        <i class="icon-base ti tabler-trash me-1"></i>Delete
                                    </a>
                                    <a class="dropdown-item view-btn" href="javascript:void(0);"
                                        data-id="{{ $employee->id }}">
                                        <i class="icon-base ti tabler-eye me-1"></i>View
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($employees->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center border-top">
            <div>
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
