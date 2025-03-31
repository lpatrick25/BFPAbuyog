<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} | Fire Safety Inspection Certificate</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/bfp.webp') }}" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}" />


    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=2.0.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=2.0.0') }}" />

    <!-- Dark Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark.min.css') }}" />

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css') }}" />


</head>

<body class="theme-color-red light" data-bs-spy="scroll" data-bs-target="#elements-section" data-bs-offset="0"
    tabindex="0">
    <!-- loader Start -->
    <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div>
    <!-- loader END -->

        <div class="card card-transparent shadow-none d-flex justify-content-center mb-0">
            <div class="card-body">
                <a href="#" class="navbar-brand d-flex align-items-center mb-3">

                    <!--Logo start-->
                    <div class="logo-main">
                        <div class="logo-normal">
                            <img src="{{ asset('img/bfp.webp') }}" class="text-primary icon-30"
                                alt="BFP Logo">
                        </div>
                        <div class="logo-mini">
                            <img src="{{ asset('img/bfp.webp') }}" class="text-primary icon-30"
                                alt="BFP Logo">
                        </div>
                    </div>
                    <!--logo End-->
                    <h4 class="logo-title ms-3">BFP - Abuyog</h4>
                </a>
                @php
                    $fsicPdf = $fsic->application
                        ->getMedia('fsic_requirements')
                        ->filter(fn($media) => $media->mime_type === 'application/pdf')
                        ->first();
                @endphp

                @if ($fsicPdf)
                    <div class="text-center">
                        <h4>Fire Safety Inspection Certificate</h4>

                        <!-- Normal PDF View for Desktop -->
                        <div id="pdf-desktop">
                            <iframe src="{{ $fsicPdf->getUrl() }}" width="100%"
                                height="600px"></iframe>
                        </div>

                        <!-- PDF.js Canvas for Mobile -->
                        <div id="pdf-mobile" style="display: none;">
                            <canvas id="pdf-render"></canvas>
                        </div>

                        <br>
                        <a href="{{ $fsicPdf->getUrl() }}" class="btn btn-primary mt-3"
                            target="_blank">Download FSIC PDF</a>
                    </div>
                @else
                    <p class="text-center text-danger">No FSIC Certificate available.</p>
                @endif

            </div>
        </div>

    <div class="toast-container position-fixed bottom-0 start-50 translate-middle-x p-3"></div>

    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/js/core/external.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <!-- AOS Animation Plugin-->

    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function isMobileDevice() {
                return /Mobi|Android/i.test(navigator.userAgent);
            }

            if (isMobileDevice()) {
                document.getElementById('pdf-desktop').style.display = 'none';
                document.getElementById('pdf-mobile').style.display = 'block';

                const pdfUrl = "{{ $fsicPdf->getUrl() }}";

                pdfjsLib.getDocument(pdfUrl).promise.then(pdfDoc => {
                    pdfDoc.getPage(1).then(page => {
                        let canvas = document.getElementById('pdf-render');
                        let ctx = canvas.getContext('2d');

                        let viewport = page.getViewport({
                            scale: 1.5
                        });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        let renderContext = {
                            canvasContext: ctx,
                            viewport: viewport
                        };
                        page.render(renderContext);
                    });
                });
            }
        });
    </script>
</body>

</html>
