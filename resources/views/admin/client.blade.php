@extends('layout.master')
@section('APP-TITLE')
    Client
@endsection
@section('APP-CONTENT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Client List</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="toolbar">
                            <button type="submit" class="btn btn-primary" id="add-btn">Add New Client</button>
                        </div>
                        <table id="table1" data-toggle="data-bs-toggle" data-fixed-columns="true" data-fixed-number="1"
                            data-i18n-enhance="true" data-mobile-responsive="true" data-multiple-sort="true"
                            data-page-jump-to="true" data-pipeline="true" data-reorder-rows="true" data-sticky-header="true"
                            data-toolbar="#toolbar" data-pagination="true" data-search="true" data-show-refresh="true"
                            data-show-copy-rows="true" data-show-columns="true" data-url="">
                        </table>
                    </div>
                    <div class="card-footer text-end">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        // Redirect to the edit page with a session token
        function editClient(clientId) {
            $.ajax({
                url: `/admin/client/${clientId}/generate-session`,
                method: 'GET',
                success: function(response) {
                    if (response.sessionID) {
                        window.location.href = `/admin/client/${response.sessionID}/edit`;
                    } else {
                        showToast('danger', 'Failed to generate session.')
                    }
                },
                error: function() {
                    showToast('danger', 'Error generating session token.')
                }
            });
        }

        function deleteClient(clientId) {
            $.ajax({
                method: 'DELETE',
                url: `/clients/${clientId}`,
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

            var $table1 = $('#table1');

            $table1.bootstrapTable({
                url: '/clients', // Laravel API endpoint
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
                            let middleName = row.middle_name ? ` ${row.middle_name}` : '';
                            let extensionName = row.extension_name ? ` ${row.extension_name}` : '';
                            return `${row.first_name}${middleName} ${row.last_name}${extensionName}`;
                        }
                    },
                    {
                        field: 'contact_number',
                        title: 'Contact Number'
                    },
                    {
                        field: 'email',
                        title: 'Email'
                    },
                    {
                        field: 'action',
                        title: 'Actions',
                        formatter: actionFormatter
                    }
                ]
            });

            // Format the "Actions" column
            function actionFormatter(value, row, index) {
                return `<button class="btn btn-sm btn-primary" onclick="editClient('${row.id}')">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteClient('${row.id}')">Delete</button>`;
            }

        });
    </script>
@endsection
