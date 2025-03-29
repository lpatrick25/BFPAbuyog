@extends('layout.master')
@section('APP-TITLE')
    Application
@endsection
@section('marshall-application')
    active
@endsection
@section('APP-CSS')
    <style>
        .thumbnail-link img {
            transition: transform 0.2s ease-in-out;
        }

        .thumbnail-link img:hover {
            transform: scale(1.1);
            cursor: pointer;
        }
    </style>
@endsection
@section('APP-CONTENT')
    <div class="row" id="addForm">
        <div class="col-lg-12">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Application List</h4>
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
    <div class="modal fade" id="schedule" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="scheduleLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form id="scheduleForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleLabel">Application Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inspector_id">Inspector Name: <span class="text-danger">*</span></label>
                        <select class="form-control" name="inspector_id" id="inspector_id">
                            @foreach ($inspectors as $inspector)
                                <option value="{{ $inspector->inspector->id }}">{{ $inspector->inspector->getFullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="schedule_date">Schedule Date: <span class="text-danger">*</span></label>
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
                    <h5 class="modal-title" id="remarksLabel">Application Remarks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group-inner">
                        <label for="remarks">Remarks: <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="remarks_text" name="remarks_text" rows="12"></textarea>
                    </div>
                </div>
                <div class="modal-footer text-end">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="requirements" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="requirementsLabel" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requirementsLabel">Application Requiments</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="requirementsContainer"></div>
                </div>
                <div class="modal-footer text-end">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        let applicationId;

        function viewFSICRequirements(applicationId) {
            $.ajax({
                method: 'GET',
                url: `/applications/${applicationId}`,
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    if (response.fsic_requirements && response.fsic_requirements.length > 0) {
                        let count = 1;
                        let html = '<ul class="list-group list-group-flush">';

                        response.fsic_requirements.forEach((req, index) => {
                            let displayName = req.name.replace(/_/g,
                                ' '); // Convert underscores to spaces

                            html += `
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span class="text-capitalize fw-medium">${count}. ${displayName}</span>
                                    <a href="${req.url}" data-fslightbox="fsic-gallery" data-type="image" class="thumbnail-link">
                                        <img src="${req.thumbnail}" alt="Thumbnail" class="img-thumbnail shadow-sm rounded" width="60">
                                    </a>
                                </li>`;
                            count++;
                        });

                        html += '</ul>';
                        $('#requirementsContainer').html(html);

                        // Refresh FS Lightbox to register new elements
                        refreshFsLightbox();
                    } else {
                        $('#requirementsContainer').html(
                            '<p class="text-muted text-center">No requirements found.</p>');
                    }

                    $('#requirements').modal('show'); // Show the modal
                },
                error: function(xhr) {
                    showToast('danger', xhr.responseJSON.error || 'Something went wrong.');
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
            // $("select").select2({
            //     width: '100%'
            // });

            var $table1 = $('#table1');

            $table1.bootstrapTable({
                url: '/applications',
                method: 'GET',
                pagination: true,
                sidePagination: 'server',
                pageSize: 10,
                pageList: [5, 10, 25, 50, 100],
                search: true,
                buttonsAlign: 'left',
                searchAlign: 'left',
                toolbarAlign: 'right',
                queryParams: function(params) {
                    return {
                        limit: params.limit,
                        page: params.offset / params.limit + 1
                    };
                },
                responseHandler: function(res) {
                    return {
                        total: res.pagination.total,
                        rows: res.rows
                    };
                },
                columns: [{
                        field: 'id',
                        title: 'ID'
                    },
                    {
                        field: 'application_number',
                        title: 'Application Number'
                    },
                    {
                        field: 'application_date',
                        title: 'Application Date',
                        formatter: function(value, row, index) {
                            if (!value) return 'N/A';

                            let date = new Date(value);
                            return date.toLocaleDateString('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric'
                            });
                        }
                    },
                    {
                        field: 'establishment.name',
                        title: 'Establishment Name'
                    },
                    {
                        field: 'fsic_type',
                        title: 'FSIC Type',
                        formatter: function(value, row, index) {
                            const FSIC_TYPES = {
                                0: 'Occupancy',
                                1: 'New Business',
                                2: 'Renewal Business'
                            };

                            return FSIC_TYPES[value] || 'Unknown';
                        }
                    },
                    {
                        field: 'application_statuses',
                        title: 'Status',
                        formatter: function(value, row, index) {
                            if (!value || value.length === 0) {
                                return 'No Status';
                            }

                            // Find the latest status based on 'updated_at'
                            let latestStatus = value.reduce((latest, status) => {
                                return new Date(status.updated_at) > new Date(latest
                                    .updated_at) ? status : latest;
                            });

                            return latestStatus.status;
                        }
                    },
                    {
                        field: 'application_statuses',
                        title: 'Remarks',
                        formatter: function(value, row, index) {
                            if (!value || value.length === 0) {
                                return 'No Remarks';
                            }

                            // Sort the statuses by 'updated_at' in descending order (latest first)
                            let sortedStatuses = value.sort((a, b) => new Date(b.updated_at) -
                                new Date(a.updated_at));

                            // Check if there is a second-latest status
                            if (sortedStatuses.length > 1) {
                                return sortedStatuses[1].remarks || 'No Remarks';
                            } else {
                                return 'No Previous Remarks'; // If there's only one status, no previous remark exists
                            }
                        }
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
                // Check if there are statuses
                if (!row.application_statuses || row.application_statuses.length === 0) {
                    return ''; // No statuses, so no buttons
                }

                // Find the latest status (based on updated_at)
                let latestStatus = row.application_statuses.reduce((latest, status) => {
                    return new Date(status.updated_at) > new Date(latest.updated_at) ? status : latest;
                });

                // Default action buttons
                let actionButtons = ``;

                // If the latest status is NOT "Under Review", disable or hide the buttons
                if (latestStatus.status === "Under Review") {
                    actionButtons += `
                        <button class="btn btn-sm btn-info" onclick="applicationSchedule('${row.id}')">
                            <i class="bi bi-calendar-check"></i>
                        </button>
                        <button class="btn btn-sm btn-success" onclick="applicationRemarks('${row.id}')">
                            <i class="bi bi-card-checklist"></i>
                        </button>
                    `;
                } else {
                    actionButtons += `
                        <button class="btn btn-sm btn-info" disabled>
                            <i class="bi bi-calendar-check"></i>
                        </button>
                        <button class="btn btn-sm btn-success" disabled>
                            <i class="bi bi-card-checklist"></i>
                        </button>
                    `;
                }

                return actionButtons;
            }

            // Click event for the table rows
            $table1.on('click-row.bs.table', function(e, row, $element) {
                // Prevent multiple event bindings
                if (!$element.data('click-bound')) {
                    $element.data('click-bound', true);

                    // Attach click event to all <td> except the last column (Actions)
                    $element.find('td:not(:last)').on('click', function() {
                        // Open the modal
                        viewFSICRequirements(row.id);
                    });

                    // Initialize tooltip once
                    $element.attr('title', `Show application status`).tooltip({
                        trigger: 'hover',
                        placement: 'top'
                    });
                }
            });

            $('#remarksForm').submit(function(event) {
                event.preventDefault();
                let timerInterval = showLoadingDialog('Notifying Establishment Owner');

                let submitBtn = $('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');

                // Remove previous error messages and invalid classes
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    method: 'PUT',
                    url: `/applicationsStatus/${applicationId}`,
                    data: {
                        status: 'Under Review',
                        remarks: $('#remarks_text').val(),
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        clearInterval(timerInterval);
                        showToast('success', response.message);

                        // Reset the form
                        $('#remarksForm')[0].reset();

                        $('#remarks').modal('hide');
                        Swal.close();
                    },
                    error: handleAjaxError,
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Save');
                    }
                });
            });

            $('#scheduleForm').submit(function(event) {
                event.preventDefault();
                let timerInterval = showLoadingDialog('Notifying Establishment Owner');

                let submitBtn = $('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');

                // Remove previous error messages and invalid classes
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    method: 'POST',
                    url: '/schedules',
                    data: {
                        application_id: applicationId,
                        inspector_id: $('#inspector_id').val(),
                        schedule_date: $('#schedule_date').val(),
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        clearInterval(timerInterval);
                        showToast('success', 'Success');

                        // Reset the form
                        $('#scheduleForm')[0].reset();

                        $('#schedule').modal('hide');
                        Swal.close();
                    },
                    error: handleAjaxError,
                    complete: function() {
                        submitBtn.prop('disabled', false).text('Save');
                    }
                });
            });
        });
    </script>
@endsection
