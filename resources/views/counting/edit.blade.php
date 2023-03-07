<div class="card bg-none card-box">
    {{ Form::model($counting, ['route' => ['counting.update', $counting->id], 'method' => 'PUT']) }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('group_id', __('Group'), ['class' => 'form-label']) }}
                {{ Form::text('group_id', $data['group_id'], ['class' => 'form-control', 'readonly']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('start_year', __('Start Year'), ['class' => 'form-label']) }}
                {{ Form::text('start_year', $data['start_year'], ['class' => 'form-control', 'placeholder' => __('Start Year')]) }}
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
                {{ Form::text('max_year', $data['max_year'], ['class' => 'form-control', 'placeholder' => __('Max Year')]) }}
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
            @foreach ($data['salary'] as $item)
                @if ($item['id'] == $employeetype->id)
                    <div class="col-md-6">
                        <div class="form-group">
                            {{ Form::label('salary' . $employeetype->id, $employeetype->name, ['class' => 'form-label']) }}
                            {{ Form::text('salary' . $employeetype->id, $item['salary'], ['class' => 'form-control', 'required', 'placeholder' => $employeetype->name]) }}
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
        <div class="col-12">
            <input type="submit" value="{{ __('Update') }}" class="btn-create badge-blue">
            <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div>
