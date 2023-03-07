@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Education') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Education') }}</li>
@endsection

@section('action-button')

    <div class="row align-items-center m-1">
        @can('Create Education')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Education') }}" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create Education') }}" data-url="{{ route('education.create') }}"><i
                        class="ti ti-plus text-white"></i></a>
            </div>
        @endcan
    </div>

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Education') }}</li>
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
                                <th>{{ __('Education') }}</th>
                                <th width="250px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($educations as $education)
                                <tr>
                                    <td>{{ $education->name }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Education')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md"
                                                        data-url="{{ URL::to('education/' . $education->id . '/edit') }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Education') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit Education') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Education')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['education.destroy', $education->id]]) !!}
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
