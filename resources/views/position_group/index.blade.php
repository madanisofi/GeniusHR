@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Position') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ __('Home') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('Position') }}</li>
@endsection

@section('action-button')
    <div class="row align-items-center m-1">
        @can('Create Position Group')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Position Group') }}" data-ajax-popup="true" data-size="lg"
                    data-title="{{ __('Create Position Group') }}" data-url="{{ route('positiongroup.create') }}"><i
                        class="ti ti-plus text-white"></i></a>
            </div>
        @endcan
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Position') }}</li>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">

                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Position') }}</th>
                                <th>{{ __('Group') }}</th>
                                <th width="250px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td style="white-space: inherit">
                                        @foreach ($item['position'] as $x)
                                            <label
                                                class="badge rounded p-2 m-1 px-3 bg-primary ">{{ $x }}</label>
                                        @endforeach
                                    </td>
                                    <td style="white-space: inherit">
                                        @foreach ($item['group'] as $x)
                                            <label
                                                class="badge rounded p-2 m-1 px-3 bg-primary ">{{ $x }}</label>
                                        @endforeach
                                    </td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Position Group')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="lg"
                                                        data-url="{{ URL::to('positiongroup/' . $item['id'] . '/edit') }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Position') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit Position') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Position Group')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['positiongroup.destroy', $item['id']]]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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
