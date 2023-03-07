{{-- <div class="col-form-label">
    {{ Form::open(array('route' => array('holidays.import'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="row">
        <div class="col-md-12 mb-2 d-flex align-items-center justify-content-between">
            {{Form::label('file',__('Download sample customer CSV file'),['class'=>'col-form-label'])}}
            <a href="{{asset(url('uploads/sample')).'/sample_holidays.csv'}}" class="action-btn btn-primary me-1 btn btn-sm d-inline-flex align-items-center">
                <i class="fa fa-download"></i> {{__('Download')}}
            </a>
        </div>
        <div class="col-md-12">
            {{Form::label('file',__('Select CSV File'),['class'=>'col-form-label'])}}
            <div class="choose-file form-group">
                <label for="file" class="col-form-label">
                    <div>{{__('Choose file here')}}</div>
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>
        <div class="col-md-12 mt-2 text-right">
            <input type="submit" value="{{__('Upload')}}" class="btn color_blue line_height_auto text-white border-none">
            <input type="button" value="{{__('Cancel')}}" class="btn color_secondary line_height_auto border-none text-white" data-bs-dismiss="modal">
        </div>
    </div>
    {{ Form::close() }}
</div> --}}



{{ Form::open(['route' => ['holidays.import'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-md-12 mb-6">
            <label for="file" class="form-label">{{ __("Download sample product CSV file") }}</label>
            <a href="{{ asset(url('uploads/sample')) . '/sample_holidays.csv' }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-download"></i> {{ __("Download") }}
            </a>
        </div>
        <div class="col-md-12">
            <label for="file" class="form-label">{{ __('Select CSV File') }}</label>
            
            <div class="choose-file form-group">
               
                <label for="file" class="form-label choose-files bg-primary "><i
                    class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</label>
            <input type="file" name="file" id="file" class="custom-input-file d-none">

            </div>
        </div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Upload') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
