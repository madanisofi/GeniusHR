{{ Form::model($additional, ['route' => ['additional.update', $additional->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="row">
            <div class="form-group col-12">
                {{ Form::label('name', __('Name', ['class' => 'form-label'])) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Additional Information Name')]) }}
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-group col-6">
            {{ Form::label('is_required', __('Required Field'), ['class' => 'form-label']) }}
            <select class="form-control select2" required name="is_required">
                <option value="0" @if ($additional->is_required == 0) selected @endif>{{ __('Not Required') }}
                </option>
                <option value="1" @if ($additional->is_required == 1) selected @endif>{{ __('Is Required') }}
                </option>
            </select>
        </div>
        <div class="form-group col-6">
            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
            <select class="form-control select2" required name="type">
                <option value="text" @if ($additional->type == 'text') selected @endif>Text</option>
                <option value="date" @if ($additional->type == 'date') selected @endif>Date</option>
            </select>
        </div>
        <div class="form-group col-6">
            {{ Form::label('can_insert', __('Can Insert'), ['class' => 'form-label']) }}
            <select class="form-control select2" required name="can_insert">
                <option value="1" @if ($additional->can_insert == 1) selected @endif>{{ __('Could') }}</option>
                <option value="0" @if ($additional->can_insert == 0) selected @endif>{{ __('Can Not') }}</option>
            </select>
        </div>
        <div class="form-group col-6">
            {{ Form::label('send_notification', __('Send Notification'), ['class' => 'form-label']) }}
            <select class="form-control select2" required name="send_notification">
                <option value="0" @if ($additional->send_notification == 0) selected @endif>{{ __('No') }}</option>
                <option value="1" @if ($additional->send_notification == 1) selected @endif>{{ __('Yes') }}</option>
            </select>
        </div>
        <div class="form-group col-6">
            {{ Form::label('reminder', __('Reminder'), ['class' => 'form-label']) }}
            {{ Form::number('reminder', null, ['class' => 'form-control', 'placeholder' => __('Enter Reminder')]) }}
            @error('reminder')
                <span class="invalid-reminder" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group col-12">
            <input type="submit" value="{{ __('Update') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>

{{ Form::close() }}
