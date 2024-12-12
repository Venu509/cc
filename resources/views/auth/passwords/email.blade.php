@extends('layouts.app')
@section('content')
    <section class="forgot-password nw">
        <div class="card-main">
            <div class="card-wrapper">
                <div class="title text-center">
                    <h5 class="blue-t">Forgot Password</h5>
                </div>
                <div class="row pt-5">
                    <div class="col-lg-12">
                        <form id="sendMailForm">
                            @csrf
                            <div class="col-lg-12 mb-3">
                                <label for="phone" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                       placeholder="Enter Your E-Mail Address" required>
                                <span class="validation error-email text-danger"></span>
                                <div class="response-msg-sendmail mt-4"></div>
                                <div class="button-form d-flex mt-3 justify-content-start align-items-center">
                                    <button type="button" class="read-more-cta" onclick="sendMail()">Get OTP
                                    </button>
                                    <a type="button" class="resend" onclick="sendMail()">Resend?
                                    </a>
                                </div>
                            </div>
                        </form>
                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="form-label">Confirm OTP</label>

                            <form id="otpCodeForm">
                                <div class="apply-otp d-flex align-items-center">
                                    <div class="input-otp">
                                        <input class="otp-for" type="number" id="otp" style="height: 40px;">
                                        <div class="response-msg-otp"></div>
                                    </div>
                                    <div class="btn">
                                        <button type="button" class="read-more-cta" onclick="applyOtp()"
                                                style="font-size: 14px; padding: 10px 15px;">Apply OTP Code
                                        </button>
                                    </div>
                                    <br/>
                                </div>
                            </form>
                            <span class="validation error-otp text-danger"></span>
                        </div>
                        <div id="changePassword" class="d-none">
                            <div class="col-lg-12 mb-3 position-relative">
                                <label for="forgot-password" class="form-label">Change Password</label>
                                <form id="changePasswordForm">
                                    <input type="password" class="form-control" id="password"
                                           placeholder="Enter Your Password">
                                    <i class="toggle-password bi bi-eye-slash position-absolute cursor-pointer"></i>
                                    <span class="validation error-password text-danger"></span>
                                    <div class="response-msg-password mt-4"></div>
                                    <div class="button-fl d-flex mt-3 justify-content-center justify-content-md-start">
                                        <button type="button" class="read-more-cta text-uppercase"
                                                onclick="changePassword()">Change Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        $('#changePassword').addClass('d-none')
        {{--function sendMail() {--}}
        {{--    let route = '{{ route('front.send.email') }}';--}}
        {{--    let data;--}}
        {{--    let email = $('#sendMailForm #email').val();--}}
        {{--    let validation = $('.response-msg-sendmail');--}}
        {{--    validation.text('')--}}
        {{--    validation.removeClass('text-danger')--}}
        {{--    validation.removeClass('text-success')--}}
        {{--    validation.text('')--}}

        {{--    data = {--}}
        {{--        email: email,--}}
        {{--    }--}}

        {{--    axios({--}}
        {{--        method: 'POST',--}}
        {{--        url: route,--}}
        {{--        data: data,--}}
        {{--        headers: {--}}
        {{--            "Content-Type": "multipart/form-data"--}}
        {{--        },--}}
        {{--    })--}}
        {{--        .then(function (response) {--}}
        {{--            clearForm()--}}
        {{--            if (response['data']['status']) {--}}
        {{--                validation.addClass('text-success')--}}
        {{--                validation.text(response['data']['message'])--}}

        {{--                setTimeout(function () {--}}
        {{--                    validation.text('')--}}
        {{--                    clearContactUsForm()--}}
        {{--                }, 2000)--}}
        {{--            } else {--}}
        {{--                validation.addClass('text-danger')--}}
        {{--                validation.text(response['data']['message'])--}}
        {{--            }--}}
        {{--        })--}}
        {{--        .catch(function (error) {--}}
        {{--            $('.validation').text('');--}}

        {{--            if (error.response && error.response.status === 422) {--}}
        {{--                let errors = error.response.data.errors;--}}
        {{--                $.each(errors, function (field, error) {--}}
        {{--                    $(`.error-${field}`).text(error[0]);--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--}--}}


        {{--function applyOtp() {--}}
        {{--    let otp = $('#otpCodeForm #otp').val()--}}
        {{--    let email = $('#sendMailForm #email').val();--}}
        {{--    let route = '{{ route('front.check.otp') }}';--}}
        {{--    let validation = $('.response-msg-otp');--}}
        {{--    validation.text('')--}}
        {{--    validation.removeClass('text-danger')--}}
        {{--    validation.removeClass('text-success')--}}
        {{--    validation.text('')--}}

        {{--    let data = {--}}
        {{--        otp: otp,--}}
        {{--        email: email,--}}
        {{--    }--}}

        {{--    axios({--}}
        {{--        method: 'POST',--}}
        {{--        url: route,--}}
        {{--        data: data,--}}
        {{--        headers: {--}}
        {{--            "Content-Type": "multipart/form-data"--}}
        {{--        },--}}
        {{--    })--}}
        {{--        .then(function (response) {--}}
        {{--            clearForm()--}}
        {{--            if (response.data.status === true) {--}}
        {{--                clearForm()--}}
        {{--                $('#changePassword').removeClass('d-none')--}}
        {{--            } else {--}}
        {{--                $('#changePassword').addClass('d-none')--}}
        {{--                validation.addClass('text-danger')--}}
        {{--                validation.text(response['data']['message'])--}}
        {{--            }--}}
        {{--        })--}}
        {{--        .catch(function (error) {--}}
        {{--            $('#changePassword').addClass('d-none')--}}
        {{--            $('.validation').text('');--}}

        {{--            if (error.response && error.response.status === 422) {--}}
        {{--                let errors = error.response.data.errors;--}}
        {{--                $.each(errors, function (field, error) {--}}
        {{--                    $(`.error-${field}`).text(error[0]);--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--}--}}

        {{--function changePassword() {--}}
        {{--    let password = $('#changePasswordForm #password').val()--}}
        {{--    let email = $('#sendMailForm #email').val();--}}
        {{--    let route = '{{ route('front.reset.password') }}';--}}
        {{--    let validation = $('.response-msg-password');--}}
        {{--    validation.text('')--}}
        {{--    validation.removeClass('text-danger')--}}
        {{--    validation.removeClass('text-success')--}}
        {{--    validation.text('')--}}

        {{--    let data = {--}}
        {{--        password: password,--}}
        {{--        email: email--}}
        {{--    }--}}

        {{--    axios({--}}
        {{--        method: 'POST',--}}
        {{--        url: route,--}}
        {{--        data: data,--}}
        {{--        headers: {--}}
        {{--            "Content-Type": "multipart/form-data"--}}
        {{--        },--}}
        {{--    })--}}
        {{--        .then(function (response) {--}}
        {{--            clearForm()--}}
        {{--            if (response['data']['status']) {--}}
        {{--                validation.addClass('text-success')--}}
        {{--                validation.text(response['data']['message'])--}}

        {{--                setTimeout(function () {--}}
        {{--                    validation.text('')--}}
        {{--                    clearForm()--}}
        {{--                }, 2000)--}}
        {{--            } else {--}}
        {{--                validation.addClass('text-danger')--}}
        {{--                validation.text(response['data']['message'])--}}
        {{--            }--}}
        {{--            window.location.href = '{{ route('login') }}';--}}
        {{--        })--}}

        {{--        .catch(function (error) {--}}
        {{--            $('.validation').text('');--}}

        {{--            if (error.response && error.response.status === 422) {--}}
        {{--                let errors = error.response.data.errors;--}}
        {{--                $.each(errors, function (field, error) {--}}
        {{--                    $(`.error-${field}`).text(error[0]);--}}
        {{--                });--}}
        {{--            }--}}
        {{--        });--}}
        {{--}--}}

        function clearForm() {
            $('#sendMailForm .validation').text('')
            $('#changePassword .validation').text('')
            $('#changePasswordForm .validation').text('')
            $('#otpCodeForm .validation').text('')
        }
    </script>
@endsection
