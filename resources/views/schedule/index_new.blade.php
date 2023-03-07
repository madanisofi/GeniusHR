@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Schedule') }}
@endsection

@section('action-button')
@endsection

@section('content')

    <div class="col">
        <div class="card">
            <div class="card-body">
                @if ($level > 0)
                    @if ($get_access == 'open')
                        @can('Create Schedule')
                            {{ Form::open(['route' => ['schedule.create_schedule.new'], 'method' => 'GET', 'id' => 'schedule_form']) }}
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="col-xl-3 col-lg-3 col-md-6 mx-2">
                                    <div class="btn-box">
                                        {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
                                        {{ Form::select('month', $month, null, ['class' => 'form-control month']) }}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 mx-2">
                                    <div class="btn-box">
                                        {{ Form::label('year', __('Select Year'), ['class' => 'form-label']) }}
                                        {{ Form::select('year', $year, null, ['class' => 'form-control year']) }}
                                    </div>
                                </div>
                                <div class="col-auto text-right payslip-btn">
                                    <a href="#" class="btn btn-primary"
                                        onclick="document.getElementById('schedule_form').submit(); return false;">
                                        {{ __('Create') }}
                                    </a>
                                </div>
                            </div>
                            {{ Form::close() }}
                        @endcan
                    @endif
                @else
                    @can('Create Schedule')
                        {{ Form::open(['route' => ['schedule.create_schedule.new'], 'method' => 'GET', 'id' => 'schedule_form']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 mx-2">

                                <div class="btn-box">
                                    {{ Form::label('month', __('Select Month'), ['class' => 'form-label']) }}
                                    {{ Form::select('month', $month, null, ['class' => 'form-control month']) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 mx-2">

                                <div class="btn-box">
                                    {{ Form::label('year', __('Select Year'), ['class' => 'form-label']) }}
                                    {{ Form::select('year', $year, null, ['class' => 'form-control year']) }}
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <a href="#" class="btn btn-primary"
                                    onclick="document.getElementById('schedule_form').submit(); return false;">
                                    {{ __('Create') }}
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    @endcan
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Month') }}</th>
                                <th>{{ __('Repeat') }}</th>
                                {{-- @if (Gate::check('Edit Schedule') || Gate::check('Delete Schedule')) --}}
                                @if ($level > 0)
                                    @if ($get_access == 'open')
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                @else
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody class="font-style">
                            @foreach ($schedules as $x => $schedule)
                                <tr>
                                    <td>{{ ucfirst($schedule->employee) }}</td>
                                    <td>{{ $schedule->month != '' ? $schedule->month : 'All Time' }}</td>
                                    <td>{{ $schedule->repeat }}</td>
                                    @if ($level > 0)
                                        @if ($get_access == 'open')
                                            <td>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{ route('schedule.show', $schedule->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"><i
                                                            class="ti ti-eye text-white"></i></a>
                                                </div>
                                                @can('Edit Schedule')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ URL::to('schedule/' . $schedule->id . '/edit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endcan
                                                @can('Delete Schedule')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['schedule.destroy', $schedule->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </td>
                                        @endif
                                    @else
                                        <td>
                                            <div class="action-btn bg-primary ms-2">
                                                <a href="{{ route('schedule.show', $schedule->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"><i
                                                        class="ti ti-eye text-white"></i></a>
                                            </div>

                                            @can('Edit Schedule')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="{{ URL::to('schedule/' . $schedule->id . '/edit') }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Schedule')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['schedule.destroy', $schedule->id]]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Delete') }}">
                                                        <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
                                        </td>
                                    @endif
                                    {{-- @endif --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        //Branch Wise Deapartment Get
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });

        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '{{ route('schedule.getdepartment') }}',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data);
                    $('#department_id').empty();
                    $('#department_id').append('<option value="">{{ __('Select Department') }}</option>');

                    $('#department_id').append('<option value="0"> {{ __('All Department') }} </option>');
                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }

        $(document).on('change', '#department_id', function() {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        $(document).on('change', '#type_id', function() {
            var type = $(this).val();

            if (type == 'day') {
                $('#date').css("display", "none");
                $('#day').css("display", "inline");
                $('#repeat_check').css("display", "inline");
            } else if (type == 'date') {
                $('#date').css("display", "inline");
                $('#day').css("display", "none");
                $('#repeat_check').css("display", "none");
            } else {
                $('#date').css("display", "none");
                $('#day').css("display", "none");
                $('#repeat_check').css("display", "none");
            }
        });

        function getEmployee(did) {

            $.ajax({
                url: '{{ route('schedule.getemployee') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('#employee_id').empty();
                    $('#employee_id').append('<option value="">{{ __('Select Employee') }}</option>');
                    // $('#employee_id').append('<option value="0"> {{ __('All Employee') }} </option>');

                    $.each(data, function(key, value) {
                        $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
