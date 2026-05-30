$(document).ready(function () {
    // 1. AJAX Setup for CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 2. Open Modal for Add
    $("#add-employee-btn").on('click', function () {
        $('#employeeForm')[0].reset();
        $('#employee_id_pk').val('');
        $('#employeeModalLabel').text('Add New Employee');
        $('#employeeForm').find('input, select').prop('disabled', false);
        $('#employeeForm').find('button[type="submit"]').show();
        $('.current-avatar-preview').addClass('d-none');
        $('.dynamic-option').remove();
        $('#user_id').val('');
        clearValidationErrors();
    });

    // 3. View Employee Details
    $(document).on('click', '.view-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/employees/' + id,
            method: 'GET',
            success: function (employee) {
                populateModal(employee);
                $('#employeeModalLabel').text('View Employee Details');
                $('#employeeForm').find('input, select').prop('disabled', true);
                $('#employeeForm').find('button[type="submit"]').hide();
                clearValidationErrors();
                $('#employeeModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch employee details.'
                });
            }
        });
    });

    // 4. Edit Employee Details
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/employees/' + id,
            method: 'GET',
            success: function (employee) {
                populateModal(employee);
                $('#employeeModalLabel').text('Edit Employee');
                $('#employeeForm').find('input, select').prop('disabled', false);
                $('#employeeForm').find('button[type="submit"]').show();
                clearValidationErrors();
                $('#employeeModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch employee details.'
                });
            }
        });
    });

    // 5. Submit Form (Create & Update)
    $('#employeeForm').on('submit', function (e) {
        e.preventDefault();
        clearValidationErrors();

        var id = $('#employee_id_pk').val();
        var isEdit = id ? true : false;
        var url = isEdit ? '/employees/' + id : '/employees';

        var formData = new FormData(this);
        if (isEdit) {
            // Enable disabled fields so they are submitted in FormData (like user_id)
            $('#employeeForm').find(':disabled').each(function () {
                formData.append($(this).attr('name'), $(this).val());
            });
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST', // Use POST for FormData support with file uploads
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#employeeModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    fetchEmployees(window.location.href);
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
                    var errMsg = 'An error occurred while saving the employee.';
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

    // 6. Delete Employee
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
                    url: '/employees/' + id,
                    method: 'DELETE',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            fetchEmployees(window.location.href);
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete employee.'
                        });
                    }
                });
            }
        });
    });

    // Helper: Populate Modal Fields
    function populateModal(employee) {
        $('#employee_id_pk').val(employee.id);
        $('#name').val(employee.name);
        $('#email').val(employee.email);
        $('#employee_id').val(employee.employee_id);
        $('#phone').val(employee.phone);
        $('#position').val(employee.position);
        $('#department').val(employee.department);

        var joinDate = employee.join_date ? employee.join_date.split('T')[0] : '';
        $('#join_date').val(joinDate);
        $('#status').val(employee.status);

        // Populate user dropdown
        if (employee.user) {
            // Check if user option already exists, if not add it dynamically
            if ($('#user_id option[value="' + employee.user_id + '"]').length === 0) {
                $('#user_id').append(
                    $('<option>', {
                        value: employee.user_id,
                        text: employee.user.name + ' (' + employee.user.email + ')',
                        class: 'dynamic-option'
                    })
                );
            }
            $('#user_id').val(employee.user_id);
        } else {
            $('#user_id').val('');
        }

        if (employee.profile_photo) {
            $('.current-avatar-preview').removeClass('d-none');
            $('#avatar_preview_img').attr('src', '/storage/' + employee.profile_photo);
        } else {
            $('.current-avatar-preview').addClass('d-none');
        }
    }

    // Helper: Clear validation errors
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.dynamic-error').remove();
    }

    // Helper: AJAX Table Fetching with state preservation
    function fetchEmployees(url, pushToHistory = true) {
        var wrapper = $('#employees-table-wrapper');
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
                    text: 'Failed to fetch employees.'
                });
            }
        });
    }

    // Intercept click on sorting headers and pagination links
    $(document).on('click', '#employees-table-wrapper .sort-link, #employees-table-wrapper .pagination-container a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            fetchEmployees(url);
        }
    });

    // Listen to history navigation (back/forward)
    $(window).on('popstate', function () {
        fetchEmployees(window.location.href, false);
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
                fetchEmployees(currentUrl.toString());
            }, 1000); // 1000ms debounce
        });
    }
});
