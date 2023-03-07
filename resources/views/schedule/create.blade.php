<div class="card bg-none card-box">
    {{Form::open(array('url'=>'schedule','method'=>'post'))}}
    <div class="row">
        {{-- <div class="col-md-6">
            <div class="form-group">
                {{Form::label('title',__('Announcement Title'),['class'=>'form-label'])}}
                {{Form::text('title',null,array('class'=>'form-control','placeholder'=>__('Enter Announcement Title')))}}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('branch_id',__('Branch'),['class'=>'form-label'])}}
                <select class="form-control select2" name="branch_id" id="branch_id" placeholder="Select Branch">
                    <option value="">{{__('Select Branch')}}</option>
                    {{-- <option value="0">{{__('All Branch')}}</option> --}}
                    @foreach($branch as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('department_id',__('Department'),['class'=>'form-label'])}}
                <select class="form-control select2" name="department_id[]" id="department_id" placeholder="Select Department" multiple>

                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('employee_id',__('Employee'),['class'=>'form-label'])}}
                <select class="form-control select2" name="employee_id[]" id="employee_id" placeholder="Select Employee" multiple>

                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('schedule_type',__('Schedule Type'),['class'=>'form-label'])}}
                <select class="form-control select2" name="schedule_type" id="type_id" placeholder="Select Room">
                    <option value="">{{__('Select Schedule Type')}}</option>
                    <option value="day">{{ __('Day') }}</option>
                    <option value="date">{{ __('Date') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('room_id',__('Room Type'),['class'=>'form-label'])}}
                <select class="form-control select2" name="room_id" id="room_id" placeholder="Select Room">
                    <option value="">{{__('Select Room')}}</option>
                    {{-- <option value="0">{{__('All Room')}}</option> --}}
                    @foreach($roomtype as $roomtype)
                        <option value="{{ $roomtype->id }}">{{ $roomtype->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('shift_id',__('Shift'),['class'=>'form-label'])}}
                <select class="form-control select2" name="shift_id" id="shift_id" placeholder="Select Shift">
                    <option value="">{{__('Select Shift')}}</option>
                    {{-- <option value="0">{{__('All Shift')}}</option> --}}
                    @foreach($shift as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12" id="day" style="display: none;">
            <div class="form-group">
                {{Form::label('day_id',__('Day'),['class'=>'form-label'])}}
                <select class="form-control select2" name="day_id[]" id="day_id" placeholder="Select Room" multiple>
                    <option value="">{{__('Select Day')}}</option>
                    <option value="1">{{ __('Sunday') }}</option>
                    <option value="2">{{ __('Monday') }}</option>
                    <option value="3">{{ __('Tuesday') }}</option>
                    <option value="4">{{ __('Wednesday') }}</option>
                    <option value="5">{{ __('Thursday') }}</option>
                    <option value="6">{{ __('Friday') }}</option>
                    <option value="7">{{ __('Saturday') }}</option>
                </select>
            </div>
        </div>
        <div class="col-md-6" id="repeat_check" style="display: none;">
            <div class="form-group custom-control custom-switch">
                {{-- <input type="checkbox" class="custom-control-input" name="ip_restrict" id="ip_restrict" {{ $settings['ip_restrict'] == 'on' ? 'checked="checked"' : '' }} > --}}
                <input type="checkbox" class="custom-control-input" name="repeat" id="repeat" value="on">
                <label class="custom-control-label form-label" for="repeat">{{__('Repeat')}}</label>
            </div>
        </div>
        <div class="col-md-6" id="date" style="display: none;">
            <div class="form-group">
                {{Form::label('date',__('Date'),['class'=>'form-label'])}}
                {{Form::text('date',null,array('class'=>'form-control datepicker'))}}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                {{Form::label('time',__('Time'),['class'=>'form-label'])}}
                {{Form::text('time',null,array('class'=>'form-control'))}}
            </div>
        </div> --}}
        <div class="col-12">
            <input type="submit" value="{{__('Create')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{Form::close()}}
</div>
