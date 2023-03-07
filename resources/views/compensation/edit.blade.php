{{ Form::model($compensation, ['route' => ['compensation.update', $compensation->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Compesation Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-6">
            {{ Form::label('attendance_option', __('Used as presence'), ['class' => 'form-label']) }}
            <select class="form-control select2" required name="attendance_option">
                <option value="no" {{ $compensation->attendance_option != 'yes' ? 'selected' : '' }}>
                    {{ __('No') }}</option>
                <option value="yes" {{ $compensation->attendance_option == 'yes' ? 'selected' : '' }}>
                    {{ __('Yes') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>

{{ Form::close() }}
