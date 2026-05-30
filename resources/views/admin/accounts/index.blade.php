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
    {{-- Placeholders for future accounts JS interactions --}}
    <script>
        $(document).ready(function() {
            // Setup clear/reset of forms on modal close
            $('#accountModal').on('hidden.bs.modal', function() {
                $('#accountForm')[0].reset();
                $('#account_id_pk').val('');
                $('#accountModalLabel').text('Add New Admin');
                
                // Reset password fields to required
                $('#password, #password_confirmation').prop('required', true);
                $('#password-asterisk, #confirm-password-asterisk').removeClass('d-none');
                $('.id-note').addClass('d-none');
            });

            // Handle edit buttons (static click handler for now)
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                $('#accountModalLabel').text('Edit Admin Account');
                
                // Passwords are optional on edit, so toggle attributes
                $('#password, #password_confirmation').prop('required', false);
                $('#password-asterisk, #confirm-password-asterisk').addClass('d-none');
                $('.id-note').removeClass('d-none');
                
                // Show modal
                $('#accountModal').modal('show');
            });
        });
    </script>
@endpush
