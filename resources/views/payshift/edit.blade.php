{{ Form::model($payshift, ['route' => ['payshift.update', $payshift->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('shift_id', __('Shift'), ['class' => 'form-label']) }}
            {{ Form::select('shift_id', $shift, null, ['class' => 'form-control select2', 'disabled']) }}
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label']) }}
                {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
