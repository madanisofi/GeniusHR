@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    $logo = asset('uploads/logo/');
    $company_logo = Utility::getValByName('company_logo');
    $company_logo_light = Utility::getValByName('company_logo_light');
    $company_favicon = Utility::getValByName('company_favicon');
    $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';
    // $is_sidebar_transperent = isset($settings['is_sidebar_transperent']) ? $settings['is_sidebar_transperent'] : '';
    // $dark_mode = isset($settings['dark_mode']) ? $settings['dark_mode'] : '';
    $setting = Utility::settings();
    
    $SITE_RTL = $settings['SITE_RTL'];
    if ($SITE_RTL == '') {
        $SITE_RTL == 'off';
    }
@endphp



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@push('script-page')
    <script>
        $(document).on('change', '.email-template-checkbox', function() {
            var url = $(this).data('url');
            var chbox = $(this);

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                success: function(data) {

                },
            });
        });
    </script>
    <script>
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
        document.getElementById('company_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script>
        document.getElementById('company_logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
    <script>
        document.getElementById('company_favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>
@endpush
@section('content')
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top">
                    <div class="list-group list-group-flush" id="useradd-sidenav">

                        <a href="#business-setting" id="business-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Business Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#system-setting" id="system-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('System Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#company-setting" id="company-setting-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('Company Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a id="email-notification-tab" data-toggle="tab" href="#email-notification" role="tab"
                            aria-controls="" aria-selected="false"
                            class="list-group-item list-group-item-action border-0">{{ __('Email Notification') }}<div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        <a href="#ip-restrict" id="ip-restrict-tab"
                            class="list-group-item list-group-item-action border-0">{{ __('IP Restrict Setting') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                        @if (Auth::user()->type == 'company')
                            <a href="#zoom-meeting-setting" id="zoom-meeting-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Zoom Meeting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#slack-setting" id="slack-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Slack Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#telegram-setting" id="telegram-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Telegram Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>

                            <a href="#twilio-setting" id="twilio-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Twilio Setting') }} <div
                                    class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        @endif
                    </div>

                </div>
            </div>

            <div class="col-xl-9">
                <div class="" id="business-setting">
                    {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Business Setting') }}</h5>
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
                                                                src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                                                                class="logo logo-sm"
                                                                style="filter: drop-shadow(2px 3px 7px #011c4b);height: 40px; width: auto;">
                                                        </div>
                                                        <div class="choose-files mt-3">
                                                            <label for="company_logo">
                                                                <div class=" bg-primary "> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file" accept="image/png"
                                                                    name="company_logo" id="company_logo"
                                                                    data-filename="company_logo">
                                                            </label>

                                                        </div>
                                                        @error('company_logo')
                                                            <div class="row">
                                                                <span class="invalid-company_logo" role="alert">
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
                                                                src="{{ $logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light : 'logo-light.png') }}"
                                                                class="logo logo-sm img_setting"
                                                                style="filter: drop-shadow(2px 3px 7px #011c4b);height: 40px; width: auto;">
                                                        </div>
                                                        <div class="choose-files mt-3">
                                                            <label for="company_logo_light">
                                                                <div class=" bg-primary dark_logo_update"> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file" accept="image/png"
                                                                    name="company_logo_light" id="company_logo_light"
                                                                    data-filename="dark_logo_update">
                                                            </label>
                                                        </div>
                                                        @error('company_logo_light')
                                                            <div class="row">
                                                                <span class="invalid-company_logo_light" role="alert">
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
                                                            <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                                id="image2" width="50px"
                                                                class="logo logo-sm img_setting">
                                                        </div>
                                                        <div class="choose-files mt-3">

                                                            <label for="company_favicon">
                                                                <div class=" bg-primary company_favicon"> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file" class="form-control file" accept="image/png, image/x-icon"
                                                                    name="company_favicon" id="company_favicon"
                                                                    data-filename="company_favicon">
                                                            </label>
                                                        </div>
                                                        @error('company_favicon')
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

                                        <div class="form-group col-md-6">

                                            {{ Form::label('title_text', __('Title Text'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('title_text', null, ['class' => 'form-control', 'placeholder' => __('Enter Title Text')]) }}

                                            @error('title_text')
                                                <span class="invalid-title_text" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="metakeyword" class="col-form-label">{{ __('Meta Keywords') }}
                                            </label>
                                            <textarea class="form-control" rows="4" cols="8"
                                                value="{{ isset($settings['metakeyword']) ? $settings['metakeyword'] : '' }}" name="metakeyword"
                                                id="metakeyword" style="resize: vertical; height: 42px;" placeholder="Enter Meta Keyword">{{ isset($settings['metakeyword']) ? $settings['metakeyword'] : '' }}</textarea>

                                        </div>


                                        <div class="form-group col-md-6">

                                            <label for="metadesc"
                                                class="col-form-label">{{ __('Meta Description') }}</label>
                                            <textarea class="form-control" rows="4" cols="8"
                                                value="{{ isset($settings['metadesc']) ? $settings['metadesc'] : '' }}" name="metadesc" id="metadesc"
                                                style="resize: vertical; height: 42px;" placeholder="Enter Meta Description">{{ isset($settings['metadesc']) ? $settings['metadesc'] : '' }}</textarea>

                                        </div>
                                        <div class="col-3 ">
                                            <div class="col switch-width">
                                                <div class="form-group ml-2 mr-3">
                                                    {{ Form::label('SITE_RTL', __('RTL'), ['class' => 'col-form-label']) }}
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary" class="" name="SITE_RTL"
                                                            id="SITE_RTL"
                                                            {{ $SITE_RTL == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label mb-1" for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>
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
                                                            <input type="hidden" name="cust_darklayout" value="off">
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
                                <div class="card-footer ">
                                    <div class="col-sm-12 px-2">
                                        <div class="text-end">
                                            {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div class="" id="system-setting">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('System Setting') }}</h5>
                        </div>
                        {{ Form::model($settings, ['route' => 'system.settings', 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row company-setting">
                                <div class="form-group col-md-4">
                                    {{ Form::label('site_currency', __('Currency *'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('site_currency', null, ['class' => 'form-control ']) }}
                                    <small class="text-xs">
                                        {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                        <a href="https://stripe.com/docs/currencies"
                                            target="_blank">{{ __('you can find out here..') }}</a>
                                    </small>
                                    @error('site_currency')
                                        <br>
                                        <span class="text-xs text-danger invalid-site_currency"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('site_currency_symbol', __('Currency Symbol *'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('site_currency_symbol', null, ['class' => 'form-control']) }}
                                    @error('site_currency_symbol')
                                        <span class="text-xs text-danger invalid-site_currency_symbol"
                                            role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    <label class="col-form-label">{{ __('Currency Symbol Position') }}</label>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" id="pre" value="pre"
                                            name="site_currency_symbol_position"
                                            @if ($settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                        <label class="form-check-label" for="pre">
                                            {{ __('Pre') }}
                                        </label>
                                    </div>
                                    <div class="form-check form-check">
                                        <input class="form-check-input" type="radio" id="post" value="post"
                                            name="site_currency_symbol_position"
                                            @if ($settings['site_currency_symbol_position'] == 'post') checked @endif>
                                        <label class="form-check-label" for="post">
                                            {{ __('Post') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="site_date_format" class="col-form-label">{{ __('Date Format') }}</label>
                                    <select type="text" name="site_date_format" class="form-control select2"
                                        id="site_date_format">
                                        <option value="M j, Y"
                                            @if (@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>
                                            Jan 1,2015</option>
                                        <option value="d-m-Y"
                                            @if (@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>
                                            d-m-y</option>
                                        <option value="m-d-Y"
                                            @if (@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>
                                            m-d-y</option>
                                        <option value="Y-m-d"
                                            @if (@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>
                                            y-m-d</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="site_time_format" class="col-form-label">{{ __('Time Format') }}</label>
                                    <select type="text" name="site_time_format" class="form-control select2"
                                        id="site_time_format">
                                        <option value="g:i A"
                                            @if (@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>
                                            10:30 PM</option>
                                        <option value="g:i a"
                                            @if (@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>
                                            10:30 pm</option>
                                        <option value="H:i"
                                            @if (@$settings['site_time_format'] == 'H:i') selected="selected" @endif>
                                            22:30</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-4">

                                    {{ Form::label('employee_prefix', __('Employee Prefix'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('employee_prefix', null, ['class' => 'form-control']) }}
                                    @error('employee_prefix')
                                        <span class="text-xs text-danger invalid-employee_prefix" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror

                                </div>



                                <div class="form-group col-md-4">
                                    {{ Form::label('formula_x', __('X Axis Formula'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('formula_x', null, ['class' => 'form-control font-style']) }}
                                    @error('formula_x')
                                        <span class="invalid-formula_x" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('formula_y', __('Y Axis Formula'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('formula_y', null, ['class' => 'form-control font-style']) }}
                                    @error('formula_y')
                                        <span class="invalid-formula_y" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('formula_z', __('Z Axis Formula'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('formula_z', null, ['class' => 'form-control font-style']) }}
                                    @error('formula_z')
                                        <span class="invalid-formula_z" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('formula_y_o_s', __('Formula Year Of Service'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('formula_y_o_s', null, ['class' => 'form-control font-style']) }}
                                    @error('formula_y_o_s')
                                        <span class="invalid-formula_y_o_s" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('formula_c_i', __('Formula Class Increase'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('formula_c_i', null, ['class' => 'form-control font-style']) }}
                                    @error('formula_c_i')
                                        <span class="invalid-formula_c_i" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>







                            </div>
                        </div>

                        <div class="card-footer ">
                            <div class="col-sm-12 px-2">
                                <div class="text-end">
                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary']) }}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="" id="company-setting">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Company Setting') }}</h5>
                        </div>
                        {{ Form::model($settings, ['route' => 'company.settings', 'method' => 'post']) }}
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_name *', __('Company Name *'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_name', null, ['class' => 'form-control ', 'placeholder' => 'Enter Company Name']) }}

                                    @error('company_name')
                                        <span class="invalid-company_name" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_address', __('Address'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_address', null, ['class' => 'form-control ', 'placeholder' => 'Enter Address']) }}
                                    @error('company_address')
                                        <span class="invalid-company_address" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_city', __('City'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_city', null, ['class' => 'form-control ', 'placeholder' => 'Enter City']) }}
                                    @error('company_city')
                                        <span class="invalid-company_city" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_state', __('State'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_state', null, ['class' => 'form-control ', 'placeholder' => 'Enter State']) }}
                                    @error('company_state')
                                        <span class="invalid-company_state" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_zipcode', __('Zip/Post Code'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter Zip/Post Code']) }}
                                    @error('company_zipcode')
                                        <span class="invalid-company_zipcode" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_country', __('Country'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_country', null, ['class' => 'form-control', 'placeholder' => 'Enter Country']) }}
                                    @error('company_country')
                                        <span class="invalid-company_country" role="alert"><strong
                                                class="text-danger">{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_telephone', __('Telephone'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_telephone', null, ['class' => 'form-control', 'placeholder' => 'Enter Telephone']) }}
                                    @error('company_telephone')
                                        <span class="invalid-company_telephone" role="alert"><strong
                                                class="text-danger">{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_email', __('System Email *'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_email', null, ['class' => 'form-control', 'placeholder' => 'Enter System Email']) }}
                                    @error('company_email')
                                        <span class="invalid-company_email" role="alert"><strong
                                                class="text-danger">{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4">
                                    {{ Form::label('company_email_from_name', __('Email (From Name) *'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('company_email_from_name', null, ['class' => 'form-control ', 'placeholder' => 'Enter Email']) }}
                                    @error('company_email_from_name')
                                        <span class="invalid-company_email_from_name" role="alert"><strong
                                                class="text-danger">{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('company_start_time', __('Company Start Time *'), ['class' => 'col-form-label']) }}

                                            {{ Form::time('company_start_time', null, ['class' => 'form-control timepicker_format']) }}
                                            @error('company_start_time')
                                                <span class="invalid-company_start_time" role="alert">
                                                    <small class="text-danger">{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('company_end_time', __('Company End Time *'), ['class' => 'col-form-label']) }}
                                            {{ Form::time('company_end_time', null, ['class' => 'form-control timepicker_format']) }}
                                            @error('company_end_time')
                                                <span class="invalid-company_end_time" role="alert">
                                                    <small class="text-danger">{{ $message }}</small>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    {{ Form::label('timezone', __('Timezone'), ['class' => 'col-form-label']) }}
                                    <select type="text" name="timezone" class="form-control select2" id="timezone">
                                        <option value="">{{ __('Select Timezone') }}</option>
                                        @foreach ($timezones as $k => $timezone)
                                            <option value="{{ $k }}"
                                                {{ env('TIMEZONE') == $k ? 'selected' : '' }}>
                                                {{ $timezone }}</option>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                        <span class="invalid-timezone" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror

                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('payroll_date', __('Payroll Date'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('payroll_date', null, ['class' => 'form-control font-style']) }}
                                    @error('payroll_date')
                                        <span class="invalid-payroll_date" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('month_start_date', __('Month Start Date'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('month_start_date', null, ['class' => 'form-control font-style']) }}
                                    @error('month_start_date')
                                        <span class="invalid-month_start_date" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('payroll_time', __('Hari Kerja'), ['class' => 'col-form-label']) }}
                                    <select class="form-control select2" name="working_days[]" id="working_days"
                                        placeholder="Select ayroll Time" multiple>
                                        <option value="1"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(1, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Monday') }}</option>
                                        <option value="2"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(2, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Tuesday') }}</option>
                                        <option value="3"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(3, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Wednesday') }}</option>
                                        <option value="4"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(4, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Thursday') }}</option>
                                        <option value="5"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(5, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Friday') }}</option>
                                        <option value="6"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(6, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Saturday') }}</option>
                                        <option value="7"
                                            {{ count(json_decode($settings['working_days'])) > 0 ? (in_array(7, json_decode($settings['working_days'])) ? 'selected' : '') : '' }}>
                                            {{ __('Sunday') }}</option>
                                    </select>
                                    @error('payroll_time')
                                        <span class="invalid-payroll_time" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('payroll_time', __('Payroll Time'), ['class' => 'col-form-label']) }}
                                    <select class="form-control select2" name="payroll_time" id="payroll_time"
                                        placeholder="Select ayroll Time">
                                        <option value="first"
                                            {{ $settings['payroll_time'] == 'first' ? 'selected' : '' }}>
                                            {{ __('Beginning of the month') }}</option>
                                        <option value="last"
                                            {{ $settings['payroll_time'] == 'last' ? 'selected' : '' }}>
                                            {{ __('End of month') }}</option>
                                    </select>
                                    @error('payroll_time')
                                        <span class="invalid-payroll_time" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <hr>
                                    <label class="text-xl">Pembatasan Akses</label>
                                </div>

                                <div class="form-group col-md-4 py-4">
                                    <div class="form-check form-switch ">
                                        <input type="checkbox" class="form-check-input" id="ip_restrict"
                                            name="ip_restrict" @if ($settings['ip_restrict'] == 'on') checked @endif />

                                        <label class="form-check-label f-w-600 pl-1"
                                            for="ip_restrict">{{ __('Ip Restrict') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="access_restrictions"
                                            id="access_restrictions"
                                            {{ $settings['access_restrictions'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="access_restrictions">{{ __('Access Restrictions') }}</label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('day_of_access', __('Access Restrictions'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('day_of_access', null, ['class' => 'form-control font-style']) }}
                                    @error('day_of_access')
                                        <span class="invalid-day_of_access" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <hr>
                                    <label class="text-xl">Perhitungan Otomatis</label>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="automatic_basic_salary"
                                            id="automatic_basic_salary"
                                            {{ $settings['automatic_basic_salary'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="automatic_basic_salary">{{ __('Automatic Basic Salary Calculation') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="auto_expense_payroll"
                                            id="auto_expense_payroll"
                                            {{ $settings['auto_expense_payroll'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="auto_expense_payroll">{{ __('Auto Create Expense Payroll') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="late_fee_calculation"
                                            id="late_fee_calculation"
                                            {{ $settings['late_fee_calculation'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="late_fee_calculation">{{ __('Late fee calculation') }}</label>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <hr>
                                    <label class="text-xl">Mode On Off</label>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="tiered_leave"
                                            id="tiered_leave"
                                            {{ $settings['tiered_leave'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="tiered_leave">{{ __('Tiered Leave') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="shift" id="shift"
                                            {{ $settings['shift'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="shift">{{ __('Shift') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="qr_presence"
                                            id="qr_presence"
                                            {{ $settings['qr_presence'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="qr_presence">{{ __('QR Presence') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="selfie_presence"
                                            id="selfie_presence"
                                            {{ $settings['selfie_presence'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="selfie_presence">{{ __('Selfie Presence') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input"
                                            name="presence_mode_with_distance" id="presence_mode_with_distance"
                                            {{ $settings['presence_mode_with_distance'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="presence_mode_with_distance">{{ __('Presence mode with distance') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="open_and_close_presence"
                                            id="open_and_close_presence"
                                            {{ $settings['open_and_close_presence'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="open_and_close_presence">{{ __('Open And Close Presence') }}</label>
                                    </div>
                                </div>

                                <div class="col-md-3 py-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="rest_mode" id="rest_mode"
                                            {{ $settings['rest_mode'] == 'on' ? 'checked="checked"' : '' }}>
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="rest_mode">{{ __('Rest Mode') }}</label>
                                    </div>
                                </div>

                                <div class="col-12 mb-4">
                                    <hr>
                                    <label class="text-xl">Pengaturan Presensi</label>
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('autoreload', __('Autoreload'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('autoreload', null, ['class' => 'form-control font-style']) }}
                                    @error('autoreload')
                                        <span class="invalid-autoreload" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('presence_ip', __('Presence IP'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('presence_ip', null, ['class' => 'form-control font-style']) }}
                                    @error('presence_ip')
                                        <span class="invalid-presence_ip" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('presence_tolerance', __('Presence Tolerance'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('presence_tolerance', null, ['class' => 'form-control font-style']) }}
                                    @error('presence_tolerance')
                                        <span class="invalid-presence_tolerance" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('working_hours', __('Working Hours'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('working_hours', null, ['class' => 'form-control font-style']) }}
                                    @error('working_hours')
                                        <span class="invalid-working_hours" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('late_fee_in_minutes', __('Late fee in minutes'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('late_fee_in_minutes', null, ['class' => 'form-control font-style']) }}
                                    @error('late_fee_in_minutes')
                                        <span class="invalid-late_fee_in_minutes" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('maximum_late_fee_in_one_day', __('Maximum late fee in one day'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('maximum_late_fee_in_one_day', null, ['class' => 'form-control font-style']) }}
                                    @error('maximum_late_fee_in_one_day')
                                        <span class="invalid-maximum_late_fee_in_one_day" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('daily_no_show_fee', __('Daily no-show fee'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('daily_no_show_fee', null, ['class' => 'form-control font-style']) }}
                                    @error('daily_no_show_fee')
                                        <span class="invalid-daily_no_show_fee" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('late_accumulation', __('Late Accumulation'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('late_accumulation', null, ['class' => 'form-control font-style']) }}
                                    @error('late_accumulation')
                                        <span class="invalid-late_accumulation" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('attendance_approval', __('Attendance approval'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('attendance_approval', null, ['class' => 'form-control font-style']) }}
                                    @error('attendance_approval')
                                        <span class="invalid-attendance_approval" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form-group col-md-3">
                                    {{ Form::label('distance_tolerance', __('Distance tolerance'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('distance_tolerance', null, ['class' => 'form-control font-style']) }}
                                    @error('distance_tolerance')
                                        <span class="invalid-distance_tolerance" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('latitude', __('latitude'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('latitude', null, ['class' => 'form-control font-style']) }}
                                    @error('latitude')
                                        <span class="invalid-latitude" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('longitude', __('longitude'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('longitude', null, ['class' => 'form-control font-style']) }}
                                    @error('longitude')
                                        <span class="invalid-longitude" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('can_access_attendance_in_before', __('Can Access Attendance In Before'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('can_access_attendance_in_before', null, ['class' => 'form-control font-style']) }}
                                    @error('can_access_attendance_in_before')
                                        <span class="invalid-can_access_attendance_in_before" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('can_access_attendance_in_after', __('Can Access Attendance In After'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('can_access_attendance_in_after', null, ['class' => 'form-control font-style']) }}
                                    @error('can_access_attendance_in_after')
                                        <span class="invalid-can_access_attendance_in_after" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group col-md-3">
                                    {{ Form::label('can_access_attendance_out_before', __('Can Access Attendance Out Before'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('can_access_attendance_out_before', null, ['class' => 'form-control font-style']) }}
                                    @error('can_access_attendance_out_before')
                                        <span class="invalid-can_access_attendance_out_before" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('can_access_attendance_out_after', __('Can Access Attendance Out After'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('can_access_attendance_out_after', null, ['class' => 'form-control font-style']) }}
                                    @error('can_access_attendance_out_after')
                                        <span class="invalid-can_access_attendance_out_after" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="form-group col-md-3">
                                    {{ Form::label('rest_time', __('Rest Time'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('rest_time', null, ['class' => 'form-control font-style']) }}
                                    @error('rest_time')
                                        <span class="invalid-rest_time" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    {{ Form::label('attendance_gift', __('Attendance Gift'), ['class' => 'col-form-label']) }}
                                    {{ Form::number('attendance_gift', null, ['class' => 'form-control font-style']) }}
                                    @error('attendance_gift')
                                        <span class="invalid-attendance_gift" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12 mb-4">
                                    <hr>
                                    <label class="text-xl">Pengaturan Lembur</label>
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('overtime_start', __('Overtime Start'), ['class' => 'col-form-label']) }}
                                    {{ Form::time('overtime_start', null, ['class' => 'form-control timepicker_format']) }}
                                    @error('overtime_start')
                                        <span class="invalid-overtime_start" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    {{ Form::label('overtime_max_hours', __('Maximum Overtime'), ['class' => 'col-form-label']) }}
                                    {{ Form::time('overtime_max_hours', null, ['class' => 'form-control timepicker_format']) }}
                                    @error('overtime_max_hours')
                                        <span class="invalid-overtime_max_hours" role="alert">
                                            <small class="text-danger">{{ $message }}</small>
                                        </span>
                                    @enderror
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" id="addSig" type="submit">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div class="" id="email-notification">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Email Notification') }}</h5>
                        </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th class="w-75"> {{ __('Name') }}</th>
                                            <th class="text-center"> {{ __('On/Off') }}</th>
                                        </tr>
                                    </thead>
                                    {{-- <tbody>
                                        @foreach ($EmailTemplates as $EmailTemplate)
                                            <tr class="">
                                                <td>{{ $EmailTemplate->name }}</td>
                                                <td class="text-center">

                                                    <div class="form-group col-md-12">
                                                        <label class="form-check form-switch d-inline-block">
                                                            <input type="checkbox"
                                                                class="email-template-checkbox form-check-input"
                                                                name="{{ $EmailTemplate->id }}"
                                                                @if ($EmailTemplate->template->is_active == 1) checked="checked" @endcan
                                                                value="{{ $EmailTemplate->template->is_active == 1 ? '1' : '0' }}"
                                                                data-url="{{ route('company.email.setting', $EmailTemplate->id) }}">
                                                            <span class="slider1 round"></span>
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="" id="ip-restrict">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">

                            <h5>{{ __('IP Restrict Setting') }}</h5>
                            <a href="#" data-url="{{ route('create.ip') }}" class="btn btn-sm btn-primary"
                                data-bs-toggle="tooltip" data-bs-original-title="{{ __('Create New IP') }}"
                                data-bs-placement="top" data-size="md" data-ajax-popup="true"
                                data-title="{{ __('Create New IP') }}">
                                <i class="ti ti-plus"></i>
                            </a>

                        </div>
                        <div class="card-body table-border-style ">
                            <div class="table-responsive">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th class="w-75"> {{ __('IP') }}</th>
                                            <th width="200px"> {{ 'Action' }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($ips as $ip)
                                            <tr class="Action">
                                                <td class="sorting_1">{{ $ip->ip }}</td>
                                                <td class="">
                                                    @can('Manage Company Settings')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('edit.ip', $ip->id) }}" data-size="md"
                                                                data-ajax-popup="true" data-title="{{ __('Edit IP') }}"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Edit') }}"
                                                                data-bs-placement="top" class="edit-icon"
                                                                data-original-title="{{ __('Edit') }}"><i
                                                                    class="ti ti-pencil text-white"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('Manage Company Settings')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['destroy.ip', $ip->id], 'id' => 'delete-form-' . $ip->id]) !!}
                                                            <a href="#!" data-bs-toggle="tooltip"
                                                                data-bs-original-title="{{ __('Delete') }}"
                                                                data-bs-placement="top"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                                title="{{ __('Delete') }}">
                                                                <i class="ti ti-trash text-white"></i></a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>


                {{-- @dd($settings) --}}
                @if (Auth::user()->type == 'company')
                    <div class="" id="zoom-meeting-setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Zoom Meeting') }}</h5>
                            </div>
                            {{ Form::open(['route' => 'zoom.settings', 'method' => 'post']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{ Form::label('zoom_apikey', __('Zoom API Key'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('zoom_apikey', isset($settings['zoom_apikey']) ? $settings['zoom_apikey'] : '', ['class' => 'form-control ', 'placeholder' => 'Zoom API Key']) }}
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{ Form::label('Zoom Secret Key', __('Zoom Secret Key'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('zoom_secret_key', !empty($settings['zoom_secret_key']) ? $settings['zoom_secret_key'] : '', ['class' => 'form-control', 'placeholder' => 'Zoom Secret Key']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="" id="slack-setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Slack Setting') }}</h5>
                                <small
                                    class="text-secondary font-weight-bold">{{ __('Slack Notification Setting') }}</small>
                            </div>
                            {{ Form::open(['route' => 'slack.setting', 'id' => 'slack-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                        {{ Form::label('Slack Webhook URL', __('Slack Webhook URL'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('slack_webhook', isset($settings['slack_webhook']) ? $settings['slack_webhook'] : '', ['class' => 'form-control w-100', 'placeholder' => __('Enter Slack Webhook URL'), 'required' => 'required']) }}
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                        {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                    </div>
                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Monthly payslip create', __('Create Monthly Payslip '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('monthly_payslip_notification', '1', isset($settings['monthly_payslip_notification']) && $settings['monthly_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'monthly_payslip_notification']) }}
                                                    <label class="col-form-label" for="lead_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Award create', __('Create Award '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('award_notificaation', '1', isset($settings['award_notificaation']) && $settings['award_notificaation'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'award_notificaation']) }}
                                                    <label class="col-form-label" for="award_notificaation"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Ticket create', __('Create Ticket '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('ticket_notification', '1', isset($settings['ticket_notification']) && $settings['ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'ticket_notification']) }}
                                                    <label class="col-form-label" for="ticket_notification"></label>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Announcement create', __('Create Announcement '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">

                                                    {{ Form::checkbox('Announcement_notification', '1', isset($settings['Announcement_notification']) && $settings['Announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'Announcement_notification']) }}
                                                    <label class="col-form-label" for="Announcement_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Holidays create', __('Create Holidays '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('Holiday_notification', '1', isset($settings['Holiday_notification']) && $settings['Holiday_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'Holiday_notification']) }}
                                                    <label class="col-form-label" for="Holiday_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Event create', __('Create Event '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('event_notification', '1', isset($settings['event_notification']) && $settings['event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'event_notification']) }}
                                                    <label class="col-form-label" for="event_notification"></label>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Meeting create', __('Create Meeting '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('meeting_notification', '1', isset($settings['meeting_notification']) && $settings['meeting_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'meeting_notification']) }}
                                                    <label class="col-form-label" for="meeting_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Company policy create', __('Create Company Policy '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('company_policy_notification', '1', isset($settings['company_policy_notification']) && $settings['company_policy_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'company_policy_notification']) }}
                                                    <label class="col-form-label"
                                                        for="company_policy_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>



                    <div class="" id="telegram-setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Telegram Setting') }}</h5>
                                <small
                                    class="text-secondary font-weight-bold">{{ __('Telegram Notification Setting') }}</small>
                            </div>
                            {{ Form::open(['route' => 'telegram.setting', 'id' => 'telegram-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{ Form::label('Telegram Access Token', __('Telegram Access Token'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('telegram_accestoken', isset($settings['telegram_accestoken']) ? $settings['telegram_accestoken'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram AccessToken')]) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{ Form::label('Telegram ChatID', __('Telegram ChatID'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('telegram_chatid', isset($settings['telegram_chatid']) ? $settings['telegram_chatid'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Telegram ChatID')]) }}
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                        {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                    </div>


                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Monthly payslip create', __('Create Monthly Payslip '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_monthly_payslip_notification', '1', isset($settings['telegram_monthly_payslip_notification']) && $settings['telegram_monthly_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_monthly_payslip_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_monthly_payslip_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Award create', __('Create Award '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_award_notification', '1', isset($settings['telegram_award_notification']) && $settings['telegram_award_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_award_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_award_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Ticket create', __('Create Ticket '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_ticket_notification', '1', isset($settings['telegram_ticket_notification']) && $settings['telegram_ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_ticket_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_ticket_notification"></label>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Announcement create', __('Create Announcement'), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_Announcement_notification', '1', isset($settings['telegram_Announcement_notification']) && $settings['telegram_Announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_Announcement_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_Announcement_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Holidays create', __('Create Holidays '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_Holiday_notification', '1', isset($settings['telegram_Holiday_notification']) && $settings['telegram_Holiday_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_Holiday_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_Holiday_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Event create', __('Create Event '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_event_notification', '1', isset($settings['telegram_event_notification']) && $settings['telegram_event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_event_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_event_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Meeting create', __('Create Meeting '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_meeting_notification', '1', isset($settings['telegram_meeting_notification']) && $settings['telegram_meeting_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_meeting_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_meeting_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Company policy create', __('Create Company Policy '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('telegram_company_policy_notification', '1', isset($settings['telegram_company_policy_notification']) && $settings['telegram_company_policy_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'telegram_company_policy_notification']) }}
                                                    <label class="col-form-label"
                                                        for="telegram_company_policy_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <div class="" id="twilio-setting">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('Twilio Setting') }}</h5>
                                <small
                                    class="text-secondary font-weight-bold">{{ __('Twilio Notification Setting') }}</small>
                            </div>
                            {{ Form::open(['route' => 'twilio.setting', 'id' => 'twilio-setting', 'method' => 'post', 'class' => 'd-contents']) }}
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{ Form::label('Twilio SID', __('Twilio SID'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('twilio_sid', isset($settings['twilio_sid']) ? $settings['twilio_sid'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio Sid')]) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{ Form::label('Twilio Token', __('Twilio Token'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('twilio_token', isset($settings['twilio_token']) ? $settings['twilio_token'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio Token')]) }}
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        {{ Form::label('Twilio From', __('Twilio From'), ['class' => 'col-form-label']) }}
                                        {{ Form::text('twilio_from', isset($settings['twilio_from']) ? $settings['twilio_from'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Twilio From')]) }}
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 form-group mb-3">
                                        {{-- {{ Form::label('Module Setting', __('Module Setting'), ['class' => 'col-form-label']) }} --}}
                                    </div>


                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Payslip create', __('Create Payslip '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_payslip_notification', '1', isset($settings['twilio_payslip_notification']) && $settings['twilio_payslip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_payslip_notification']) }}
                                                    <label class="col-form-label"
                                                        for="twilio_payslip_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Leave Approve/Reject', __('Leave Approve/Reject'), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_leave_approve_notification', '1', isset($settings['twilio_leave_approve_notification']) && $settings['twilio_leave_approve_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_leave_approve_notification']) }}
                                                    <label class="col-form-label"
                                                        for="twilio_leave_approve_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Ticket create', __('Create Ticket '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_ticket_notification', '1', isset($settings['twilio_ticket_notification']) && $settings['twilio_ticket_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_ticket_notification']) }}
                                                    <label class="col-form-label"
                                                        for="twilio_ticket_notification"></label>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Award create', __('Create Award '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_award_notification', '1', isset($settings['twilio_award_notification']) && $settings['twilio_award_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_award_notification']) }}
                                                    <label class="col-form-label" for="twilio_award_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Trip create', __('Create Trip '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_trip_notification', '1', isset($settings['twilio_trip_notification']) && $settings['twilio_trip_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_trip_notification']) }}
                                                    <label class="col-form-label" for="twilio_trip_notification"></label>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="col-md-4">
                                        <ul class="list-group">
                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Event create', __('Create Event '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_event_notification', '1', isset($settings['twilio_event_notification']) && $settings['twilio_event_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_event_notification']) }}
                                                    <label class="col-form-label" for="twilio_event_notification"></label>
                                                </div>
                                            </li>

                                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                                {{ Form::label('Announcement create', __('Create Announcement '), ['class' => 'col-form-label']) }}
                                                <div class="form-check form-switch d-inline-block float-right">
                                                    {{ Form::checkbox('twilio_announcement_notification', '1', isset($settings['twilio_announcement_notification']) && $settings['twilio_announcement_notification'] == '1' ? 'checked' : '', ['class' => 'form-check-input', 'id' => 'twilio_announcement_notification']) }}
                                                    <label class="col-form-label"
                                                        for="twilio_announcement_notification"></label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    {{ __('Save Changes') }}
                                </button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
