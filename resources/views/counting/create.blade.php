{{ Form::open(['url' => 'counting', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('group_id', __('Group'), ['class' => 'form-label']) }}
                <select class="form-control select2" name="group_id" id="group_id" placeholder="Select Group">
                    <option value="">{{ __('Select Group') }}</option>
                    @foreach ($group as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('start_year', __('Start Year'), ['class' => 'form-label']) }}
                {{ Form::text('start_year', null, ['class' => 'form-control', 'placeholder' => __('Start Year')]) }}
                @error('start_year')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('max_year', __('Max Year'), ['class' => 'form-label']) }}
                {{ Form::text('max_year', null, ['class' => 'form-control', 'placeholder' => __('Max Year')]) }}
                @error('max_year')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <h6 class="my-3">{{ __('Assign Start Salary') }} </h6>
        </div>
        @foreach ($employeetype as $employeetype)
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('salary' . $employeetype->id, $employeetype->name, ['class' => 'form-label']) }}
                    {{ Form::text('salary' . $employeetype->id, null, ['class' => 'form-control', 'required', 'placeholder' => $employeetype->name]) }}
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
