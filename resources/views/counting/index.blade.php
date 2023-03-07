@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Counting') }}
@endsection

@section('action-button')
    @can('Create Counting')
        <a href="#" data-url="{{ route('counting.create') }}" data-ajax-popup="true" data-title="{{ __('Create Counting') }}"
            data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Counting') }}</li>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Group') }}</th>
                                <th>{{ __('Salary') }}</th>
                                <th>{{ __('Start Year') }}</th>
                                <th>{{ __('Max Year') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="font-style">
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item['group_name'] }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($item['salary'] as $x)
                                                <li>{{ $x['id'] . ' : ' . Auth::user()->priceFormat($x['salary']) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>{{ $item['start_year'] }}</td>
                                    <td>{{ $item['max_year'] }}</td>
                                    <td>
                                        @can('Edit Counting')
                                            <a href="#" data-url="{{ URL::to('counting/' . $item['id'] . '/edit') }}"
                                                data-size="lg" data-ajax-popup="true" data-title="{{ __('Edit Position') }}"
                                                class="edit-icon" data-toggle="tooltip"
                                                data-original-title="{{ __('Edit') }}"><i class="fas fa-pencil-alt"></i></a>
                                        @endcan
                                        @can('Delete Counting')
                                            <a href="#" class="delete-icon" data-toggle="tooltip"
                                                data-original-title="{{ __('Delete') }}"
                                                data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="document.getElementById('delete-form-{{ $item['id'] }}').submit();"><i
                                                    class="fas fa-trash"></i></a>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['counting.destroy', $item['id']],
                                                'id' => 'delete-form-' . $item['id'],
                                            ]) !!}
                                            {!! Form::close() !!}
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
@endsection
