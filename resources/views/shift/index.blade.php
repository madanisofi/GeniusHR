@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Shift') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Shift') }}</li>
@endsection

@section('action-button')

    <div class="row align-items-center m-1">
        @can('Create Shift')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Shift') }}" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Shift') }}"
                    data-url="{{ route('shift.create') }}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endcan
    </div>

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Shift') }}</li>
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
                                <th>{{ __('Shift') }}</th>
                                <th>{{ __('Start Time') }}</th>
                                <th>{{ __('End Time') }}</th>
                                <th width="250px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shift_get as $shift)
                                <tr>
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ $shift->start_time }}</td>
                                    <td>{{ $shift->end_time }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Shift')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md"
                                                        data-url="{{ URL::to('shift/' . $shift->id . '/edit') }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Shift') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit Shift') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Shift')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['shift.destroy', $shift->id]]) !!}
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
