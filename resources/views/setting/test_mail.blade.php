{{ Form::open(['route' => ['test.send.mail']]) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'col-form-label']) }}
            {{ Form::text('email', '', ['class' => 'form-control', 'required' => 'required' , 'placeholder'=>'Enter yore test email']) }}
            @error('email')
                <span class="invalid-email" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Send') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
