@extends('layouts.master')
@section('page-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-icons.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Asset</span> Management
    </h4>

    <div class="card">
        <div class="text-end p-5">
            <button class="btn btn-primary" type="button">Add Asset</button>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
            <thead>
                <tr>
                    <th>Asset Code</th>
                    <th>Name</th>
                    <th>Serial Number</th>
                    <th>Purchase Price</th>
                    <th>Purchased Date</th>
                    <th>Condition</th>
                    <th>Warranty Expiry</th>
                    <th>Maintenance Date</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="fw-medium">AST-001</span></td>
                    <td>Dell XPS Laptop</td>
                    <td>SN123456789</td>
                    <td>$1,200.00</td>
                    <td>2023-11-20</td>
                    <td><span class="badge bg-label-success">Good</span></td>
                    <td>2025-12-31</td>
                    <td>2024-06-15</td>
                    <td>Regular maintenance done</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="fw-medium">AST-002</span></td>
                    <td>HP Monitor</td>
                    <td>SN987654321</td>
                    <td>$350.00</td>
                    <td>2022-05-10</td>
                    <td><span class="badge bg-label-warning">Fair</span></td>
                    <td>2024-08-15</td>
                    <td>2024-07-10</td>
                    <td>Screen flickering issue</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="fw-medium">AST-003</span></td>
                    <td>MacBook Pro</td>
                    <td>SN456789123</td>
                    <td>$2,500.00</td>
                    <td>2023-03-15</td>
                    <td><span class="badge bg-label-success">Excellent</span></td>
                    <td>2026-03-20</td>
                    <td>2024-08-01</td>
                    <td>Under Apple Care+</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="fw-medium">AST-004</span></td>
                    <td>Office Chair</td>
                    <td>SN321654987</td>
                    <td>$450.00</td>
                    <td>2021-08-22</td>
                    <td><span class="badge bg-label-danger">Poor</span></td>
                    <td>2024-01-10</td>
                    <td>2024-06-20</td>
                    <td>Needs replacement</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="fw-medium">AST-005</span></td>
                    <td>Projector Epson</td>
                    <td>SN147852369</td>
                    <td>$800.00</td>
                    <td>2022-11-10</td>
                    <td><span class="badge bg-label-info">Good</span></td>
                    <td>2024-11-30</td>
                    <td>2024-07-25</td>
                    <td>Bulb replaced recently</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ti tabler-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-trash me-1"></i>Delete
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);">
                                    <i class="icon-base ti tabler-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
