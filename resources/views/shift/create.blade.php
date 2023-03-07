{{ Form::open(['url' => 'shift']) }}
<div class="modal-body">

    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Shift Name'), ['class' => 'col-form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Shift name']) }}
        </div>
        <div class="form-group col-6">
            {{ Form::label('start_time', __('Start Time'), ['class' => 'form-label']) }}
            {{ Form::time('start_time', null, ['class' => 'form-control', 'placeholder' => __('Enter Start Time')]) }}
            @error('start_time')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group col-6">
            {{ Form::label('end_time', __('End Time'), ['class' => 'form-label']) }}
            {{ Form::time('end_time', null, ['class' => 'form-control', 'placeholder' => __('Enter End Time')]) }}
            @error('end_time')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Create') }}</button>
</div>

{{ Form::close() }}
