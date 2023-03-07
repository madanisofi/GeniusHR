<div class="col">
    <div class="card">
        <div class="card-body">
            {{ Form::open(['url' => 'scheduleEmployee', 'method' => 'post']) }}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('branch_id', __('Branch'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="branch_id" id="branch_id" placeholder="Select Branch">
                            <option value="">{{ __('Select Branch') }}</option>
                            @foreach ($branch as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('department_id', __('Department'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="department_id[]" id="department_id"
                            placeholder="Select Department" multiple>

                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('employee_id', __('Employee'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="employee_id" id="employee_id"
                            placeholder="Select Employee">

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('shift_id', __('Shift'), ['class' => 'form-label']) }}
                        <select class="form-control select2" name="shift_id" id="shift_id" placeholder="Select Shift">
                            <option value="">{{ __('Select Shift') }}</option>
                            @foreach ($shift as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                        {{ Form::text('date', null, ['class' => 'form-control datepicker']) }}
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
                    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-dismiss="modal">
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
