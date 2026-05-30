@php
    // Fallback mock user accounts for preview if $accounts is not passed
    $accounts = $accounts ?? collect([
        (object)[
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@company.com',
            'role' => 'super_admin',
            'created_at' => now()->subMonths(6)->format('Y-m-d'),
        ],
        (object)[
            'id' => 2,
            'name' => 'Jane Smith',
            'email' => 'jane.smith@company.com',
            'role' => 'admin',
            'created_at' => now()->subMonths(3)->format('Y-m-d'),
        ],
        (object)[
            'id' => 3,
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@company.com',
            'role' => 'admin',
            'created_at' => now()->subDays(12)->format('Y-m-d'),
        ]
    ]);
@endphp

<div id="accounts-table-container">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="accounts-table-body">
                @forelse($accounts as $account)
                    @php
                        // Format roles with appropriate styling badges
                        $roleName = 'Admin';
                        $roleBadgeClass = 'bg-label-primary';
                        
                        if (strtolower($account->role ?? '') === 'super_admin' || strtolower($account->role ?? '') === 'super admin') {
                            $roleName = 'Super Admin';
                            $roleBadgeClass = 'bg-label-danger';
                        }
                    @endphp
                    <tr>
                        <td>
                            <div class="d-flex justify-content-start align-items-center gap-3">
                                <div class="avatar avatar-sm">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($account->name ?? 'User') }}&background=7367f0&color=fff"
                                        alt="Avatar" class="rounded-circle"
                                        style="width: 32px; height: 32px; object-fit: cover;">
                                </div>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium text-heading">{{ $account->name }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span>{{ $account->email }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $roleBadgeClass }}">{{ $roleName }}</span>
                        </td>
                        <td>
                            <span>{{ is_string($account->created_at) ? $account->created_at : $account->created_at->format('Y-m-d') }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="icon-base ti tabler-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item edit-btn" href="javascript:void(0);"
                                        data-id="{{ $account->id }}">
                                        <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                    </a>
                                    <a class="dropdown-item delete-btn" href="javascript:void(0);"
                                        data-id="{{ $account->id }}">
                                        <i class="icon-base ti tabler-trash me-1"></i>Delete
                                    </a>
                                    <a class="dropdown-item view-btn" href="javascript:void(0);"
                                        data-id="{{ $account->id }}">
                                        <i class="icon-base ti tabler-eye me-1"></i>View
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="no-accounts-row">
                        <td colspan="5" class="text-center p-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="icon-base ti tabler-user-off text-muted mb-2" style="font-size: 2.5rem;"></i>
                                <h6 class="text-muted mb-1">No admin accounts found</h6>
                                <span class="text-muted small">Click "Add Account" to create a new administrator account.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (method_exists($accounts, 'hasPages') && $accounts->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center border-top">
            <div>
                Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} entries
            </div>
            <div class="pagination-container">
                {{ $accounts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
