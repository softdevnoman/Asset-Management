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
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="d-flex align-items-center gap-2">
                    <input class="form-control" type="search" placeholder="Search assets..." id="assetSearch" style="width: 250px;" />
                </div>
                <button class="btn btn-primary" id="add-asset-btn" data-bs-toggle="modal" data-bs-target="#assetModal"
                    type="button">Add Asset</button>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="sortable text-nowrap cursor-pointer" data-column="asset_code">Asset Code <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="name">Name <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="serial_number">Serial Number <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="purchased_price">Purchase Price <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="purchased_date">Purchased Date <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="condition">Condition <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="warranty_expiry">Warranty Expiry <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th class="sortable text-nowrap cursor-pointer" data-column="maintenance_date">Maintenance Date <i class="icon-base ti tabler-selector ms-1"></i></th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="asset-table-body">
                        <!-- Loaded dynamically via AJAX -->
                        <tr id="no-assets-row">
                            <td colspan="10" class="text-center p-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="icon-base ti tabler-box-off text-muted mb-2" style="font-size: 2.5rem;"></i>
                                    <h6 class="text-muted mb-1">No assets found</h6>
                                    <span class="text-muted small">Click "Add Asset" to get started.</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('assets.asset-modal')
@endsection

@push('scripts')
    @vite('resources/js/asset.js')
@endpush
