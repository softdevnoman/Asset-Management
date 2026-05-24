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
                <!-- Mock Row 1 -->
                <tr>
                    <td>
                        <div class="avatar avatar-sm">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=7367f0&color=fff" alt="Avatar" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-medium text-heading">John Doe</span>
                            <small class="text-muted">john.doe@example.com</small>
                        </div>
                    </td>
                    <td><span class="fw-medium">EMP-001</span></td>
                    <td>Software Engineer</td>
                    <td>Engineering</td>
                    <td>+1 (555) 019-2834</td>
                    <td>2024-01-15</td>
                    <td><span class="badge bg-label-success">Active</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item edit-btn" href="javascript:void(0);" data-id="1">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="1">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item view-btn" href="javascript:void(0);" data-id="1">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Mock Row 2 -->
                <tr>
                    <td>
                        <div class="avatar avatar-sm">
                            <img src="https://ui-avatars.com/api/?name=Jane+Smith&background=00cfe8&color=fff" alt="Avatar" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-medium text-heading">Jane Smith</span>
                            <small class="text-muted">jane.smith@example.com</small>
                        </div>
                    </td>
                    <td><span class="fw-medium">EMP-002</span></td>
                    <td>Product Manager</td>
                    <td>Product</td>
                    <td>+1 (555) 014-9382</td>
                    <td>2023-06-01</td>
                    <td><span class="badge bg-label-warning">On Leave</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item edit-btn" href="javascript:void(0);" data-id="2">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="2">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item view-btn" href="javascript:void(0);" data-id="2">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Mock Row 3 -->
                <tr>
                    <td>
                        <div class="avatar avatar-sm">
                            <img src="https://ui-avatars.com/api/?name=Robert+Johnson&background=ff9f43&color=fff" alt="Avatar" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-medium text-heading">Robert Johnson</span>
                            <small class="text-muted">robert.j@example.com</small>
                        </div>
                    </td>
                    <td><span class="fw-medium">EMP-003</span></td>
                    <td>HR Manager</td>
                    <td>Human Resources</td>
                    <td>+1 (555) 017-4839</td>
                    <td>2022-11-10</td>
                    <td><span class="badge bg-label-danger">Inactive</span></td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item edit-btn" href="javascript:void(0);" data-id="3">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="3">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item view-btn" href="javascript:void(0);" data-id="3">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Static Pagination Footer -->
    <div class="card-footer d-flex justify-content-between align-items-center border-top">
        <div>
            Showing 1 to 3 of 3 entries
        </div>
        <div class="pagination-container">
            <ul class="pagination mb-0">
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            </ul>
        </div>
    </div>
</div>
