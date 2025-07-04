<nav class="nav navbar navbar-expand-lg navbar-light iq-navbar nav-glass">
    <div class="container-fluid navbar-inner">
        <a href="#" class="navbar-brand">
            <!--Logo start-->
            <!--logo End-->

            <!--Logo start-->
            <div class="logo-main">
                <div class="logo-normal">
                    <img src="{{ asset('img/bfp.webp') }}" alt="BFP Abuyog" class="text-primary icon-30" width="28">
                </div>
                <div class="logo-mini">
                    <img src="{{ asset('img/bfp.webp') }}" alt="BFP Abuyog" class="text-primary icon-30" width="28">
                </div>
            </div>
            <!--logo End-->
            <h4 class="logo-title">BFP - Abuyog</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                </svg>
            </i>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <span class="mt-2 navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('assets/images/avatars/avtar_1.png') }}" alt="User-Profile"
                            class="theme-color-purple-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('assets/images/avatars/avtar_2.png') }}" alt="User-Profile"
                            class="theme-color-blue-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('assets/images/avatars/avtar_4.png') }}" alt="User-Profile"
                            class="theme-color-green-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('assets/images/avatars/avtar_5.png') }}" alt="User-Profile"
                            class="theme-color-yellow-img img-fluid avatar avatar-50 avatar-rounded">
                        <img src="{{ asset('assets/images/avatars/avtar_3.png') }}" alt="User-Profile"
                            class="theme-color-pink-img img-fluid avatar avatar-50 avatar-rounded">
                        <div class="caption ms-3 d-none d-md-block">
                            <h6 class="mb-0 caption-title" id="userFullName">
                                {{ optional(auth()->user())->getFullName() }}</h6>
                            <p class="mb-0 caption-sub-title" id="userRole">{{ auth()->user()->role }}</p>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        @if (auth()->user()->role !== 'Admin')
                            <li><a class="dropdown-item" href="#" onclick="toggleProfileMode('view')"
                                    data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a></li>
                            <li><a class="dropdown-item" href="#" onclick="toggleProfileMode('edit')"
                                    data-bs-toggle="modal" data-bs-target="#profileModal">Edit Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @endif

                        <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> <!-- Nav Header Component Start -->
