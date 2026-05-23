@extends('layouts.master')
@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-icons.css') }}" />
@endsection
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Asset</span> Management
        </h4>

        <div class="card">
            <div class="d-flex justify-content-between align-items-center p-4">
                <form action="{{ route('assets') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input class="form-control" name="search" type="search" placeholder="Search assets..." value="{{ request('search') }}" style="width: 250px;" autocomplete="off" />
                </form>
                <button class="btn btn-primary" id="add-asset-btn" data-bs-toggle="modal" data-bs-target="#assetModal"
                    type="button">Add Asset</button>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        @php
                            function getSortUrl($column) {
                                $currentSortDir = request('sort_dir', 'desc');
                                $newSortDir = (request('sort_by') === $column && $currentSortDir === 'asc') ? 'desc' : 'asc';
                                return request()->fullUrlWithQuery(['sort_by' => $column, 'sort_dir' => $newSortDir]);
                            }

                            function getSortIcon($column) {
                                if (request('sort_by') !== $column) {
                                    return 'tabler-selector';
                                }
                                return request('sort_dir') === 'asc' ? 'tabler-chevron-up' : 'tabler-chevron-down';
                            }
                        @endphp
                        <tr>
                            <th><a href="{{ getSortUrl('asset_code') }}" class="text-body d-flex align-items-center">Asset Code <i class="icon-base ti {{ getSortIcon('asset_code') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('name') }}" class="text-body d-flex align-items-center">Name <i class="icon-base ti {{ getSortIcon('name') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('serial_number') }}" class="text-body d-flex align-items-center">Serial Number <i class="icon-base ti {{ getSortIcon('serial_number') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('purchased_price') }}" class="text-body d-flex align-items-center">Purchase Price <i class="icon-base ti {{ getSortIcon('purchased_price') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('purchased_date') }}" class="text-body d-flex align-items-center">Purchased Date <i class="icon-base ti {{ getSortIcon('purchased_date') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('condition') }}" class="text-body d-flex align-items-center">Condition <i class="icon-base ti {{ getSortIcon('condition') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('warranty_expiry') }}" class="text-body d-flex align-items-center">Warranty Expiry <i class="icon-base ti {{ getSortIcon('warranty_expiry') }} ms-1"></i></a></th>
                            <th><a href="{{ getSortUrl('maintenance_date') }}" class="text-body d-flex align-items-center">Maintenance Date <i class="icon-base ti {{ getSortIcon('maintenance_date') }} ms-1"></i></a></th>
                            <th>Notes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="asset-table-body">
                        @forelse($assets as $asset)
                            @php
                                $badgeClass = match ($asset->condition) {
                                    'Excellent' => 'bg-label-success',
                                    'Good' => 'bg-label-info',
                                    'Fair' => 'bg-label-warning',
                                    'Poor' => 'bg-label-danger',
                                    'Under Repair' => 'bg-label-secondary',
                                    default => 'bg-label-primary',
                                };
                            @endphp
                            <tr>
                                <td><span class="fw-medium">{{ $asset->asset_code }}</span></td>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->serial_number ?? '' }}</td>
                                <td>
                                    {{ $asset->purchased_price ? '$' . number_format($asset->purchased_price, 2) : '-' }}
                                </td>
                                <td>{{ $asset->purchased_date ?? '-' }}</td>
                                <td><span class="badge {{ $badgeClass }}">{{ $asset->condition ?? '-' }}</span></td>
                                <td>{{ $asset->warranty_expiry ? \Carbon\Carbon::parse($asset->warranty_expiry)->format('Y-m-d') : '-' }}</td>
                                <td>{{ $asset->maintenance_date ? \Carbon\Carbon::parse($asset->maintenance_date)->format('Y-m-d') : '-' }}</td>
                                <td class="text-truncate" style="max-width: 150px;" title="{{ $asset->notes }}">{{ $asset->notes ?? '' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <i class="icon-base ti tabler-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item edit-btn" href="javascript:void(0);" data-id="{{ $asset->id }}">
                                                <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                            </a>
                                            <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="{{ $asset->id }}">
                                                <i class="icon-base ti tabler-trash me-1"></i>Delete
                                            </a>
                                            <a class="dropdown-item view-btn" href="javascript:void(0);" data-id="{{ $asset->id }}">
                                                <i class="icon-base ti tabler-eye me-1"></i>View
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-assets-row">
                                <td colspan="10" class="text-center p-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <i class="icon-base ti tabler-box-off text-muted mb-2" style="font-size: 2.5rem;"></i>
                                        <h6 class="text-muted mb-1">No assets found</h6>
                                        <span class="text-muted small">Click "Add Asset" to get started.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($assets->hasPages())
                <div class="card-footer d-flex justify-content-between align-items-center border-top">
                    <div>
                        Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} entries
                    </div>
                    <div>
                        {{ $assets->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('admin.assets.asset-modal')
@endsection

@push('scripts')
    @vite('resources/js/asset.js')
@endpush
