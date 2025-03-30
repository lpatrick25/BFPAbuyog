@extends('layout.master')

@section('APP-TITLE')
    GIS Mapping
@endsection
@section('admin-gis')
    active
@endsection
@section('APP-CONTENT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Establishment Mapping</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="map" class="shadow-reset" style="height: 700px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('APP-SCRIPT')
    <script>
        var iconPaths = {
            inspected: "{{ asset('css/leaflet/images/marker-icon-blue.png') }}",
            notInspected: "{{ asset('css/leaflet/images/marker-icon-red.png') }}",
            notApplied: "{{ asset('css/leaflet/images/marker-icon-yellow.png') }}",
            default: "{{ asset('css/leaflet/images/marker-icon-default.png') }}"
        };
    </script>

    <script src="{{ asset('js/abuyog-map.js') }}" defer></script>
    <script type="text/javascript">
        $(document).ready(function() {
            let summary = @json($summary);
            let role = "{{ session('role') }}".toLowerCase();

            var infoControl = L.control({
                position: 'topright'
            });

            infoControl.onAdd = function() {
                var div = L.DomUtil.create('div', 'info-control');
                let summaryContent = `
                    <h4>Establishment Summary</h4>
                    <p class="text-left" style="font-size: 18px; color: #0596db;">
                        <strong>Inspected:</strong> ${summary.inspected}
                    </p>
                    <p class="text-left" style="font-size: 18px; color: #ef7f7f;">
                        <strong>Not Inspected:</strong> ${summary.not_inspected}
                    </p>
                `;

                if (role !== 'marshall' && role !== 'inspector') {
                    summaryContent += `
                        <p class="text-left" style="font-size: 18px; color: #000;">
                            <strong>Not Applied:</strong> ${summary.not_applied}
                        </p>
                    `;
                }

                div.innerHTML = summaryContent;
                div.style.background = 'white';
                div.style.padding = '10px';
                div.style.border = '1px solid #ccc';
                div.style.borderRadius = '5px';
                return div;
            };

            infoControl.addTo(map);

            let markers = @json($response);
            showMapWithCoordinates(markers);
        });
    </script>
@endsection
