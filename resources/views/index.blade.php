<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env('APP_NAME') }} | Homepage</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('img/bfp.webp') }}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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

    <style>
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
    </style>
    <style>
        /* Elegant Header Styling */
        .bfp-header {
            background: #b22222 url("https://bfp.gov.ph/wp-content/uploads/2018/08/banner_bg02_18Aug2018.png") no-repeat center;
            background-size: cover;
            height: auto;
            color: white;
            padding: 10px 0;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Logo Styling */
        .bfp-logo img {
            max-height: 100px;
            /* Ensures a fixed size */
            height: auto;
            max-width: 100%;
            /* Ensures responsiveness */
        }

        /* Mobile Centering */
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        /* Time Container */
        .time-container {
            text-align: right;
            font-size: 14px;
        }

        .time-container div {
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Styling for text to look professional */
        .time-container .time-label {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            opacity: 0.9;
        }

        .time-container .time-value {
            font-size: 14px;
            font-weight: bold;
        }


        /* 🔥 Background Section */
        .bfp-section {
            position: relative;
            background: url('https://bfp.gov.ph/wp-content/uploads/2022/03/bfp-firefighters.jpg') no-repeat center center;
            background-size: cover;
            height: auto;
            padding: 60px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        /* 🔥 Overlay */
        .bfp-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            /* Dark overlay for readability */
        }

        /* ✨ Vision & Mission Image */
        .vision-mission img {
            max-width: 1000px;
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
            z-index: 2;
            position: relative;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .bfp-header {
                text-align: center;
                height: auto;
            }

            .bfp-section {
                padding: 40px 10px;
            }

            .logo-container {
                justify-content: center;
                /* Centers logo on small screens */
                margin-bottom: 10px;
            }

            .time-container {
                text-align: center;
                margin-top: 5px;
            }
        }
    </style>
</head>

<body class="theme-color-red light boxed-fancy">
    <div class="boxed-inner">
        <!-- loader Start -->
        <div id="loading">
            <div class="loader simple-loader">
                <div class="loader-body"></div>
            </div>
        </div>
        <!-- loader END -->
        <span class="screen-darken"></span>
        <main class="main-content">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-lg navbar-light iq-navbar">
                <div class="container-fluid navbar-inner">
                    <button data-trigger="navbar_main" class="d-lg-none btn btn-primary rounded-pill p-1 pt-0"
                        type="button">
                        <svg class="icon-20" width="20px" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"></path>
                        </svg>
                    </button>
                    <a href="/" class="navbar-brand">
                        <!--Logo start-->
                        <img src="{{ asset('img/bfp.webp') }}" alt="BFP Abuyog" class="text-primary icon-50"
                            width="50">
                        <!--logo End-->
                        <h4 class="logo-title">BFP - Abuyog</h4>
                    </a>
                    <!-- Horizontal Menu Start -->
                    <nav id="navbar_main"
                        class="mobile-offcanvas nav navbar navbar-expand-xl hover-nav horizontal-nav mx-md-auto">
                        <div class="container-fluid">
                            <div class="offcanvas-header px-0">
                                <div class="navbar-brand ms-3">
                                    <!--Logo start-->
                                    <!--logo End-->

                                    <!--Logo start-->
                                    <div class="logo-main">
                                        <div class="logo-normal">
                                            <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="-0.757324" y="19.2427" width="28" height="4"
                                                    rx="2" transform="rotate(-45 -0.757324 19.2427)"
                                                    fill="currentColor" />
                                                <rect x="7.72803" y="27.728" width="28" height="4"
                                                    rx="2" transform="rotate(-45 7.72803 27.728)"
                                                    fill="currentColor" />
                                                <rect x="10.5366" y="16.3945" width="16" height="4"
                                                    rx="2" transform="rotate(45 10.5366 16.3945)"
                                                    fill="currentColor" />
                                                <rect x="10.5562" y="-0.556152" width="28" height="4"
                                                    rx="2" transform="rotate(45 10.5562 -0.556152)"
                                                    fill="currentColor" />
                                            </svg>
                                        </div>
                                        <div class="logo-mini">
                                            <svg class="text-primary icon-30" viewBox="0 0 30 30" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="-0.757324" y="19.2427" width="28" height="4"
                                                    rx="2" transform="rotate(-45 -0.757324 19.2427)"
                                                    fill="currentColor" />
                                                <rect x="7.72803" y="27.728" width="28" height="4"
                                                    rx="2" transform="rotate(-45 7.72803 27.728)"
                                                    fill="currentColor" />
                                                <rect x="10.5366" y="16.3945" width="16" height="4"
                                                    rx="2" transform="rotate(45 10.5366 16.3945)"
                                                    fill="currentColor" />
                                                <rect x="10.5562" y="-0.556152" width="28" height="4"
                                                    rx="2" transform="rotate(45 10.5562 -0.556152)"
                                                    fill="currentColor" />
                                            </svg>
                                        </div>
                                    </div>
                                    <!--logo End-->
                                    <h4 class="logo-title">BFP - Abuyog</h4>
                                </div>
                                <button class="btn-close float-end"></button>
                            </div>
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link active" href="/">Home</a></li>
                                <li class="nav-item"><a class="nav-link " href="#">About Us</a></li>
                                <li class="nav-item"><a class="nav-link " href="#">Contact Us</a></li>
                                <li class="nav-item"><a class="nav-link " href="e-FSIC">e-FSIC</a></li>
                            </ul>
                        </div>
                    </nav>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="navbar-toggler-bar bar1 mt-2"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link py-0 d-flex align-items-center" href="#" id="navbarDropdown"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <!-- User Avatar -->
                                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded shadow-sm">

                                        <!-- User Info -->
                                        <div class="caption ms-2 d-none d-md-block">
                                            <h6 class="mb-0 caption-title fw-semibold text-dark">
                                                {{ auth()->user()->getFullName() }}</h6>
                                            <p class="mb-0 caption-sub-title text-muted small">{{ auth()->user()->role }}
                                            </p>
                                        </div>
                                    </a>

                                    <!-- Dropdown Menu -->
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                        aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item" href="#"><i
                                                    class="bi bi-person-circle me-2"></i> Profile</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger"><i
                                                        class="bi bi-box-arrow-right me-2"></i> Logout</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <!-- Login Button -->
                                <li class="nav-item">
                                    <a href="{{ route('signin') }}"
                                        class="nav-link btn btn-primary px-3 py-2 text-white fw-semibold rounded-pill shadow-sm">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Header Section -->
            <header class="bfp-header">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Logo Section -->
                        <div class="col-md-9 col-12 logo-container">
                            <a href="https://bfp.gov.ph/" title="BFP : Bureau of Fire Protection" rel="home"
                                class="bfp-logo">
                                <img src="https://bfp.gov.ph/wp-content/uploads/2024/05/banner2024-v4.png"
                                    alt="BFP Logo">
                            </a>
                        </div>

                        <!-- Philippine Standard Time -->
                        <div class="col-md-3 col-12 text-md-end text-center">
                            <div class="time-container">
                                <div class="time-label">Philippine Standard Time:</div>
                                <div id="pst-time" class="time-value"><a href="#"
                                        style="text-decoration: none; color: inherit;">Loading...</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="conatiner-fluid content-inner pb-0">
                <div class="row">
                    <div class="col-md-12 col-lg-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="hopeUICarousel" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators">
                                                <button type="button" data-bs-target="#hopeUICarousel"
                                                    data-bs-slide-to="0" class="active"></button>
                                                <button type="button" data-bs-target="#hopeUICarousel"
                                                    data-bs-slide-to="1"></button>
                                                <button type="button" data-bs-target="#hopeUICarousel"
                                                    data-bs-slide-to="2"></button>
                                            </div>

                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <img src="https://ncr.bfp.gov.ph/wp-content/uploads/slider/cache/196addee76de6708aef257fcbd2231cf/FPM-2025.png"
                                                        class="d-block w-100" alt="Slide 1">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="https://ncr.bfp.gov.ph/wp-content/uploads/slider/cache/16aaf8c1912e86496539d63502cff535/ncr-website-hero.png"
                                                        class="d-block w-100" alt="Slide 2">
                                                </div>
                                                <div class="carousel-item">
                                                    <img src="https://ncr.bfp.gov.ph/wp-content/uploads/slider/cache/9d95267d436329b079ba5e24a62bc760/Planning-implementation-and-management-if-uct-system.png"
                                                        class="d-block w-100" alt="Slide 3">
                                                </div>
                                            </div>

                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#hopeUICarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#hopeUICarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="user-post">
                                            <a href="https://bfp.gov.ph/official-transparency-seal/"><img
                                                    src="https://bfp.gov.ph/wp-content/uploads/2019/05/transparency_seal_2019-500x231.jpg"
                                                    alt="post-image" class="img-fluid"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="user-post">
                                            <a href="https://www.foi.gov.ph/requests?agency=BFP"><img
                                                    src="https://bfp.gov.ph/wp-content/uploads/2018/09/FOI.jpg"
                                                    alt="post-image" class="img-fluid"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="user-post">
                                            <a href="https://bfp.gov.ph/bfp-citizens-charter/"><img
                                                    src="https://csc.gov.ph/images/widgets/2023/citizens-charter.png"
                                                    alt="post-image" class="img-fluid"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <section class="bfp-section">
                                            <div class="bfp-overlay"></div> <!-- Background Overlay -->
                                            <div class="container position-relative">
                                                <div class="row justify-content-center">
                                                    <div class="col-lg-12 text-center vision-mission">
                                                        <img src="https://bfp.gov.ph/wp-content/uploads/2022/03/vision-mission.png"
                                                            alt="Vision & Mission">
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="card credit-card-widget">
                                    <div class="card-header pb-4 border-0">
                                        <div class="p-4 primary-gradient-card rounded border border-white">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="font-weight-bold">ABUYOG </h5>
                                                    <P class="mb-0">Fire Station North Leyte</P>
                                                </div>
                                                <div class="master-card-content">
                                                    <img src="{{ asset('img/bfp.webp') }}" alt="BFP Abuyog"
                                                        class="text-primary icon-60" width="60">
                                                    <img src="{{ asset('img/dilg.webp') }}" alt="DILG Abuyog"
                                                        class="text-primary icon-60" width="60">
                                                </div>
                                            </div>
                                            <div class="my-4">
                                                <div class="card-number">
                                                    <span class="fs-5 me-2">
                                                        <svg class="icon-32" width="32" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M11.5317 12.4724C15.5208 16.4604 16.4258 11.8467 18.9656 14.3848C21.4143 16.8328 22.8216 17.3232 19.7192 20.4247C19.3306 20.737 16.8616 24.4943 8.1846 15.8197C-0.493478 7.144 3.26158 4.67244 3.57397 4.28395C6.68387 1.17385 7.16586 2.58938 9.61449 5.03733C12.1544 7.5765 7.54266 8.48441 11.5317 12.4724Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                    </span>
                                                    <span class="fs-5 me-2">0916</span>
                                                    <span class="fs-5 me-2">908</span>
                                                    <span class="fs-5">5788</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mb-2 justify-content-between">
                                                <p class="mb-0">Email</p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6>abuyogfsnorthleyte@gmail.com</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex align-items-center flex-wrap  mb-4">
                                            <div class="d-flex align-items-center me-0 me-md-4">
                                                <div class="p-3 mb-2 rounded bg-soft-primary">
                                                    <i class="icon-20 bi bi-file-earmark-text-fill"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h5>0</h5>
                                                    <small class="mb-0">FSIC</small>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="p-3 mb-2 rounded bg-soft-info">
                                                    <i class="icon-20 bi bi-building"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h5>0</h5>
                                                    <small class="mb-0">Establishment</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid grid-cols-2 gap">
                                            <button class="btn btn-primary text-uppercase">SUMMARY</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="fb-page" data-href="https://www.facebook.com/AbuyogPIS/"
                                            data-tabs="timeline" data-width="700" data-height="600"
                                            data-small-header="false" data-adapt-container-width="true"
                                            data-hide-cover="false" data-show-facepile="true">
                                        </div>

                                        <!-- Load Facebook SDK -->
                                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0"
                                            nonce="your-nonce"></script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Section Start -->
            <footer class="footer">
                <div class="footer-body">
                    <ul class="left-panel list-inline mb-0 p-0">
                        <li class="list-inline-item"><a href="#">Privacy
                                Policy</a></li>
                        <li class="list-inline-item"><a href="#">Terms of
                                Use</a></li>
                    </ul>
                    <div class="right-panel">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, Made with
                        <span class="">
                            <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M15.85 2.50065C16.481 2.50065 17.111 2.58965 17.71 2.79065C21.401 3.99065 22.731 8.04065 21.62 11.5806C20.99 13.3896 19.96 15.0406 18.611 16.3896C16.68 18.2596 14.561 19.9196 12.28 21.3496L12.03 21.5006L11.77 21.3396C9.48102 19.9196 7.35002 18.2596 5.40102 16.3796C4.06102 15.0306 3.03002 13.3896 2.39002 11.5806C1.26002 8.04065 2.59002 3.99065 6.32102 2.76965C6.61102 2.66965 6.91002 2.59965 7.21002 2.56065H7.33002C7.61102 2.51965 7.89002 2.50065 8.17002 2.50065H8.28002C8.91002 2.51965 9.52002 2.62965 10.111 2.83065H10.17C10.21 2.84965 10.24 2.87065 10.26 2.88965C10.481 2.96065 10.69 3.04065 10.89 3.15065L11.27 3.32065C11.3618 3.36962 11.4649 3.44445 11.554 3.50912C11.6104 3.55009 11.6612 3.58699 11.7 3.61065C11.7163 3.62028 11.7329 3.62996 11.7496 3.63972C11.8354 3.68977 11.9247 3.74191 12 3.79965C13.111 2.95065 14.46 2.49065 15.85 2.50065ZM18.51 9.70065C18.92 9.68965 19.27 9.36065 19.3 8.93965V8.82065C19.33 7.41965 18.481 6.15065 17.19 5.66065C16.78 5.51965 16.33 5.74065 16.18 6.16065C16.04 6.58065 16.26 7.04065 16.68 7.18965C17.321 7.42965 17.75 8.06065 17.75 8.75965V8.79065C17.731 9.01965 17.8 9.24065 17.94 9.41065C18.08 9.58065 18.29 9.67965 18.51 9.70065Z"
                                    fill="currentColor"></path>
                            </svg>
                        </span> by <a href="#">BFP Abuyog</a>.
                    </div>
                </div>
            </footer>
            <!-- Footer Section End -->
        </main>
        <!-- Wrapper End-->
    </div>

    <!-- Library Bundle Script -->
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/js/core/external.min.js') }}"></script>

    <!-- AOS Animation Plugin-->

    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>

    <!-- JavaScript for Dynamic Time -->
    <script>
        function updateTime() {
            const now = new Date().toLocaleString("en-US", {
                timeZone: "Asia/Manila",
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
                hour: "2-digit",
                minute: "2-digit",
                second: "2-digit",
                hour12: true
            });
            document.getElementById("pst-time").innerHTML =
                `<a href="#" style="text-decoration: none; color: inherit;">${now}</a>`;
        }
        setInterval(updateTime, 1000);
        updateTime();
    </script>

</body>

</html>
