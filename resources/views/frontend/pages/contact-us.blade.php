@extends('frontend.layouts.app')
@section('pageTitle', __('Contact us'))
@section('content')

  <section class="sngle_page">
    <div class="container container_log">
      <div class="row">
        <div class="col-4">
          <div class="social_cont">
            <ul>
              <li>
                <img src="{{ asset('build/frontend') }}/images/whatsapp.png" alt="">
                <h3>{{ $setting->whatsapp }}</h3>
              </li>
              <li>
                <img src="{{ asset('build/frontend') }}/images/twitter.png" alt="">
                <h3>{{ $setting->twitter }}</h3>
              </li>

              <li>
                <img src="{{ asset('build/frontend') }}/images/phone-call.png" alt="">
                <h3>{{ $setting->phone }}</h3>
              </li>

              <li>
                <img src="{{ asset('build/frontend') }}/images/mail.png" alt="">
                <h3>{{ $setting->email }}</h3>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-8">
          <div class="form_login">
            <h2>  {{ __('Contact us') }}</h2>
            <form method="POST" action="{{ route('front.contact') }}">
                @csrf
                <input class="Input" name="name" type="text" placeholder="{{ __('Name') }}">
                <input class="Input" name="subject" type="text" placeholder="{{ __('Subject') }}">
                <input class="Input" name="email" type="text" placeholder="{{ __('Email') }}">
                <input class="Input" name="phone" type="text" placeholder="{{ __('Phone') }}">
                <textarea  placeholder="{{ __('Message') }}" name="message"></textarea>
                <button>{{ __('Send Now') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

