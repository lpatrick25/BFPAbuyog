@extends('app')
@section('APP-CONTENT')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between flex-wrap">
                    <div class="header-title">
                        <h4 class="card-title mb-2">BFP: E-Fire Safety Inspection Certificate (e-FSIC)
                        </h4>
                    </div>
                </div>
                <div class="card-body text-center">
                    <!-- 🔍 Floating Search Form -->
                    <div class="floating-label">
                        <input type="text" class="form-control" id="search" placeholder=" " required>
                        <label for="search">Search FSIC...</label>
                        <button type="button" class="btn btn-search" id="search-btn">Go</button>
                    </div>

                    <div id="pdf-view" class="pdf-view-container">
                        <!-- Image will be displayed here -->
                    </div>
                </div>
            </div>

            <!-- Add loading spinner -->
            <div id="loading-spinner" class="spinner-container" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3"></div>
@endsection
@section('APP-SCRIPT')
    <script>
        var timerInterval = null;

        function showLoadingDialog(title) {
            const startTime = Date.now();

            Swal.fire({
                title: title,
                html: 'Please wait... Time Taken: <b>0</b> seconds',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();

                    // Start timer
                    timerInterval = setInterval(() => {
                        const currentTime = Date.now();
                        const timeTaken = ((currentTime - startTime) / 1000).toFixed(2);
                        const swalContainer = Swal.getHtmlContainer();

                        if (swalContainer) {
                            const timerElement = swalContainer.querySelector('b');
                            if (timerElement) {
                                timerElement.textContent = timeTaken;
                            }
                        }
                    }, 1000);
                },
                willClose: () => {
                    // Stop the timer when modal is closed
                    clearInterval(timerInterval);
                }
            });

            return timerInterval;
        }

        function showToast(type, message) {
            let toastClass = type === 'success' ? 'bg-success' : 'bg-danger';

            let toastHtml = `
                <div class="toast align-items-center text-white ${toastClass} border-0 show" role="alert">
                    <div class="d-flex">
                        <div class="toast-body text-center w-100">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>`;

            $('.toast-container').html(toastHtml);
            let toastElement = new bootstrap.Toast($('.toast')[0]);
            toastElement.show();
        }

        $(document).ready(function() {

            $('#search-btn').click(function(event) {
                event.preventDefault();

                // Show loading spinner
                $('#loading-spinner').show();
                $('#pdf-view').hide();

                $.ajax({
                    method: 'GET',
                    url: '/search-FSIC',
                    data: {
                        fsic_no: $('#search').val()
                    },
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        $('#loading-spinner').hide();

                        if (response.message) {
                            showToast('danger', response.message);
                            return;
                        }

                        if (!response.file_url) {
                            $('#search-btn').trigger('click');
                            return;
                        }

                        // Fix the malformed URL by replacing \/\/ with /
                        let fileUrl = response.file_url.replace(/\\\/\//g,
                            '/'); // Remove escaped slashes and fix the URL

                        $('#pdf-view').show();

                        // Check if the response file is a valid image URL
                        var img = '<img src="' + fileUrl + '" alt="FSIC Certificate Image">';
                        $('#pdf-view').html(img);

                        clearInterval(timerInterval);
                        Swal.close();
                    },

                    error: handleAjaxError,
                });
            });

            function handleAjaxError(jqXHR, textStatus, errorThrown) {
                clearInterval(timerInterval);
                Swal.close();

                $('#loading-spinner').hide();
                showToast('danger', 'An error occurred while processing your request.');
            }

            var fsicNo = {!! json_encode($fsicNo) !!};

            if (fsicNo) {
                $('#search').val(fsicNo);

                // Delay click event slightly
                setTimeout(function() {
                    $('#search-btn').trigger('click');
                }, 500);
            }

        });
    </script>
@endsection
