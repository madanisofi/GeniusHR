@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Additional Information') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Additional Information') }}</li>
@endsection

@section('action-button')
    <div class="row align-items-center m-1">
        @can('Create Additional Information')
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="{{ __('Create Additional Information') }}" data-ajax-popup="true" data-size="md"
                    data-title="{{ __('Create Additional Information') }}" data-url="{{ route('additional.create') }}"><i
                        class="ti ti-plus text-white"></i></a>
            </div>
        @endcan
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Additional Information') }}</li>
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
                                <th>{{ __('Additional Information') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Required Field') }}</th>
                                <th>{{ __('Can Insert') }}</th>
                                <th>{{ __('Send Notification') }}</th>
                                <th>{{ __('Reminder') }}</th>
                                @if (Gate::check('Edit Additional Information') || Gate::check('Delete Additional Information'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($additionals as $additional)
                                <tr>
                                    <td>{{ $additional->name }}</td>
                                    <td>{{ strtoupper($additional->type) }}</td>
                                    <td>
                                        <h6 class="float-left mr-1">
                                            @if ($additional->is_required == 1)
                                                <div class="badge bg-success p-2 px-3 rounded">{{ __('Required') }}</div>
                                            @else
                                                <div class="badge bg-danger p-2 px-3 rounded">{{ __('Not Required') }}
                                                </div>
                                            @endif
                                        </h6>
                                    </td>
                                    <td>{{ $additional->can_insert == 1 ? __('Could') : __('Can Not') }}</td>
                                    <td>{{ $additional->send_notification == 1 ? __('Yes') : __('No') }}</td>
                                    <td>{{ $additional->reminder . ' ' . __('Day') }}</td>
                                    <td class="Action">
                                        <span>
                                            @can('Edit Additional Information')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md"
                                                        data-url="{{ URL::to('additional/' . $additional->id . '/edit') }}"
                                                        data-ajax-popup="true"
                                                        data-title="{{ __('Edit Additional Information') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Edit Additional Information') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Additional Information')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['additional.destroy', $additional->id]]) !!}
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
