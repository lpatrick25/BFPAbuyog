@extends('layout.master')

@section('APP-TITLE')
    Reports
@endsection

@section('marshall-reports')
    active
@endsection

@section('APP-CONTENT')
    <div class="row">
        @php
            $reports = [
                [
                    'route' => 'establishments.reports',
                    'title' => 'Establishment Reports',
                    'desc' => 'View details about establishments, including summaries and risk flags.',
                    'icon' => 'building',
                ],
                [
                    'route' => 'applications.reports',
                    'title' => 'Application Reports',
                    'desc' => 'Track application statuses and volumes.',
                    'icon' => 'file-earmark-text',
                ],
                [
                    'route' => 'schedules.reports',
                    'title' => 'Inspection Schedule Reports',
                    'desc' => 'Review inspection schedules and inspector assignments.',
                    'icon' => 'calendar-check',
                ],
                [
                    'route' => 'fsics.reports',
                    'title' => 'FSIC Reports',
                    'desc' => 'Monitor FSIC issuances and expirations.',
                    'icon' => 'file-earmark-check',
                ],
                [
                    'route' => 'compliance.reports',
                    'title' => 'Compliance and Risk Reports',
                    'desc' => 'Identify non-compliant establishments and risks.',
                    'icon' => 'shield-exclamation',
                ],
                [
                    'route' => 'audit.reports',
                    'title' => 'Audit & Historical Reports',
                    'desc' => 'Track application history and deleted records.',
                    'icon' => 'journal-text',
                ],
                [
                    'route' => 'statistical.reports',
                    'title' => 'Statistical & Analytical Reports',
                    'desc' => 'Analyze occupancy types and approval rates.',
                    'icon' => 'bar-chart',
                ],
            ];
        @endphp

        @foreach ($reports as $report)
            <div class="col-md-6 col-lg-4 mb-4">
                <a href="{{ route($report['route']) }}"
                    class="card h-100 border-0 shadow-sm hover-shadow position-relative text-decoration-none text-dark">
                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <i class="bi bi-{{ $report['icon'] }} fs-1 text-maroon"></i>
                        </div>
                        <h5 class="card-title">{{ $report['title'] }}</h5>
                        <p class="card-text text-muted">{{ $report['desc'] }}</p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endsection

@section('APP-SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {
            // Custom JS can go here
        });
    </script>
@endsection
