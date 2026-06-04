@extends('layouts.master')

@section('content')
    @if (auth()->user()->isAdmin())
        <div class="container-fluid flex-grow-1 container-p-y">
            <h4 class="py-3 mb-4">
                <span class="text-muted fw-light">Dashboard /</span> Asset Overview
            </h4>

            <!-- Statistics Cards -->
            <div class="row gy-4 mb-4">
                <!-- Total Assets -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="ti tabler-box ti-md"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0">1,245</h4>
                            </div>
                            <p class="mb-1">Total Assets</p>
                            <p class="mb-0">
                                <span class="text-success fw-medium"><i class="ti tabler-arrow-up ti-xs"></i> +8.1%</span>
                                <small class="text-muted">than last month</small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Value -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="ti tabler-currency-dollar ti-md"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0">$128.5k</h4>
                            </div>
                            <p class="mb-1">Total Asset Value</p>
                            <p class="mb-0">
                                <span class="text-danger fw-medium"><i class="ti tabler-arrow-down ti-xs"></i> -1.2%</span>
                                <small class="text-muted">depreciation</small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Assigned Assets -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="ti tabler-user-check ti-md"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0">892</h4>
                            </div>
                            <p class="mb-1">Assigned Assets</p>
                            <p class="mb-0">
                                <span class="text-success fw-medium"><i class="ti tabler-arrow-up ti-xs"></i> +4.2%</span>
                                <small class="text-muted">deployment rate</small>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Needs Maintenance -->
                <div class="col-sm-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2 pb-1">
                                <div class="avatar me-2">
                                    <span class="avatar-initial rounded bg-label-danger"><i
                                            class="ti tabler-tool ti-md"></i></span>
                                </div>
                                <h4 class="ms-1 mb-0">24</h4>
                            </div>
                            <p class="mb-1">Needs Maintenance</p>
                            <p class="mb-0">
                                <span class="text-danger fw-medium">Action Required</span>
                                <small class="text-muted">within 30 days</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analytics & Recent Activity -->
            <div class="row">
                <!-- Asset Conditions Snapshot -->
                <div class="col-xl-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Asset Conditions</h5>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="conditionDropdown" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti tabler-dots-vertical ti-sm text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="conditionDropdown">
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Download Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <h2 class="mb-0">78%</h2>
                                    <small class="text-muted">Good Condition</small>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <h2 class="mb-0">15%</h2>
                                    <small class="text-muted">Fair Condition</small>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <h2 class="mb-0">7%</h2>
                                    <small class="text-muted">Needs Repair</small>
                                </div>
                            </div>
                            <!-- Static Progress Bar Visual -->
                            <div class="progress mt-4" style="height: 12px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 78%"
                                    aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 15%"
                                    aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 7%"
                                    aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            <ul class="p-0 m-0 mt-4 mb-4 list-unstyled">
                                <li class="d-flex mb-3">
                                    <span class="badge bg-label-success me-2 rounded-circle p-2"><i
                                            class="ti tabler-check ti-sm"></i></span>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Excellent / Good</h6>
                                            <small class="text-muted">Fully operational</small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">971</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex mb-3">
                                    <span class="badge bg-label-warning me-2 rounded-circle p-2"><i
                                            class="ti tabler-alert-circle ti-sm"></i></span>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Fair</h6>
                                            <small class="text-muted">Operational, signs of wear</small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">187</h6>
                                        </div>
                                    </div>
                                </li>
                                <li class="d-flex">
                                    <span class="badge bg-label-danger me-2 rounded-circle p-2"><i
                                            class="ti tabler-x ti-sm"></i></span>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <h6 class="mb-0">Poor / Broken</h6>
                                            <small class="text-muted">Needs repair or replacement</small>
                                        </div>
                                        <div class="user-progress">
                                            <h6 class="mb-0">87</h6>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Recent Assets Table -->
                <div class="col-xl-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recently Added Assets</h5>
                            <a href="javascript:void(0);" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Asset Code</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="fw-medium text-primary">AST-1042</span></td>
                                        <td>MacBook Pro 16"</td>
                                        <td>Electronics</td>
                                        <td><span class="badge bg-label-success">Deployed</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium text-primary">AST-1043</span></td>
                                        <td>Ergonomic Chair</td>
                                        <td>Furniture</td>
                                        <td><span class="badge bg-label-info">In Storage</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium text-primary">AST-1044</span></td>
                                        <td>Dell UltraSharp 27"</td>
                                        <td>Electronics</td>
                                        <td><span class="badge bg-label-success">Deployed</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium text-primary">AST-1045</span></td>
                                        <td>Cisco Router</td>
                                        <td>Networking</td>
                                        <td><span class="badge bg-label-warning">Maintenance</span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="fw-medium text-primary">AST-1046</span></td>
                                        <td>Conference Table</td>
                                        <td>Furniture</td>
                                        <td><span class="badge bg-label-success">Deployed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
