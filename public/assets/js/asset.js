$(document).ready(function () {
    // 1. AJAX Setup for CSRF Token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 2. Open Modal for Add
    $('#add-asset-btn').on('click', function () {
        $('#assetForm')[0].reset();
        $('#asset_id').val('');
        $('#assetModalLabel').text('Add New Asset');
        $('#assetForm').find('input, select, textarea').prop('disabled', false);
        $('#assetForm').find('button[type="submit"]').show();
        clearValidationErrors();
    });

    // 3. View Asset Details
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

    // 4. Edit Asset Details
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

    // 5. Submit Form (Create & Update)
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
                }).then(() => {
                    fetchAssets(window.location.href);
                });
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

    // 6. Delete Asset
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
                        }).then(() => {
                            fetchAssets(window.location.href);
                        });
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

    // Helper: AJAX Table Fetching with state preservation
    function fetchAssets(url, pushToHistory = true) {
        var wrapper = $('#assets-table-wrapper');
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
                    text: 'Failed to fetch assets.'
                });
            }
        });
    }

    // Intercept click on sorting headers and pagination links
    $(document).on('click', '#assets-table-wrapper .sort-link, #assets-table-wrapper .pagination-container a', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        if (url) {
            fetchAssets(url);
        }
    });

    // Listen to history navigation (back/forward)
    $(window).on('popstate', function () {
        fetchAssets(window.location.href, false);
    });

    // Prevent default form submission for search
    $('input[name="search"]').closest('form').on('submit', function (e) {
        e.preventDefault();
    });

    // 7. Auto-search on input with Debounce and cursor restoration
    var searchInput = $('input[name="search"]');
    if (searchInput.length) {
        // Restore focus and cursor position at the end of input if search has a value
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
                // Reset page on search change to avoid empty pages
                currentUrl.searchParams.delete('page');
                fetchAssets(currentUrl.toString());
            }, 1000); // 1000ms debounce
        });
    }
});
