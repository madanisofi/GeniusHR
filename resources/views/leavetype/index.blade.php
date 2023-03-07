@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Leave Type') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave Type') }}</li>
@endsection

@section('action-button')
    @can('Create Branch')
        <a href="#" data-url="{{ route('leavetype.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Leave Type') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
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
                                <th>{{ __('Leave Type') }}</th>
                                <th>{{ __('Days / Year') }}</th>
                                <th>{{ __('Select All Option') }}</th>
                                <th>{{ __('Parent') }}</th>
                                <th>{{ __('Reduction') }} <small>({{ __('Day') }})</small></th>
                                <th>{{ __('Initial Month Valid') }}</th>
                                <th>{{ __('Last Month Valid') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $leavetype)
                                <tr>
                                    <td>{{ $leavetype['title'] }}</td>
                                    <td>{{ $leavetype['days'] }}</td>
                                    <td>{{ $leavetype['select_all'] }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($leavetype['parent'] as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $leavetype['reduction'] }}</td>
                                    <td>{{ $leavetype['start_date'] }}</td>
                                    <td>{{ $leavetype['end_date'] }}</td>

                                    <td class="Action">
                                        <span>
                                            @can('Edit Leave Type')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('leavetype/' . $leavetype['id'] . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Edit Leave Type') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Leave Type')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['leavetype.destroy', $leavetype['id']],
                                                        'id' => 'delete-form-' . $leavetype['id'],
                                                    ]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i class="ti ti-trash text-white "></i></a>
                                                    </form>
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