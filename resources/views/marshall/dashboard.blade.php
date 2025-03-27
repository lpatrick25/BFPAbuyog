@extends('layout.master')
@section('APP-TITLE')
    Dashboard
@endsection
@section('client-dashboard')
    active
@endsection
@section('APP-CONTENT')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="row row-cols-1">
                <div class="overflow-hidden d-slider1 ">
                    <ul class="p-0 m-0 mb-2 swiper-wrapper list-inline">
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="700">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-01"
                                        class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                        data-min-value="0" data-max-value="100" data-value="90" data-type="percent">
                                        <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Establishment</p>
                                        <h4 class="counter">0</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="800">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-02"
                                        class="text-center circle-progress-01 circle-progress circle-progress-info"
                                        data-min-value="0" data-max-value="100" data-value="80" data-type="percent">
                                        <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Application</p>
                                        <h4 class="counter">0</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="900">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-03"
                                        class="text-center circle-progress-01 circle-progress circle-progress-primary"
                                        data-min-value="0" data-max-value="100" data-value="70" data-type="percent">
                                        <svg class="card-slie-arrow icon-24" width="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M19,6.41L17.59,5L7,15.59V9H5V19H15V17H8.41L19,6.41Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">Schedule</p>
                                        <h4 class="counter">0</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide card card-slide" data-aos="fade-up" data-aos-delay="1000">
                            <div class="card-body">
                                <div class="progress-widget">
                                    <div id="circle-progress-04"
                                        class="text-center circle-progress-01 circle-progress circle-progress-info"
                                        data-min-value="0" data-max-value="100" data-value="60" data-type="percent">
                                        <svg class="card-slie-arrow icon-24" width="24px" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                d="M5,17.59L15.59,7H9V5H19V15H17V8.41L6.41,19L5,17.59Z" />
                                        </svg>
                                    </div>
                                    <div class="progress-detail">
                                        <p class="mb-2">FSIC</p>
                                        <h4 class="counter">0</h4>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="swiper-button swiper-button-next"></div>
                    <div class="swiper-button swiper-button-prev"></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card credit-card-widget" data-aos="fade-up" data-aos-delay="900">
                        <div class="pb-4 border-0 card-header">
                            <div class="p-4 border border-white rounded primary-gradient-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="font-weight-bold">{{ Str::ucfirst(auth()->user()->role) }}</h5>
                                        <p class="mb-0">Role</p>
                                    </div>
                                    <div class="master-card-content">
                                        <svg class="master-card-1 icon-60" width="60" viewBox="0 0 24 24">
                                            <path fill="#ffffff"
                                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                        <svg class="master-card-2 icon-60" width="60" viewBox="0 0 24 24">
                                            <path fill="#ffffff"
                                                d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="my-4">
                                    <div class="card-number">
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5 me-2">****</span>
                                        <span class="fs-5 me-2">****</span>
                                    </div>
                                </div>
                                <div class="mb-2 d-flex align-items-center justify-content-between">
                                    <p class="mb-0">Client Name</p>
                                    <p class="mb-0">Date Created</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    @php
                                        $user = auth()->user();
                                        $firstName = optional($user->client)->first_name;
                                        $middleName = optional($user->client)->middle_name;
                                        $lastName = optional($user->client)->last_name;
                                        $extensionName = optional($user->client)->extensionName;
                                    @endphp
                                    <h6>{{ $firstName }} {{ $middleName ?? '' }} {{ $lastName }}</h6>
                                    <h6 class="ms-5">{{ $user->created_at->format('m/d/Y') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8">
            <div class="row">
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        $(document).ready(function() {



        });
    </script>
@endsection
