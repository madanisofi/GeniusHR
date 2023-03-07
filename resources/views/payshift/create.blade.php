{{ Form::open(['url' => 'payshift', 'method' => 'post']) }}
{{ Form::hidden('employee_id', $employee->id, []) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('shift_id', __('Shift'), ['class' => 'form-label']) }}
            {{ Form::select('shift_id', $shift, null, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>
        <div class="form-group">
            {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
            {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
