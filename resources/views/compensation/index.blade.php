@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Overtime Compensation') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Overtime Compensation') }}</li>
@endsection

@section('action-button')

    <div class="row align-items-center m-1">
        @can('Create Overtime Compensation')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Overtime Compensation') }}" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create Overtime Compensation') }}" data-url="{{ route('compensation.create') }}"><i
                        class="ti ti-plus text-white"></i></a>
            </div>
        @endcan
    </div>

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Overtime Compensation') }}</li>
@endsection

@section('content')
    <div class="col-3">
        @include('layouts.hrm_setup')
    </div>
    <div class="col-9">
        <div class="card">
            <div class="card-body table-border-style">

                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Overtime Compensation') }}</th>
                                <th>{{ __('Used as presence') }}</th>
                                <th width="250px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overtimeCompensation as $compensation)
                                <tr>
                                    <td>{{ $compensation->name }}</td>
                                    <td>{{ Str::ucfirst($compensation->attendance_option) }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Overtime Compensation')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md"
                                                        data-url="{{ URL::to('compensation/' . $compensation->id . '/edit') }}"
                                                        data-ajax-popup="true"
                                                        data-title="{{ __('Edit Overtime Compensation') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit Overtime Compensation') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Overtime Compensation')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['compensation.destroy', $compensation->id]]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                    {!! Form::close() !!}
                                                </div>
                                @endif
                                </span>
                                </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection
