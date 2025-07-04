@extends('layout.master')
@section('APP-TITLE')
    Marshall
@endsection
@section('APP-CONTENT')
    <div class="row">
        <div class="col-lg-12">
            <div class="card rounded">
                <form id="updateForm" class="card-content">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Update @yield('APP-TITLE') Information</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="new-user-info">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="first_name">First Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="First Name" value="{{ $marshall->first_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="middle_name">Middle Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name"
                                        placeholder="Middle Name" value="{{ $marshall->middle_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="last_name">Last Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="Last Name" value="{{ $marshall->last_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="extension_name">Extension Name: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="extension_name" name="extension_name"
                                        placeholder="Extension Name" value="{{ $marshall->extension_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="contact_number">Mobile Number: <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                        placeholder="Mobile Number" value="{{ $marshall->contact_number }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="email">Email: <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email" value="{{ $marshall->email }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="Password">
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label" for="password_confirmation">Repeat Password:</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Repeat Password ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('APP-SCRIPT')
<script type="text/javascript">
    $(document).ready(function() {

        $('#back-btn').show();

        $('#updateForm').submit(function(event) {
            event.preventDefault();
            let timerInterval = showLoadingDialog('Updating User Account');

            let submitBtn = $('button[type="submit"]');
            submitBtn.prop('disabled', true).text('Processing...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                method: 'PUT',
                url: "/marshalls/{{ $marshall->id }}",
                data: $(this).serialize(),
                dataType: 'JSON',
                cache: false,
                success: function(response) {
                    clearInterval(timerInterval);
                    Swal.close();
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'slow');

                    showToast('success', 'Success');

                    setInterval(() => {
                        goBack();
                    }, 1000);
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
