{{ Form::model($permissiontype, ['route' => ['permissiontype.update', $permissiontype->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Title')]) }}
                @error('title')
                    <span class="invalid-title" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {{ Form::label('days', __('Days'), ['class' => 'form-label']) }}
                {{ Form::text('days', null, ['class' => 'form-control', 'placeholder' => __('Enter Days')]) }}
                @error('days')
                    <span class="invalid-days" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3 py-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="many_submission" id="many_submission"
                    value="yes" {{ $permissiontype->many_submission == 'yes' ? 'checked="checked"' : '' }}>
                <label class="form-check-label f-w-600 pl-1" for="many_submission">{{ __('Many Submission') }}</label>
            </div>
        </div>
        <div class="col-md-3 py-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="get_consumption_fee" id="get_consumption_fee"
                    value="yes" {{ $permissiontype->get_consumption_fee == 'yes' ? 'checked="checked"' : '' }}>
                <label class="form-check-label f-w-600 pl-1"
                    for="get_consumption_fee">{{ __('Get Consumption Fee') }}</label>
            </div>
        </div>
        <div class="col-md-3 py-3">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="clock_out" id="clock_out" value="yes"
                    {{ $permissiontype->clock_out == 'yes' ? 'checked="checked"' : '' }}>
                <label class="form-check-label f-w-600 pl-1" for="clock_out">{{ __('Clock Out') }}</label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>

{{ Form::close() }}
