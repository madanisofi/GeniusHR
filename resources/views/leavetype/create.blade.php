{{ Form::open(['url' => 'leavetype', 'method' => 'post']) }}
<div class="modal-body">

    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('title', __('Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Payment Type Name')]) }}
                </div>
                @error('title')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Days Per Year'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::number('days', null, ['class' => 'form-control', 'placeholder' => __('Enter Days / Year')]) }}
                </div>

            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('parent', __('Parent'), ['class' => 'form-label']) }}
                <select name="parent[]" id="parent" class="form-control select2" multiple>
                    @foreach ($leavetype as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Initial Month Valid'), ['class' => 'form-label']) }}
                {{ Form::select('start_date', $month, null, ['class' => 'form-control month select2']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('Last Month Valid'), ['class' => 'form-label']) }}
                {{ Form::select('end_date', $month, null, ['class' => 'form-control month select2']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reduction', __('Reduction'), ['class' => 'form-label']) }}
                {{ Form::number('reduction', null, ['class' => 'form-control', 'placeholder' => __('Reduction')]) }}
            </div>
        </div>
        <div class="col-md-6 mt-4">
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input" name="select_all" id="select_all" value="on">
                <label class="form-check-label f-w-600 pl-1" for="select_all">{{ __('Select All Option') }}</label>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
