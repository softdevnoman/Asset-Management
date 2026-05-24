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
            <div id="assets-table-wrapper">
                @include('admin.assets.table')
            </div>
        </div>
    </div>

    @include('admin.assets.asset-modal')
@endsection

@push('scripts')
    @vite('resources/js/asset.js')
@endpush
