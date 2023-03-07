{{ Form::open(['route' => ['job.on.board.store', $id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        @if ($id == 0)
            <div class="form-group col-md-12">
                {{ Form::label('application', __('Interviewer'), ['class' => 'col-form-label']) }}
                {{ Form::select('application', $applications, null, ['class' => 'form-control select2', 'required' => 'required']) }}
            </div>
        @endif
        <div class="form-group col-md-12">
            {!! Form::label('joining_date', __('Joining Date'), ['class' => 'col-form-label']) !!}
            {!! Form::text('joining_date', null, ['class' => 'form-control d_week','autocomplete'=>'off']) !!}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            {{ Form::select('status', $status, null, ['class' => 'form-control select2']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
