{{ Form::open(['url' => 'compensation']) }}
<div class="modal-body">

    <div class="row">
        <div class="form-group col-6">
            {{ Form::label('name', __('Compensation Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Compensation name']) }}
        </div>
        <div class="form-group col-6">
            {{ Form::label('attendance_option', __('Used as presence'), ['class' => 'col-form-label']) }}
            <select class="form-control select2" required name="attendance_option">
                <option value="no">{{ __('No') }}</option>
                <option value="yes">{{ __('Yes') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
</div>

{{ Form::close() }}
