@extends('frontend.layouts.app')


@section('pageTitle', __('OTP'))
@section('content')

    <section class="sngle_page">
        <div class="container container_log">
            <div class="row">
                <div class="col-6">
                    <img src="{{ asset('build/frontend') }}/images/img3.svg" alt="">
                </div>
                <div class="col-6">
                    <div class="form_login">
                        <h2>{{ __('Please enter the OTP sent') }}</h2>
                        <h4>{{ $otp }}</h4>
                        <form method="POST"  action="{{ route('front.auth.login') }}">
                            @csrf
                            <input name="phone" value="{{ request()->phone }}" type="hidden" placeholder="-">

                            <div class="r-t-l">
                                <div class="col-3">
                                    <input class="codeInput code-input" name="otp1" maxlength="1" type="text" placeholder="-">
                                </div>

                                <div class="col-3">
                                    <input class="codeInput code-input" name="otp2" maxlength="1" type="text" placeholder="-">
                                </div>
                                <div class="col-3">
                                    <input class="codeInput code-input" name="otp3" maxlength="1" type="text" placeholder="-">
                                </div>

                                <div class="col-3">
                                    <input class="codeInput code-input" name="otp4" maxlength="1" type="text" placeholder="-">
                                </div>
                            </div>
                           

                            <button>{{ __('Login Now') }}</button>

                            <span class="link_reg">{{ __('I did not receive the code,') }}<a href=""> {{ __('Send it again') }}</a></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
