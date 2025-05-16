@extends('app')
@section('APP-CONTENT')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between flex-wrap">
                    <div class="header-title">
                        <h4 class="card-title mb-2">Abuyog Municipality
                        </h4>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div id="map" class="shadow-reset" style="height: 700px;"></div>
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
@endsection
@section('APP-SCRIPT')
    <!-- leaflet JS -->

    <script src="{{ asset('js/abuyog-map.js') }}" defer></script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet/Control.Geocoder.js') }}"></script>
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

        function showImagePopup(establishmentId) {
            let timerInterval = showLoadingDialog('Getting establishment information');

            $.ajax({
                url: `/admin/${establishmentId}/generate-session`,
                method: 'POST',
                success: function(response) {
                    clearInterval(timerInterval);
                    if (response.sessionID) {
                        window.location.href = `/admin/establishment/${response.sessionID}/show`;
                    } else {
                        showToast('danger', 'Failed to generate session.');
                    }
                    Swal.close();
                },
                error: function() {
                    clearInterval(timerInterval);
                    Swal.close();
                    showToast('danger', 'Error generating session token.');
                }
            });
        }

        function showMapWithCoordinates(coordinates) {
            if (!Array.isArray(coordinates) || coordinates.length === 0) {
                console.warn("No markers found!");
                return;
            }

            coordinates.forEach(function(coord) {
                if (!coord || coord.length < 3 || isNaN(coord[0]) || isNaN(coord[1])) {
                    console.warn("Invalid coordinate:", coord);
                    return;
                }

                const customIcon = L.icon({
                    iconUrl: "{{ asset('css/leaflet/images/marker-icon.png') }}",
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [0, -35],
                });

                const marker = L.marker([parseFloat(coord[0]), parseFloat(coord[1])], {
                    icon: customIcon
                }).addTo(map);

                const popupContent = `<button type="button" class="btn btn-success btn-block"
                                onclick="showImagePopup('${coord[2]}');">
                                Show Establishment
                              </button>`;

                marker.bindPopup(L.popup().setContent(popupContent));
            });
        }

        $(document).ready(function() {

            let markers = @json($response);
            showMapWithCoordinates(markers);

        });
    </script>
@endsection
