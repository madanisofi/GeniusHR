@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection

@section('action-button')
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            if ($('.gdpr_fulltime').is(':checked')) {

                $('.fulltime').show();
            } else {

                $('.fulltime').hide();
            }

            $('#gdpr_cookie').on('change', function() {
                if ($('.gdpr_fulltime').is(':checked')) {

                    $('.fulltime').show();
                } else {

                    $('.fulltime').hide();
                }
            });
        });

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        $('.themes-color-change').on('click', function() {
            var color_val = $(this).data('value');
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);

        });
    </script>
    <script>
        document.getElementById('logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script>
        document.getElementById('logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection



@php
    $logo = asset('uploads/logo/');
    $lang = Utility::getValByName('default_language');
    $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';
    // $is_sidebar_transperent = isset($settings['is_sidebar_transperent']) ? $settings['is_sidebar_transperent'] : '';
    // $dark_mode = isset($settings['dark_mode']) ? $settings['dark_mode'] : '';
    $setting = Utility::settings();

    $SITE_RTL = $settings['SITE_RTL'];
    if ($SITE_RTL == '') {
        $SITE_RTL == 'off';
    }
@endphp





@section('content')
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top">
                    <div class="list-group list-group-flush" id="useradd-sidenav">

                        <a href="#site-setting" id="site-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Site Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#email-setting" id="email-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Email Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#payment-setting" id="payment-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Payment Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#recaptcha-print-setting" id="recaptcha-print-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('ReCaptcha Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="" id="site-setting">
                    {{ Form::model($settings, ['url' => 'settings', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Site Setting') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Logo dark') }}</h5>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class=" setting-card">
                                                        <div class="logo-content mt-4 setting-logo">
                                                            <img id="image"
                                                                src="{{ asset('uploads/logo/logo-dark.png') }}"
                                                                class="logo logo-sm"
                                                                style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                        </div>
                                                        <div class="choose-files mt-5">
                                                            <label for="logo">
                                                                <div class=" bg-primary logo_update"> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file"
                                                                    name="logo" id="logo"
                                                                    data-filename="logo_update" accept=".jpeg,.jpg,.png"
                                                                    accept=".jpeg,.jpg,.png">
                                                            </label>
                                                        </div>


                                                        @error('logo')
                                                            <div class="row">
                                                                <span class="invalid-logo" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Logo Light') }}</h5>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class=" setting-card">
                                                        <div class="logo-content mt-4  setting-logo">
                                                            <img id="image1"
                                                                src="{{ asset('uploads/logo/logo-light.png') }}"
                                                                class="logo logo-sm img_setting"
                                                                style="filter: drop-shadow(2px 3px 7px #011c4b);">
                                                        </div>

                                                        <div class="choose-files mt-5">
                                                            <label for="logo_light">
                                                                <div class=" bg-primary logo_light_update"> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file"
                                                                    name="logo_light" id="logo_light"
                                                                    data-filename="logo_light_update">
                                                            </label>
                                                        </div>

                                                        @error('logo_light')
                                                            <div class="row">
                                                                <span class="invalid-logo_light" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-6 col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>{{ __('Favicon') }}</h5>
                                                </div>
                                                <div class="card-body pt-0">
                                                    <div class=" setting-card">
                                                        <div class="logo-content mt-4 setting-logo ">
                                                            <img src="{{ asset('uploads/logo/favicon.png') }}"
                                                                width="50px" class="logo logo-sm img_setting">
                                                        </div>
                                                        <div class="choose-files mt-5">
                                                            <label for="favicon_update">
                                                                <div class=" bg-primary favicon_update"> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file"
                                                                    name="favicon" id="favicon_update"
                                                                    data-filename="favicon_update">
                                                            </label>
                                                        </div>
                                                        @error('favicon')
                                                            <div class="row">
                                                                <span class="invalid-favicon" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            {{ Form::label('title_text', __('Title Text'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Title Text')]) }}
                                            @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror



                                        </div>
                                        <div class="form-group col-md-4">
                                            {{ Form::label('footer_text', __('Footer Text'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('footer_text', null, ['class' => 'form-control', 'placeholder' => __('Footer Text')]) }}
                                            @error('footer_text')
                                                <span class="invalid-footer_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror


                                        </div>
                                        {{-- {{ dd($lang) }} --}}
                                        <div class="form-group col-md-4">
                                            {{ Form::label('default_language', __('Default Language'), ['class' => 'col-form-label']) }}
                                            <select name="default_language" id="default_language"
                                                class="form-control select2">
                                                @foreach (Utility::languages() as $language)
                                                    <option @if ($lang == $language) selected @endif
                                                        value="{{ $language }}">{{ Str::upper($language) }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="row">
                                            <div class="col-3 ">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">

                                                        {{ Form::label('display_landing_page', __('Landing Page Display'), ['class' => 'col-form-label']) }}

                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class=""
                                                                name="display_landing_page" id="display_landing_page"
                                                                {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label mb-1"
                                                                for="display_landing_page"></label>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3 ">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">
                                                        {{ Form::label('SITE_RTL', __('RTL'), ['class' => 'col-form-label']) }}
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class="" name="SITE_RTL"
                                                                id="SITE_RTL"
                                                                {{ Utility::getValByName('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label mb-1"
                                                                for="SITE_RTL"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-3 ">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">
                                                        {{ Form::label('disable_signup_button', __('Signup'), ['class' => 'col-form-label']) }}
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class=""
                                                                name="disable_signup_button" id="disable_signup_button"
                                                                {{ $settings['disable_signup_button'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label mb-1"
                                                                for="disable_signup_button"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="col-3">
                                                <div class="custom-control custom-switch p-0">
                                                    <div class="form-group ml-2 mr-3">
                                                        <label class="col-form-label"
                                                            for="gdpr_cookie">{{ __('GDPR Cookie') }}</label><br>
                                                        <input type="checkbox"
                                                            class="form-check-input gdpr_fulltime gdpr_type"
                                                            data-toggle="switchbutton" data-onstyle="primary"
                                                            name="gdpr_cookie" id="gdpr_cookie"
                                                            {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            {{ Form::label('cookie_text', __('GDPR Cookie Text'), ['class' => 'fulltime form-label']) }}
                                            {!! Form::textarea(
                                                'cookie_text',
                                                isset($settings['cookie_text']) && $settings['cookie_text'] ? $settings['cookie_text'] : '',
                                                ['class' => 'form-control fulltime', 'style' => 'display: hidden;resize: none;', 'rows' => '2'],
                                            ) !!}
                                        </div>

                                        <h5 class="mt-3 mb-3">{{ __('Theme Customizer') }}</h5>
                                        <div class="col-12">
                                            <div class="pct-body">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <h6 class="">
                                                            <i data-feather="credit-card"
                                                                class="me-2"></i>{{ __('Primary color settings') }}
                                                        </h6>
                                                        <hr class="my-2" />

                                                        <div class="theme-color themes-color">
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                                data-value="theme-1"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="theme_color" value="theme-1"
                                                                {{ $color == 'theme-1' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                                data-value="theme-2"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="theme_color" value="theme-2"
                                                                {{ $color == 'theme-2' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                                data-value="theme-3"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="theme_color" value="theme-3"
                                                                {{ $color == 'theme-3' ? 'checked' : '' }}>
                                                            <a href="#!"
                                                                class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                                data-value="theme-4"></a>
                                                            <input type="radio" class="theme_color d-none"
                                                                name="theme_color" value="theme-4"
                                                                {{ $color == 'theme-4' ? 'checked' : '' }}>
                                                        </div>

                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class=" ">
                                                            <i data-feather="layout"
                                                                class="me-2"></i>{{ __('Sidebar settings') }}
                                                        </h6>
                                                        <hr class="my-2 " />
                                                        <div class="form-check form-switch ">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust_theme_bg" name="cust_theme_bg"
                                                                @if ($settings['cust_theme_bg'] == 'on') checked @endif />

                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust_theme_bg">{{ __('Transparent layout') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <h6 class=" ">
                                                            <i data-feather="sun"
                                                                class=""></i>{{ __('Layout settings') }}
                                                        </h6>
                                                        <hr class=" my-2  " />
                                                        <div class="form-check form-switch mt-2 ">
                                                            <input type="checkbox" class="form-check-input"
                                                                id="cust_darklayout" name="cust_darklayout"
                                                                @if ($settings['cust_darklayout'] == 'on') checked @endif />

                                                            <label class="form-check-label f-w-600 pl-1"
                                                                for="cust_darklayout">{{ __('Dark Layout') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">

                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}

                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

                <div class="" id="email-setting">
                    {{ Form::open(['route' => 'email.settings', 'method' => 'post']) }}
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Email Setting') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_driver', env('MAIL_DRIVER'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_driver')
                                                <span class="text-xs text-danger invalid-mail_driver"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_host', __('Mail Host'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_host', env('MAIL_HOST'), ['class' => 'form-control ', 'placeholder' => __('Enter Mail Driver')]) }}
                                            @error('mail_host')
                                                <span class="text-xs text-danger invalid-mail_driver"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_port', __('Mail Port'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_port', env('MAIL_PORT'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Port')]) }}
                                            @error('mail_port')
                                                <span class="text-xs text-danger invalid-mail_port"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_username', __('Mail Username'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_username', env('MAIL_USERNAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Username')]) }}
                                            @error('mail_username')
                                                <span class="text-xs text-danger invalid-mail_username"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_password', __('Mail Password'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_password', env('MAIL_PASSWORD'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Password')]) }}
                                            @error('mail_password')
                                                <span class="text-xs text-danger invalid-mail_password"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_encryption', env('MAIL_ENCRYPTION'), ['class' => 'form-control', 'placeholder' => __('Enter Mail Encryption')]) }}
                                            @error('mail_encryption')
                                                <span class="text-xs text-danger invalid-mail_encryption"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_from_address', __('Mail From Address'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_from_address', env('MAIL_FROM_ADDRESS'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Address')]) }}
                                            @error('mail_from_address')
                                                <span class="text-xs text-danger invalid-mail_from_address"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                            {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('mail_from_name', env('MAIL_FROM_NAME'), ['class' => 'form-control', 'placeholder' => __('Enter Mail From Name')]) }}
                                            @error('mail_from_name')
                                                <span class="text-xs text-danger invalid-mail_from_name"
                                                    role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="#" class="btn btn-xs btn-primary" data-ajax-popup="true"
                                                data-title="{{ __('Send Test Mail') }}"
                                                data-url="{{ route('test.mail') }}">
                                                {{ __('Send Test Mail') }}
                                            </a>

                                        </div>
                                        <div class="text-end col-md-6">
                                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="card" id="payment-setting">
                    <div class="card-header">
                        <h5>{{ 'Payment Setting' }}</h5>
                        <small
                            class="text-secondary font-weight-bold">{{ __('This detail will use for make purchase of plan.') }}</small>
                    </div>
                    {{ Form::open(['route' => 'payment.settings', 'method' => 'post']) }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label class="col-form-label">{{ __('Currency') }} *</label>
                                        {{ Form::text('currency', env('CURRENCY'), ['class' => 'form-control font-style', 'required', 'placeholder' => __('Enter Currency')]) }}
                                        <small class="text-xs">
                                            {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                            <a href="https://stripe.com/docs/currencies"
                                                target="_blank">{{ __('you can find out here..') }}</a>
                                        </small>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="currency_symbol"
                                            class="col-form-label">{{ __('Currency Symbol') }}</label>
                                        {{ Form::text('currency_symbol', env('CURRENCY_SYMBOL'), ['class' => 'form-control', 'required', 'placeholder' => __('Enter Currency Symbol')]) }}
                                    </div>
                                </div>
                                <div class="faq justify-content-center">
                                    <div class="row">


                                        <div class="col-sm-12 col-md-12 col-xxl-12">
                                            <div class="accordion accordion-flush" id="accordionExample">


                                                <div class="accordion-item card">
                                                    <h2 class="accordion-header" id="heading-2-2">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse0"
                                                            aria-expanded="true" aria-controls="collapse0">
                                                            <span class="d-flex align-items-center">
                                                                <i class="ti ti-credit-card text-primary"></i>
                                                                {{ __('Stripe') }}
                                                            </span>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse0" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-2-2" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-12 text-end">
                                                                    <div
                                                                        class="form-check form-switch form-switch-right mb-2">
                                                                        <input type="hidden" name="is_stripe_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input mx-2"
                                                                            name="is_stripe_enabled"
                                                                            id="is_stripe_enabled"
                                                                            {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="is_stripe_enabled">{{ __('Enable') }}</label>
                                                                    </div>

                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">

                                                                        {{ Form::label('stripe_key', __('Stripe Key'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('stripe_key', isset($admin_payment_setting['stripe_key']) ? $admin_payment_setting['stripe_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Stripe Key')]) }}
                                                                        @if ($errors->has('stripe_key'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('stripe_key') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">

                                                                        {{ Form::label('stripe_secret', __('Stripe Secret'), ['class' => 'col-form-label']) }}
                                                                        {{ Form::text('stripe_secret', isset($admin_payment_setting['stripe_secret']) ? $admin_payment_setting['stripe_secret'] : '', ['class' => 'form-control ', 'placeholder' => __('Enter Stripe Secret')]) }}
                                                                        @if ($errors->has('stripe_secret'))
                                                                            <span class="invalid-feedback d-block">
                                                                                {{ $errors->first('stripe_secret') }}
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn-submit btn btn-primary" type="submit">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                    </form>
                </div>
                <div id="recaptcha-print-setting" class="card">
                    <div class="col-md-12">
                        <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                            target="_blank" class="text-blue">
                                            <h5 class="">{{ __('ReCaptcha settings') }}</h5><small
                                                class="text-secondary font-weight-bold">({{ __('How to Get Google reCaptcha Site and Secret key') }})</small>
                                        </a>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                    class="" name="recaptcha_module" id="recaptcha_module"
                                                    value="yes"
                                                    {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-label px-2"
                                                    for="recaptcha_module "></label><br>
                                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                    target="_blank" class="text-blue">

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_key"
                                            class="form-label">{{ __('Google Recaptcha Key') }}</label>
                                        <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                            name="google_recaptcha_key" type="text"
                                            value="{{ env('NOCAPTCHA_SITEKEY') }}" id="google_recaptcha_key">

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_secret"
                                            class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                        <input class="form-control "
                                            placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                            name="google_recaptcha_secret" type="text"
                                            value="{{ env('NOCAPTCHA_SECRET') }}" id="google_recaptcha_secret">

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">

                                {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
