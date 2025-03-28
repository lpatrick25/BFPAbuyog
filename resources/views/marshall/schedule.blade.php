@extends('layout.master')
@section('APP-TITLE')
    Schedule
@endsection
@section('marshall-schedule')
    active
@endsection
@section('APP-CONTENT')
    <div class="row" id="addForm">
        <div class="col-lg-12">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Schedule List</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="toolbar">
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
    <div id="map-content"></div>
    <div class="modal fade" id="remarks" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="remarksLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form id="remarksForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksLabel">Application Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="remarks_text">Remarks: <span class="text-danger">*</span></label>
                        <select name="remarks_text" id="remarks_text" class="form-control">
                            <option value="disabled" selected disabled>Select Remarks</option>
                            <option value="Issue Notice to Comply">Issue Notice to Comply</option>
                            <option value="Issue Notice to Correct Violation">Issue Notice to Correct Violation</option>
                            <option value="Issue Abatement Order">Issue Abatement Order</option>
                            <option value="Issue Stoppage of Operation">Issue Stoppage of Operation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schedule_date">Re-Schedule Date: <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="schedule_date" name="schedule_date"
                            min="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="remarks" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="remarksLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form id="remarksForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarksLabel">Application Re-scheduled</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reschedule_date">Re-Schedule Date: <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="reschedule_date" name="reschedule_date"
                            min="{{ date('Y-m-d') }}">
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
        adding = false;
        let applicationId;

        function locate(lat, lng) {
            $('#map-content').html("");
            const startTime = Date.now();
            let timerInterval;

            Swal.fire({
                title: 'Loading GIS Module',
                html: 'Please wait... Time Taken: <b>0</b> seconds',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            timerInterval = setInterval(() => {
                const currentTime = Date.now();
                const timeTaken = ((currentTime - startTime) / 1000).toFixed(2);
                const timerElement = Swal.getHtmlContainer().querySelector('b');
                if (timerElement) timerElement.textContent = timeTaken;
            }, 1000);

            $.ajax({
                method: 'GET',
                url: '{{ route('loadMap') }}',
                data: {
                    latitude: lat,
                    longitude: lng
                },
                success: function(response) {
                    clearInterval(timerInterval);
                    $('#map-content').html(response);
                    $('#addForm').hide();
                    Swal.close();
                },
                error: function(xhr) {
                    clearInterval(timerInterval);
                    console.error('Error:', xhr.responseText);
                    alert('Failed to load the map view.');
                }
            });
        }

        function applicationRemarks(applicationID) {
            applicationId = applicationID;
            $('#remarks').modal('show');
        }

        function applicationSchedule(applicationID) {
            applicationId = applicationID;
            $('#schedule').modal('show');
        }

        $(document).ready(function() {
            var $table1 = $('#table1');

            $table1.bootstrapTable({
                url: '/schedules', // Laravel API endpoint
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
                        field: 'application_number',
                        title: 'Application #'
                    },
                    {
                        field: 'establishment_name',
                        title: 'Establishment Name'
                    },
                    {
                        field: 'inspector',
                        title: 'Inspector Name'
                    },
                    {
                        field: 'schedule_date',
                        title: 'Schedule Date'
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
                return `
                    <button class="btn btn-sm btn-info" onclick="applicationSchedule('${row.application_id}')"><i class="bi bi-calendar-check"></i></button>
                    <button class="btn btn-sm btn-success" onclick="applicationRemarks('${row.application_id}')"><i class="bi bi-card-checklist"></i></button>`;
            }

            // Click event for the table rows
            $table1.on('click-row.bs.table', function(e, row, $element) {
                // Prevent multiple event bindings
                if (!$element.data('click-bound')) {
                    $element.data('click-bound', true);

                    // Attach click event to all <td> except the last column (Actions)
                    $element.find('td:not(:last)').on('click', function() {
                        locate(row.location_latitude, row.location_longitude);
                    });

                    // Initialize tooltip once
                    $element.attr('title', `Show establishment location`).tooltip({
                        trigger: 'hover',
                        placement: 'top'
                    });
                }
            });

            $('#remarksForm').submit(function(event) {
                event.preventDefault();

                let submitBtn = $('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');

                // Remove previous error messages and invalid classes
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    method: 'PUT',
                    url: `/applicationsStatus/${applicationId}`,
                    data: {
                        status: 'Scheduled for Inspection',
                        remarks: $('#remarks_text').val(),
                        schedule_date: $('#schedule_date').val(),
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        showToast('success', response.message);

                        // Reset the form
                        $('#remarksForm')[0].reset();

                        $('#remarks').modal('hide');
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

        });
    </script>
@endsection
