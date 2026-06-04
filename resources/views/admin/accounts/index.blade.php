@extends('layouts.master')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-icons.css') }}" />
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">Accounts Management /</span> Admin Accounts
        </h4>

        <div class="card">
            <div class="d-flex justify-content-between align-items-center p-4">
                <form action="{{ route('accounts') }}" method="GET" class="d-flex align-items-center gap-2">
                    <input class="form-control" name="search" type="search" placeholder="Search admin accounts..."
                        value="{{ request('search') }}" style="width: 250px;" autocomplete="off" />
                </form>
                <button class="btn btn-primary" id="add-account-btn" data-bs-toggle="modal" data-bs-target="#accountModal"
                    type="button">Add Admin</button>
            </div>
            <div id="accounts-table-wrapper">
                @include('admin.accounts.table')
            </div>
        </div>
    </div>

    @include('admin.accounts.account-modal')
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/account.js') }}?v={{ filemtime(public_path('assets/js/account.js')) }}"></script>
@endpush
