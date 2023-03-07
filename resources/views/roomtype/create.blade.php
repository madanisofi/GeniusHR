{{ Form::open(['url' => 'roomtype']) }}
<div class="modal-body">

    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Room Type Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Room Type Name']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
</div>

{{ Form::close() }}
