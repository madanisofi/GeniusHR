@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Bulk Attendance') }}
@endsection
@push('script-page')
    <script>
        $('#present_all').click(function(event) {
            if (this.checked) {
                $('.present').each(function() {
                    this.checked = true;
                });

                $('.present_check_in').removeClass('d-none');
                $('.present_check_in').addClass('d-block');

            } else {
                $('.present').each(function() {
                    this.checked = false;
                });
                $('.present_check_in').removeClass('d-block');
                $('.present_check_in').addClass('d-none');

            }
        });

        $('.present').click(function(event) {
            var div = $(this).parent().parent().parent().parent().find('.present_check_in');
            if (this.checked) {
                div.removeClass('d-none');
                div.addClass('d-block');

            } else {
                div.removeClass('d-block');
                div.addClass('d-none');
            }

        });
    </script>
@endpush

@section('action-button')
@endsection

@section('content')
    <div class="col">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'get', 'id' => 'bulkattendance_filter']) }}
                <div class="d-flex align-items-center justify-content-end">
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                            {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), ['class' => 'month-btn form-control']) }}
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                            {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control', 'required']) }}
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <div class="btn-box">
                            {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                            {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control', 'required']) }}
                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="row">
                            <div class="col-auto"> <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('bulkattendance_filter').submit(); return false;"
                                    data-toggle="tooltip" data-original-title="{{ __('Apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('timesheet.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash text-white-off"></i></span>
                                </a>
                            </div>
                        </div>


                    </div>

                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'post']) }}
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th width="10%">{{ __('Employee Id') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Branch') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>
                                    <div class="form-group my-auto">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" type="checkbox" name="present_all"
                                                id="present_all" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="present_all">
                                                {{ __('Attendance') }}</label>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                @php
                                    $attendance = $employee->present_status($employee->id, isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'));
                                @endphp
                                <tr>
                                    <td class="Id">
                                        <input type="hidden" value="{{ $employee->id }}" name="employee_id[]">
                                        <a href="{{ route('employee.show', Crypt::encrypt($employee->id)) }}"
                                            class="">{{ Auth::user()->employeeIdFormat($employee->employee_id) }}</a>
                                    </td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</td>
                                    <td>{{ !empty($employee->department) ? $employee->department->name : '' }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input present" type="checkbox"
                                                            name="present-{{ $employee->id }}"
                                                            id="present{{ $employee->id }}"
                                                            {{ !empty($attendance) && $attendance->status == 'Present' ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                            for="present{{ $employee->id }}"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-8 present_check_in {{ empty($attendance) ? 'd-none' : '' }} ">
                                                <div class="row">
                                                    <label class="col-md-2 control-label">{{ __('In') }}</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control timepicker"
                                                            name="in-{{ $employee->id }}"
                                                            value="{{ !empty($attendance) && $attendance->clock_in != '00:00:00' ? $attendance->clock_in : \Utility::getValByName('company_start_time') }}">
                                                    </div>

                                                    <label for="inputValue"
                                                        class="col-md-2 control-label">{{ __('Out') }}</label>
                                                    <div class="col-md-4">
                                                        <input type="text" class="form-control timepicker"
                                                            name="out-{{ $employee->id }}"
                                                            value="{{ !empty($attendance) && $attendance->clock_out != '00:00:00' ? $attendance->clock_out : \Utility::getValByName('company_end_time') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="attendance-btn float-end pt-4">
                    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
                    <input type="hidden" value="{{ isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') }}"
                        name="date">
                    <input type="hidden" value="{{ isset($_GET['branch']) ? $_GET['branch'] : '' }}" name="branch">
                    <input type="hidden" value="{{ isset($_GET['department']) ? $_GET['department'] : '' }}"
                        name="department">
                    {{ Form::close() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            $('.daterangepicker').daterangepicker({
                format: 'yyyy-mm-dd',
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        });
    </script>
@endpush
