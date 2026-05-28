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
                    location.reload();
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
                            location.reload();
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
        $('#employee_id').val(employee.employee_id);
        $('#phone').val(employee.phone);
        $('#position').val(employee.position);
        $('#department').val(employee.department);

        var joinDate = employee.join_date ? employee.join_date.split('T')[0] : '';
        $('#join_date').val(joinDate);
        $('#status').val(employee.status);

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
});
