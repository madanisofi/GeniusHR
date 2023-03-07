@extends('layouts.admin')
@section('page-title')
    {{ __('Edit Schedule') }}
@endsection
@section('content')
    <div class="col">
        <div class="card">
            <div class="card-body">
                {{ Form::model($schedule, ['route' => ['schedule.update', $schedule->id], 'method' => 'PUT']) }}
                <input type="hidden" name="month" value="{{ $schedule->month != '' ? $schedule->month : date('Y-m') }}">
                <input type="hidden" name="day_on_month" value="{{ $day_on_this_month }}">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                            {{ Form::text('employee', null, ['class' => 'form-control', 'readonly']) }}
                        </div>
                    </div>

                    <div class="col-md-2 py-5">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" name="repeat" id="repeat" value="on"
                                {{ $schedule->repeat == 'on' ? 'checked="checked"' : '' }}>
                            <label class="form-label" for="repeat">{{ __('Repeat') }}</label>
                        </div>
                    </div>

                    @if ($schedule->day != null and $schedule->repeat != 'on')
                        <div class="col-md-5" id="show_room_new">
                            {{ Form::label('room', __('Room'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="room" id="room">
                                <option value="">{{ __('Select Room') }}</option>
                                @foreach ($roomtype as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5" id="show_shift_new">
                            {{ Form::label('shift', __('Shift'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="shift" id="shift">
                                <option value="">{{ __('Select Shift') }}</option>
                                @foreach ($shift as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->start_time }} -
                                        {{ $item->end_time }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12" id="schedule_by_date_new">
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
                                                            <option value="{{ $item->id }}"
                                                                {{ in_array($item->id, $room[$date]) ? 'selected' : '' }}>
                                                                {{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        @foreach ($shift as $item)
                                                            <div class="col-md-4 form-check form-switch">
                                                                {{ Form::checkbox($i . 'shift[]', $item->id, in_array($item->id, $day[$date]) ? true : false, ['class' => 'form-check-input', 'id' => $i . 'shift' . $item->id]) }}
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
                    @else
                        <div class="col-md-5" id="show_room">
                            {{ Form::label('room', __('Room'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="room" id="room">
                                <option value="">{{ __('Select Room') }}</option>
                                @foreach ($roomtype as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == $schedule->room_id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5" id="show_shift">
                            {{ Form::label('shift', __('Shift'), ['class' => 'form-label']) }}
                            <select class="form-control select2" name="shift[]" id="shift" multiple>
                                <option value="">{{ __('Select Shift') }}</option>
                                @foreach ($shift as $item)
                                    {{-- <option value="{{ $item->id }}" {{ ($item->id == $schedule->shift_id ? 'selected' : '') }}>{{ $item->name }} ({{ $item->start_time }} - {{ $item->end_time }})</option> --}}
                                    <option value="{{ $item->id }}" {{ in_array($item->id, $day) ? 'selected' : '' }}>
                                        {{ $item->name }}
                                        ({{ $item->start_time }} - {{ $item->end_time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12" id="schedule_by_date" style="display: none">
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
                                                            <option value="{{ $item->id }}">{{ $item->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        @foreach ($shift as $item)
                                                            <div class="col-md-4 form-check form-switch">
                                                                {{ Form::checkbox($i . 'shift[]', $item->id, false, ['class' => 'form-check-input', 'id' => $i . 'shift' . $item->id]) }}
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
                    @endif

                    <div class="col-12">
                        <input type="submit" value="{{ __('Update') }}" class="btn btn-primary radius-10px float-right">
                        <a href="{{ route('schedule.index') }}" class="btn btn-light radius-10px float-right">{{ __('Cancel') }}</a>

                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            $('#show_room_new').hide();
            $('#show_shift_new').hide();
            $('#schedule_by_date_new').show();
        });

        $(document).on('change', '#repeat', function() {
            var repeat = document.getElementById('repeat');
            if (repeat.checked == true) {
                console.log('on');

                $('#show_room').show();
                $('#show_shift').show();
                $('#schedule_by_date').hide();

                $('#show_room_new').show();
                $('#show_shift_new').show();
                $('#schedule_by_date_new').hide();
            } else {
                console.log('off');

                $('#show_room').hide();
                $('#show_shift').hide();
                $('#schedule_by_date').show();

                $('#show_room_new').hide();
                $('#show_shift_new').hide();
                $('#schedule_by_date_new').show();
            }
        })
    </script>
@endpush
