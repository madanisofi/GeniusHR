{{ Form::open(['url' => 'attendanceemployee', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
            {{ Form::date('date', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('clock_in', __('Clock In'), ['class' => 'form-label']) }}
            {{ Form::time('clock_in', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('clock_out', __('Clock Out'), ['class' => 'form-label']) }}
            {{ Form::time('clock_out', '00:00', ['class' => 'form-control']) }}
        </div>
        @if ($shift_setting == 'on')
            <div class="form-group col-lg-6 col-md-6">
                {{ Form::label('shift_id', __('Shift'), ['class' => 'form-label']) }}
                {{ Form::select('shift_id', $shift, null, ['class' => 'form-control select2']) }}
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
</div>
{{ Form::close() }}
