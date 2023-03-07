{{ Form::model($jobOnBoard, ['route' => ['job.on.board.update', $jobOnBoard->id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
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
{{-- <div class="col-12">
    <input type="submit" value="{{ __('Update') }}" class="btn-create badge-blue">
    <input type="button" value="{{ __('Cancel') }}" class="btn-create bg-gray" data-dismiss="modal">
</div> --}}
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
