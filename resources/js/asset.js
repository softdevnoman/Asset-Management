$(document).ready(function () {
    // 1. AJAX Setup for CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 2. Load assets on page load
    loadAssets();

    // 3. Helper: Map condition to badge class
    function getBadgeClass(condition) {
        switch (condition) {
            case 'Excellent':
                return 'bg-label-success';
            case 'Good':
                return 'bg-label-info';
            case 'Fair':
                return 'bg-label-warning';
            case 'Poor':
                return 'bg-label-danger';
            case 'Under Repair':
                return 'bg-label-secondary';
            default:
                return 'bg-label-primary';
        }
    }

    // 4. Load Assets Function
    function loadAssets() {
        $.ajax({
            url: '/manage-assets',
            method: 'GET',
            dataType: 'json',
            success: function (assets) {
                var tbody = $('#asset-table-body');
                tbody.empty();

                if (assets.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="10" class="text-center p-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="icon-base ti tabler-box-off text-muted mb-2" style="font-size: 2.5rem;"></i>
                                    <h6 class="text-muted mb-1">No assets found</h6>
                                    <span class="text-muted small">Click "Add Asset" to get started.</span>
                                </div>
                            </td>
                        </tr>
                    `);
                    return;
                }

                assets.forEach(function (asset) {
                    var badgeClass = getBadgeClass(asset.condition);
                    var price = asset.purchase_price ? '$' + parseFloat(asset.purchase_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                    var purchaseDate = asset.purchase_date ? asset.purchase_date : '-';
                    var warrantyExpiry = asset.warranty_expiry ? asset.warranty_expiry.split('T')[0] : '-';
                    var maintenanceDate = asset.maintenance_date ? asset.maintenance_date.split('T')[0] : '-';
                    var notes = asset.notes ? asset.notes : '';

                    var row = `
                        <tr>
                            <td><span class="fw-medium">${asset.asset_code}</span></td>
                            <td>${asset.name}</td>
                            <td>${asset.serial_number || ''}</td>
                            <td>${price}</td>
                            <td>${purchaseDate}</td>
                            <td><span class="badge ${badgeClass}">${asset.condition || '-'}</span></td>
                            <td>${warrantyExpiry}</td>
                            <td>${maintenanceDate}</td>
                            <td class="text-truncate" style="max-width: 150px;" title="${notes}">${notes}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base ti tabler-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item edit-btn" href="javascript:void(0);" data-id="${asset.id}">
                                            <i class="icon-base ti tabler-pencil me-1"></i>Edit
                                        </a>
                                        <a class="dropdown-item delete-btn" href="javascript:void(0);" data-id="${asset.id}">
                                            <i class="icon-base ti tabler-trash me-1"></i>Delete
                                        </a>
                                        <a class="dropdown-item view-btn" href="javascript:void(0);" data-id="${asset.id}">
                                            <i class="icon-base ti tabler-eye me-1"></i>View
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function () {
                var tbody = $('#asset-table-body');
                tbody.empty();
                tbody.append(`
                    <tr>
                        <td colspan="10" class="text-center p-5">
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <i class="icon-base ti tabler-alert-triangle text-danger mb-2" style="font-size: 2.5rem;"></i>
                                <h6 class="text-danger mb-1">Error loading assets</h6>
                                <span class="text-muted small">Could not retrieve assets from the server.</span>
                            </div>
                        </td>
                    </tr>
                `);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load assets.'
                });
            }
        });
    }

    // 5. Open Modal for Add
    $('#add-asset-btn').on('click', function () {
        $('#assetForm')[0].reset();
        $('#asset_id').val('');
        $('#assetModalLabel').text('Add New Asset');
        $('#assetForm').find('input, select, textarea').prop('disabled', false);
        $('#assetForm').find('button[type="submit"]').show();
        clearValidationErrors();
    });

    // 6. View Asset Details
    $(document).on('click', '.view-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/manage-assets/' + id,
            method: 'GET',
            success: function (asset) {
                populateModal(asset);
                $('#assetModalLabel').text('View Asset Details');
                $('#assetForm').find('input, select, textarea').prop('disabled', true);
                $('#assetForm').find('button[type="submit"]').hide();
                clearValidationErrors();
                $('#assetModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch asset details.'
                });
            }
        });
    });

    // 7. Edit Asset Details
    $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        $.ajax({
            url: '/manage-assets/' + id,
            method: 'GET',
            success: function (asset) {
                populateModal(asset);
                $('#assetModalLabel').text('Edit Asset');
                $('#assetForm').find('input, select, textarea').prop('disabled', false);
                $('#assetForm').find('button[type="submit"]').show();
                clearValidationErrors();
                $('#assetModal').modal('show');
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch asset details.'
                });
            }
        });
    });

    // 8. Submit Form (Create & Update)
    $('#assetForm').on('submit', function (e) {
        e.preventDefault();
        clearValidationErrors();

        var id = $('#asset_id').val();
        var isEdit = id ? true : false;
        var url = isEdit ? '/manage-assets/' + id : '/manage-assets';
        var method = isEdit ? 'PUT' : 'POST';

        // Get form data
        var formData = {
            asset_code: $('#asset_code').val(),
            name: $('#name').val(),
            serial_number: $('#serial_number').val(),
            purchase_price: $('#purchase_price').val(),
            purchase_date: $('#purchase_date').val(),
            condition: $('#condition').val(),
            warranty_expiry: $('#warranty_expiry').val(),
            maintenance_date: $('#maintenance_date').val(),
            notes: $('#notes').val()
        };

        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function (response) {
                $('#assetModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                loadAssets();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        var field = $('#' + key);
                        field.addClass('is-invalid');
                        // Add error message text
                        field.after(`<div class="invalid-feedback dynamic-error">${messages[0]}</div>`);
                    });
                } else {
                    var errMsg = 'An error occurred while saving the asset.';
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

    // 9. Delete Asset
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
                    url: '/manage-assets/' + id,
                    method: 'DELETE',
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        loadAssets();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete asset.'
                        });
                    }
                });
            }
        });
    });

    // Helper: Populate Modal Fields
    function populateModal(asset) {
        $('#asset_id').val(asset.id);
        $('#asset_code').val(asset.asset_code);
        $('#name').val(asset.name);
        $('#serial_number').val(asset.serial_number);
        $('#purchase_price').val(asset.purchase_price);
        $('#purchase_date').val(asset.purchase_date);
        $('#condition').val(asset.condition);

        var warrantyDate = asset.warranty_expiry ? asset.warranty_expiry.split('T')[0] : '';
        $('#warranty_expiry').val(warrantyDate);

        var maintDate = asset.maintenance_date ? asset.maintenance_date.split('T')[0] : '';
        $('#maintenance_date').val(maintDate);

        $('#notes').val(asset.notes);
    }

    // Helper: Clear validation errors
    function clearValidationErrors() {
        $('.is-invalid').removeClass('is-invalid');
        $('.dynamic-error').remove();
    }
});
