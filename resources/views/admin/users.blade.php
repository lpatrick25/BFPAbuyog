@extends('layout.master')
@section('APP-TITLE')
    User Management
@endsection
@section('admin-user')
    active
@endsection
@section('APP-CONTENT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">User List</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="table1" data-toggle="data-bs-toggle" data-fixed-columns="true" data-fixed-number="1"
                            data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
                            data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
                            data-toolbar="" data-pagination="true" data-search="true" data-show-refresh="true"
                            data-show-copy-rows="true" data-show-columns="true" data-url="">
                        </table>
                    </div>
                    <div class="card-footer text-end">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="updatePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="updatePasswordLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form id="updateForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordLabel">Update Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"
                            autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Repeat Password " autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        let userID;

        // Redirect to the edit page with a session token
        function editUser(userId) {
            userID = userId;

            // Clear the input fields to prevent leftover data
            $('#password').val('');
            $('#password_confirmation').val('');
            // Remove previous error messages and invalid classes
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $('#updatePassword').modal('show');
        }

        function deleteClient(userId) {
            $.ajax({
                method: 'DELETE',
                url: `/clients/${userId}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    $('#table1').bootstrapTable('refresh');
                    showToast('success', response.message);
                },
                error: function(xhr) {
                    showToast('danger', xhr.responseJSON.message || 'Something went wrong.');
                }
            });
        }

        $(document).ready(function() {

            $('#add-btn').click(function(event) {
                event.preventDefault();
                window.location.href = '{{ route('client.add') }}';
            });

            $('#updateForm').submit(function(event) {
                event.preventDefault();

                let submitBtn = $('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');

                // Remove previous error messages and invalid classes
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    method: 'PUT',
                    url: `/users/${userID}`,
                    data: $(this).serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        $('#updatePassword').modal('hide');
                        showToast('success', response.message);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {
                                var inputElement = $('[name="' + field + '"]');

                                if (inputElement.length > 0) {
                                    // Add 'is-invalid' class to highlight error
                                    inputElement.addClass('is-invalid');

                                    // Create the error message div
                                    var errorContainer = $(
                                        '<div class="invalid-feedback"></div>');
                                    errorContainer.html(messages.join('<br>'));

                                    // Append error message after the input field
                                    inputElement.after(errorContainer);
                                }

                                // Remove error on input change
                                inputElement.on('input', function() {
                                    $(this).removeClass('is-invalid');
                                    $(this).next('.invalid-feedback').remove();
                                });
                            });

                            showToast('danger', 'Please check the form for errors.');

                        } else {
                            // Handle non-validation errors
                            showToast('danger', xhr.responseJSON.message ||
                                'Something went wrong.');
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Save');
                    }
                });
            });

            var $table1 = $('#table1');

            $table1.bootstrapTable({
                url: '/users', // Laravel API endpoint
                method: 'GET',
                pagination: true,
                sidePagination: 'server', // Enable server-side pagination
                pageSize: 10, // Default records per page
                pageList: [5, 10, 25, 50, 100], // Page size options
                search: true, // Enable search
                buttonsAlign: 'left',
                searchAlign: 'left',
                toolbarAlign: 'right',
                queryParams: function(params) {
                    return {
                        limit: params.limit, // Number of records per page
                        page: params.offset / params.limit + 1 // Page number
                    };
                },
                responseHandler: function(res) {
                    return {
                        total: res.total, // Set total count
                        rows: res.rows // Set data rows
                    };
                },
                columns: [{
                        field: 'id',
                        title: 'ID'
                    },
                    {
                        field: 'full_name',
                        title: 'Name',
                        formatter: function(value, row, index) {
                            // Extract the correct name based on the role
                            let person = row.client || row.inspector || row.marshall;

                            if (person) {
                                let middleName = person.middle_name ? ` ${person.middle_name}` : '';
                                let extensionName = person.extension_name ?
                                    ` ${person.extension_name}` : '';
                                return `${person.first_name}${middleName} ${person.last_name}${extensionName}`;
                            }
                            return 'N/A'; // If no related record exists
                        }
                    },
                    {
                        field: 'contact_number',
                        title: 'Contact Number',
                        formatter: function(value, row, index) {
                            let person = row.client || row.inspector || row.marshall;
                            return person ? person.contact_number : 'N/A';
                        }
                    },
                    {
                        field: 'email',
                        title: 'Email'
                    },
                    {
                        field: 'role',
                        title: 'Role'
                    },
                    {
                        field: 'action',
                        title: 'Actions',
                        formatter: actionFormatter
                    }
                ]
            });

            // Format the "Actions" column with Bootstrap Icons
            function actionFormatter(value, row, index) {
                return `
                    <button class="btn btn-sm btn-primary" onclick="editUser('${row.id}')" title="Edit">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteClient('${row.id}')" title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
            }

        });
    </script>
@endsection
