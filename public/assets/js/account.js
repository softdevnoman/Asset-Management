$(document).ready(function () {
    // 1. AJAX Setup for CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 2. Open Modal for Add
    $("#add-account-btn").on('click', function () {
        $('#accountForm')[0].reset();
        $('#account_id_pk').val('');
        $('#accountModalLabel').text('Add New Admin');
        $('#accountForm').find('input, select').prop('disabled', false);
        $('#accountForm').find('button[type="submit"]').show();
        
        // Passwords are required on add
        $('#password, #password_confirmation').prop('required', true);
        $('#password-asterisk, #confirm-password-asterisk').removeClass('d-none');
        $('.id-note').addClass('d-none');
        
        clearValidationErrors();
    });

    // 3. View Account Details
    $(document).on('click', '.view-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/accounts/' + id,
            method: 'GET',
            success: function (account) {
                populateModal(account);
                $('#accountModalLabel').text('View Admin Details');
                $('#accountForm').find('input, select').prop('disabled', true);
                $('#accountForm').find('button[type="submit"]').hide();
                clearValidationErrors();
                $('#accountModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch admin details.'
                });
            }
        });
    });

    // 4. Edit Account Details
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/accounts/' + id,
            method: 'GET',
            success: function (account) {
                populateModal(account);
                $('#accountModalLabel').text('Edit Admin Account');
                $('#accountForm').find('input, select').prop('disabled', false);
                $('#accountForm').find('button[type="submit"]').show();
                
                // Passwords are optional on edit
                $('#password, #password_confirmation').prop('required', false);
                $('#password-asterisk, #confirm-password-asterisk').addClass('d-none');
                $('.id-note').removeClass('d-none');
                
                clearValidationErrors();
                $('#accountModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch admin details.'
                });
            }
        });
    });

    // 5. Submit Form (Create & Update)
    $('#accountForm').on('submit', function (e) {
        e.preventDefault();
        clearValidationErrors();

        var id = $('#account_id_pk').val();
        var isEdit = id ? true : false;
        var url = isEdit ? '/accounts/' + id : '/accounts';

        var formData = new FormData(this);
        if (isEdit) {
            // Enable disabled fields so they are submitted in FormData
            $('#accountForm').find(':disabled').each(function () {
                formData.append($(this).attr('name'), $(this).val());
            });
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST', // Use POST for FormData support with PUT emulation
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#accountModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    fetchAccounts(window.location.href);
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        var field = $('#' + key);
                        field.addClass('is-invalid');
                        field.after(`<div class="invalid-feedback dynamic-error">${messages[0]}</div>`);
                    });
                } else {
                    var errMsg = 'An error occurred while saving the account.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        errMsg = xhr.responseJSON.error;
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errMsg
                    });
                }
            }
        });
    });

    // 6. Delete Account
    $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7367f0',
            cancelButtonColor: '#a8aaae',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/accounts/' + id,
                    method: 'DELETE',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            fetchAccounts(window.location.href);
                        });
                    },
                    error: function (xhr) {
                        var errMsg = 'Failed to delete account.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errMsg = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errMsg
                        });
                    }
                });
            }
        });
    });

    // Helper: Populate Modal Fields
    function populateModal(account) {
        $('#account_id_pk').val(account.id);
        $('#name').val(account.name);
        $('#email').val(account.email);
        $('#role').val(account.role);
        $('#password').val('');
        $('#password_confirmation').val('');
    }

    // Helper: Clear validation errors
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.dynamic-error').remove();
    }

    // Helper: AJAX Table Fetching with state preservation
    function fetchAccounts(url, pushToHistory = true) {
        var wrapper = $('#accounts-table-wrapper');
        wrapper.css({
            'opacity': 0.5,
            'position': 'relative'
        });
        
        // Dynamic loading spinner overlay centered inside the wrapper
        var spinnerHtml = `
            <div class="table-loading-overlay d-flex align-items-center justify-content-center" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.7); z-index: 99; min-height: 150px;">
                <div class="sk-circle-fade">
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                    <div class="sk-circle-fade-dot"></div>
                </div>
            </div>`;
        wrapper.append(spinnerHtml);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            success: function (response) {
                wrapper.html(response);
                wrapper.css('opacity', 1);
                if (pushToHistory) {
                    window.history.pushState({ url: url }, '', url);
                }
            },
            error: function () {
                wrapper.css('opacity', 1);
                wrapper.find('.table-loading-overlay').remove();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch accounts.'
                });
            }
        });
    }

    // Intercept click on sorting headers and pagination links
    $(document).on('click', '#accounts-table-wrapper .sort-link, #accounts-table-wrapper .pagination-container a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            fetchAccounts(url);
        }
    });

    // Listen to history navigation (back/forward)
    $(window).on('popstate', function () {
        fetchAccounts(window.location.href, false);
    });

    // Prevent default form submission for search
    $('input[name="search"]').closest('form').on('submit', function (e) {
        e.preventDefault();
    });

    // Auto-search on input with Debounce and cursor restoration
    var searchInput = $('input[name="search"]');
    if (searchInput.length) {
        if (searchInput.val()) {
            searchInput.focus();
            var valLength = searchInput.val().length;
            searchInput[0].setSelectionRange(valLength, valLength);
        }
 
        var searchTimeout;
        searchInput.on('input', function () {
            clearTimeout(searchTimeout);
            var val = $(this).val().trim();
            searchTimeout = setTimeout(function () {
                var currentUrl = new URL(window.location.href);
                if (val === '') {
                    currentUrl.searchParams.delete('search');
                } else {
                    currentUrl.searchParams.set('search', val);
                }
                currentUrl.searchParams.delete('page');
                fetchAccounts(currentUrl.toString());
            }, 1000); // 1000ms debounce
        });
    }
});
