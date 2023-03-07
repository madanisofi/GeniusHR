@extends('layouts.admin')
@section('page-title')
    {{ __('Create Schedule') }}
@endsection
@section('content')
    <div class="col">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['url' => 'schedule', 'method' => 'post']) }}
                <input type="hidden" name="day_on_month" value="{{ $day_on_this_month }}" readonly>
                <input type="hidden" name="month" id="month" value="{{ $month }}" readonly>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('branch_id', __('Branch'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="branch_id" id="branch_id"
                                placeholder="Select Branch">
                                <option value="">{{ __('Select Branch') }}</option>
                                @foreach ($branch as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="department_id[]" id="department_id"
                                placeholder="Select Department" multiple>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="employee_id" id="employee_id"
                                placeholder="Select Employee">

                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 py-5">
                        <div class="form-check form-switch ">
                            <input type="checkbox" class="form-check-input" name="repeat" id="repeat"
                                value="on">
                            <label class="form-label" for="repeat">{{ __('Repeat') }}</label>
                        </div>
                    </div>
                    <div class="col-md-5" id="show_room">
                        {{ Form::label('room', __('Room'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="room" id="room">
                            <option value="">{{ __('Select Room') }}</option>
                            @foreach ($roomtype as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5" id="show_shift">
                        {{ Form::label('shift', __('Shift'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="shift[]" id="shift" multiple>
                            <option value="">{{ __('Select Shift') }}</option>
                            @foreach ($shift as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->start_time }} -
                                    {{ $item->end_time }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12" id="schedule_by_date">
                        <div class="form-group">
                            <h6 class="my-3">{{ __('Manage Schedule') }} </h6>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Day') }} </th>
                                        <th>{{ __('Room') }}</th>
                                        <th>{{ __('Shift') }} </th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @for ($i = 1; $i <= $day_on_this_month; $i++)
                                        @php
                                            $date = date('Y-m-', strtotime($month)) . $i;
                                        @endphp
                                        <tr>
                                            <td>{{ date('d-F-Y', strtotime($date)) }}</td>
                                            <td>
                                                <input type="hidden" name="{{ $i }}date"
                                                    value="{{ $date }}" readonly>
                                                <select class="form-control select2" name="{{ $i }}room"
                                                    id="{{ $i }}room" placeholder="Select Branch">
                                                    <option value="">{{ __('Select Room') }}</option>
                                                    @foreach ($roomtype as $item)
                                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    @foreach ($shift as $item)
                                                        <div class="col-md-4 custom-control custom-checkbox">
                                                            {{ Form::checkbox($i . 'shift[]', $item->id, false, ['class' => 'custom-control-input', 'id' => $i . 'shift' . $item->id]) }}
                                                            {{ Form::label($i . 'shift' . $item->id, $item->name . ' (' . $item->start_time . ' - ' . $item->end_time . ')', ['class' => 'form-label font-weight-500']) }}<br>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endfor

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" value="{{ __('Create') }}"
                            class="btn btn-primary radius-10px float-right">
                        <input type="button" value="{{ __('Cancel') }}"
                            class="btn btn-light radius-10px float-right" data-dismiss="modal">
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="card bg-none card-box">

    </div>

@endsection

@push('script-page')
    <script>
        //Branch Wise Deapartment Get
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);

            $('#show_room').hide();
            $('#show_shift').hide();
            $('#schedule_by_date').show();
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

        $(document).on('change', '#repeat', function() {
            var repeat = document.getElementById('repeat');
            if (repeat.checked == true) {
                console.log('on');

                $('#show_room').show();
                $('#show_shift').show();
                $('#schedule_by_date').hide();
            } else {
                console.log('off');

                $('#show_room').hide();
                $('#show_shift').hide();
                $('#schedule_by_date').show();
            }
        })

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
                    $('#employee_id').append('<option value="0"> {{ __('All Employee') }} </option>');

                    $.each(data, function(key, value) {
                        $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
