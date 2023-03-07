{{ Form::model($employee, ['route' => ['employee.consumption.update', $employee->id], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('consumption_fee', __('Consumption'), ['class' => 'form-label']) }}
            {{ Form::number('consumption_fee', null, ['class' => 'form-control ', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <button type="submit" class="btn  btn-primary">{{ __('Save') }}</button>
</div>
{{ Form::close() }}
