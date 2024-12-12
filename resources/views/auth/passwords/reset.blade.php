@extends('layouts.app')
@section('content')
    <section class="change-password nw">
        <div class="card-main">
            <div class="card-wrapper">
                <div class="title text-center">
                    <h5 class="blue-t">Change Password</h5>
                </div>
                <form id="passwordRestForm">
                    @csrf
                    <input type="hidden" id="token" name="token" value="{{ $token }}">
                <div class="row pt-5">
                    <div class="col-lg-12">
                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="form-label">Email Address</label>
                            <input type="text" class="form-control" id="email" value="{{ $email ?? old('email') }}"
                                   placeholder="Email">
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="form-label">Password</label>
                            <input type="text" class="form-control" id="password" placeholder="New Password">
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="phone" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password-confirm" placeholder="Confirm Password">
                        </div>

                    </div>
                </div>
                <div class="button-fl d-flex mt-3 justify-content-center">
                    <button type="button" class="read-more-cta text-uppercase" onclick="restPassword()">
                        change password
                    </button>
                </div>
                </form>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script>
        function restPassword() {
            let route = '{{ route("password.update") }}';
            let data;
            let token = $('#passwordRestForm #token').val();
            let email = $('#passwordRestForm #email').val();
            let password = $('#passwordRestForm #password').val();
            let password_confirmation = $('#passwordRestForm #password-confirm').val();

            data = {
                token: token,
                email: email,
                password: password,
                password_confirmation: password_confirmation,
            }

            axios({
                method: 'POST',
                url: route,
                data: data,
                headers: {"Content-Type": "multipart/form-data"},
            })
                .then(function (response) {
                    clearForm();
                    {{--window.location.href = '{{ route("front.my-account.index") }}';--}}
                })
                .catch(function (response) {
                    $('#passwordRestForm .validation').text('')
                    let errors = response.response.data.errors;
                    $.each(errors, function (index, error) {
                        $(`#passwordRestForm .validation-${index}`).text(error)
                    })
                });
        }

        function clearForm() {
            $('#passwordRestForm .validation').text('')
        }
    </script>

@endsection
