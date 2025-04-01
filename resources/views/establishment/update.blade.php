@extends('layout.master')
@section('APP-TITLE')
    Establishment
@endsection
@section('client-establishment')
    active
@endsection
@section('APP-CSS')
    <style type="text/css">
        /* Floating Action Button (FAB) */
        .floating-save-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            background-color: #007bff;
            /* Bootstrap Primary Blue */
            color: white;
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            /* Fully circular */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            /* Soft shadow */
            border: none;
            transition: all 0.3s ease-in-out;
            cursor: pointer;
        }

        /* Hover Effect */
        .floating-save-btn:hover {
            background-color: #0056b3;
            /* Darker Blue on Hover */
            transform: scale(1.1);
            /* Slightly enlarge */
        }

        /* Responsive: Adjust size for mobile */
        @media (max-width: 576px) {
            .floating-save-btn {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
@endsection
@section('APP-CONTENT')
    <form id="addForm" class="row">
        <div class="col-lg-4">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Business Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="BIN">Business Identification Number: <span class="text-danger">*</span></label>
                            <input type="text" id="BIN" name="BIN" class="form-control" data-mask="99999-99999"
                                value="{{ $establishment->BIN }}" required>
                        </div>
                        <div class="form-group">
                            <label for="TIN">Tax Identification Number: <span class="text-danger">*</span></label>
                            <input type="text" id="TIN" name="TIN" class="form-control"
                                data-mask="999-999-999-99999" value="{{ $establishment->TIN }}" required>
                        </div>
                        <div class="form-group">
                            <label for="DTI">Department of Trade and Industry: <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="DTI" name="DTI" class="form-control" data-mask="99999999"
                                value="{{ $establishment->DTI }}" required>
                        </div>
                        <div class="form-group">
                            <label for="SEC">Security and Exchange Commission: <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="SEC" name="SEC" class="form-control" data-mask="PG999999999"
                                placeholder="PG" value="{{ $establishment->SEC }}" required>
                        </div>
                        <div class="form-group">
                            <label for="nature_of_business">Nature of Business: <span class="text-danger">*</span></label>
                            <input type="text" id="nature_of_business" name="nature_of_business" class="form-control"
                                value="{{ $establishment->nature_of_business }}" required>
                        </div>
                        <button type="button" class="btn btn-success w-100 mb-3" id="mapModalBtn">CHANGE
                            LOCATION</button>
                        <div class="form-group">
                            <label for="location_latitude">Latitude: <span class="text-danger">*</span></label>
                            <input type="text" id="location_latitude" name="location_latitude" class="form-control"
                                value="{{ $establishment->location_latitude }}" required>
                        </div>
                        <div class="form-group">
                            <label for="location_longitude">Longitude: <span class="text-danger">*</span></label>
                            <input type="text" id="location_longitude" name="location_longitude" class="form-control"
                                value="{{ $establishment->location_longitude }}" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Establishment Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="name">Establishment Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        value="{{ $establishment->name }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group input-with-success">
                                    <label for="owner_name">Owner Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="owner_name" name="owner_name" class="form-control"
                                        value="{{ optional(auth()->user()->client)->first_name }}{{ optional(auth()->user()->client)->middle_name ? ' ' . optional(auth()->user()->client)->middle_name[0] : '' }} {{ optional(auth()->user()->client)->last_name }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="representative_name">Representative Name: <span
                                            class="text-danger"></span></label>
                                    <input type="text" id="representative_name" name="representative_name"
                                        class="form-control" value="{{ $establishment->representative_name }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="trade_name">Trade Name: <span class="text-danger"></span></label>
                                    <input type="text" id="trade_name" name="trade_name" class="form-control"
                                        value="{{ $establishment->trade_name }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="total_building_area">Total Building Area: <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="total_building_area" name="total_building_area"
                                        class="form-control" value="{{ $establishment->total_building_area }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="number_of_occupant">Number of Occupant: <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="number_of_occupant" name="number_of_occupant"
                                        class="form-control" value="{{ $establishment->number_of_occupant }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="type_of_occupancy">Type of Occupancy: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="type_of_occupancy" name="type_of_occupancy"
                                        class="form-control" value="{{ $establishment->type_of_occupancy }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="type_of_building">Type of Building: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="type_of_building" name="type_of_building"
                                        class="form-control" value="{{ $establishment->type_of_building }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="high_rise">High Rise: <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <label for="high_rise1" class="form-check-label">
                                            <input class="form-check-input" type="radio" value="1"
                                                id="high_rise1" name="high_rise"
                                                {{ old('high_rise', $establishment->high_rise ?? '') == 1 ? 'checked' : '' }}>
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label for="high_rise2" class="form-check-label">
                                            <input class="form-check-input" type="radio" value="0"
                                                id="high_rise2" name="high_rise"
                                                {{ old('high_rise', $establishment->high_rise ?? '') == 0 ? 'checked' : '' }}>
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="eminent_danger">In Eminent Danger: <span
                                            class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <label for="eminent_danger1" class="form-check-label">
                                            <input class="form-check-input" type="radio" value="1"
                                                id="eminent_danger1" name="eminent_danger"
                                                {{ old('eminent_danger', $establishment->eminent_danger ?? '') == 1 ? 'checked' : '' }}>
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label for="eminent_danger2" class="form-check-label">
                                            <input class="form-check-input" type="radio" value="0"
                                                id="eminent_danger2" name="eminent_danger"
                                                {{ old('eminent_danger', $establishment->eminent_danger ?? '') == 0 ? 'checked' : '' }}>
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Address Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group input-with-success">
                                    <label for="region">Region: <span class="text-danger">*</span></label>
                                    <input type="text" id="region" name="region" class="form-control"
                                        value="REGION VII (EASTERN VISAYAS)" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group input-with-success">
                                    <label for="province">Province: <span class="text-danger">*</span></label>
                                    <input type="text" id="province" name="province" class="form-control"
                                        value="LEYTE" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group input-with-success">
                                    <label for="city_mun">City/Municipality: <span class="text-danger">*</span></label>
                                    <input type="text" id="city_mun" name="city_mun" class="form-control"
                                        value="ABUYOG" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="address_brgy">Barangay: <span class="text-danger">*</span></label>
                                    <input type="text" id="address_brgy" name="address_brgy" class="form-control"
                                        value="{{ $establishment->address_brgy }}" required>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="address_ex">Blk. No./ Street Name/ Building Name: <span
                                            class="text-danger"></span></label>
                                    <input type="text" id="address_ex" name="address_ex" class="form-control"
                                        value="{{ $establishment->address_ex }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card rounded">
                <div class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Contact Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="email">Email Address: <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        value="{{ $establishment->email }}" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="landline">Landline: <span class="text-danger"></span></label>
                                    <input type="text" id="landline" name="landline" class="form-control"
                                        value="{{ $establishment->landline }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="contact_number">Mobile Number: <span class="text-danger">*</span></label>
                                    <input type="text" id="contact_number" name="contact_number" class="form-control"
                                        data-mask="9999-999-9999" placeholder="09xx-xxx-xxxx"
                                        value="{{ $establishment->contact_number }}" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" value="" id="user_owner"> <i></i> Use owner email
                                        address and mobile number </label>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Floating Save Button -->
        <button type="button" id="submit-btn" class="btn btn-primary floating-save-btn">
            💾
        </button>
    </form>
    <div id="map-content"></div>
@endsection
@section('APP-SCRIPT')
    <script type="text/javascript">
        let adding = true;

        function locate(lat, lng) {
            $('#map-content').html("");
            let timerInterval = showLoadingDialog('Loading GIS Module');

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
                    Swal.close();
                    console.error('Error:', xhr.responseText);
                    alert('Failed to load the map view.');
                }
            });
        }

        $(document).ready(function() {

            $('#back-btn').show();

            $("#total_building_area").TouchSpin({
                min: 1,
                max: 10000000,
                postfix: ' SQM',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $("#number_of_occupant").TouchSpin({
                min: 1,
                max: 10000000,
                postfix: ' PERSON',
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white'
            });

            $('#mapModalBtn').click(function(event) {
                event.preventDefault();

                const lat = $('#location_latitude').val();
                const long = $('#location_longitude').val();

                locate(lat, long);
            });

            // Get the user's email and contact number safely
            var email = "{{ optional(auth()->user()->client)->email ?? '' }}";
            var contact_number = "{{ optional(auth()->user()->client)->contact_number ?? '' }}";
            var updateEmail = "{{ $establishment->email ?? '' }}";
            var updateContact = "{{ $establishment->contact_number ?? '' }}";

            // If establishment data matches the logged-in user's data, check the box
            if (email === updateEmail && contact_number === updateContact) {
                $('#user_owner').prop('checked', true);
                $('#email').val(email).prop('readonly', true);
                $('#contact_number').val(contact_number).prop('readonly', true);
            }

            // Handle checkbox click event
            $('#user_owner').click(function() {
                if ($(this).prop('checked')) {
                    $('#email').val(email).prop('readonly', true);
                    $('#contact_number').val(contact_number).prop('readonly', true);
                } else {
                    $('#email').val('').prop('readonly', false);
                    $('#contact_number').val('').prop('readonly', false);
                }
            });

            $('#submit-btn').click(function(event) {
                event.preventDefault();
                let timerInterval = showLoadingDialog('Saving Establishment Information');

                let submitBtn = $('button[id="submit-btn"]');
                submitBtn.prop('disabled', true).html(
                    '<i class="bi bi-arrow-repeat spin-animation"></i>'
                );

                // Remove previous error messages and invalid classes
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').remove();

                $.ajax({
                    method: 'PUT',
                    url: `/establishments/{{ $establishment->id }}`, // Adjust URL if needed
                    data: $('#addForm').serialize(),
                    dataType: 'JSON',
                    cache: false,
                    success: function(response) {
                        clearInterval(timerInterval);
                        // Scroll to the top of the page
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');

                        showToast('success', 'Success');

                        // Reset the form
                        $('#addForm')[0].reset();

                        // Reset the form
                        setInterval(() => {
                            goBack();
                        }, 1000);
                    },
                    error: function(xhr) {
                        clearInterval(timerInterval);
                        Swal.close();
                        // Scroll to the top of the page
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');

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
                        submitBtn.prop('disabled', false).text('💾');
                    }
                });
            });

        });
    </script>
@endsection
