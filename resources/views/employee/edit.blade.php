@php
    $userLogin = Auth::user();
@endphp

@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Employee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Employee') }}</li>
@endsection

@section('content')

    <div class="">
        <div class="">

            {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-6 ">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5>{{ __('Personal Detail') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-12">
                                    {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group col-6">
                                    {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::text('dob', null, ['class' => 'form-control d_week', 'id' => 'data_picker1']) !!}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        {!! Form::label('birthplace', __('Birthplace'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::text('birthplace', null, ['class' => 'form-control d_week', 'id' => 'data_picker1']) !!}
                                    </div>
                                </div>
                                <div class="col-6 ">
                                    <div class="form-group">
                                        {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="form-check-input"
                                                    {{ $employee->gender == 'Male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="form-check-input"
                                                    {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="g_female">{{ __('Female') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if ($userLogin->type != 'employee')
                                    <div class="form-group col-6">
                                        {!! Form::label('password', __('New Password'), ['class' => 'form-label']) !!}<span class="text-danger pl-1"></span>
                                        {!! Form::password('password', ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="col-6 ">
                                        <div class="form-group">
                                            {!! Form::label('is_active', __('Status'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <div class="d-flex radio-check">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="active" value="1" name="is_active"
                                                        class="form-check-input"
                                                        {{ $employee->is_active == 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="active">{{ __('Active') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                    <input type="radio" id="not_active" value="0" name="is_active"
                                                        class="form-check-input"
                                                        {{ $employee->is_active != 1 ? 'checked' : '' }}>
                                                    <label class="custom-control-label"
                                                        for="not_active">{{ __('Not Active') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                            @if (Auth::user()->type == 'employee')
                                {!! Form::submit('Update', ['class' => 'btn-create btn-xs badge-blue radius-10px float-right']) !!}
                            @endif
                        </div>
                    </div>
                </div>
                @if (Auth::user()->type != 'employee')
                    <div class="col-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Company Detail') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @csrf
                                    <div class="form-group">
                                        {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                        {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('role_id', __('User Role'), ['class' => 'form-label']) }}
                                        {{ Form::select('role_id', $roles, null, ['class' => 'form-control  select2', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label']) }}
                                        {{ Form::select('branch_id', $branches, null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder' => 'Select Branch']) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('department_id', __('Select Department*'), ['class' => 'form-label']) }}
                                        {{ Form::select('department_id', $departments, null, ['class' => 'form-control select2', 'id' => 'department_id', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <div class="designation_div">
                                                <select class="form-control designation_id select2" name="designation_id"
                                                    id="choices-multiple" placeholder="Select Designation">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('position_id', __('Position'), ['class' => 'form-label']) }}
                                        {{ Form::select('position_id', $position, null, ['class' => 'form-control  select2', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('employeetype_id', __('Employee Type'), ['class' => 'form-label']) }}
                                        {{ Form::select('employeetype_id', $employeetype, null, ['class' => 'form-control  select2', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {{ Form::label('education_id', __('Education'), ['class' => 'form-label']) }}
                                        {{ Form::select('education_id', $education, null, ['class' => 'form-control  select2', 'placeholder' => __('Select Education')]) }}
                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'form-label']) !!}
                                        {!! Form::date('company_doj', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-6 ">
                        <div class="employee-detail-wrap ">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Company Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Branch') }}</strong>
                                                <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info font-style">
                                                <strong>{{ __('Department') }}</strong>
                                                <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info font-style">
                                                <strong>{{ __('Designation') }}</strong>
                                                <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Date Of Joining') }}</strong>
                                                <span>{{ Auth::user()->dateFormat($employee->company_doj) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if (Auth::user()->type != 'employee')
                <div class="row">
                    <div class="col-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Document') }}</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                                @endphp

                                @foreach ($documents as $key => $document)
                                    <div class="row">
                                        <div class="form-group col-12 d-flex">
                                            <div class="float-left col-4">
                                                <label for="document" class=" form-label">{{ $document->name }}
                                                    @if ($document->is_required == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="float-right col-8">
                                                <input type="hidden" name="emp_doc_id[{{ $document->id }}]"
                                                    id="" value="{{ $document->id }}">

                                                @php
                                                    $employeedoc = !empty($employee->documents) ? $employee->documents()->pluck('document_value', __('document_id')) : [];
                                                @endphp
                                                <div class="choose-files ">
                                                    <label for="document[{{ $document->id }}]">
                                                        <div class=" bg-primary document "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file"
                                                            class="form-control file  d-none @error('document') is-invalid @enderror"
                                                            @if ($document->is_required == 1) required @endif
                                                            name="document[{{ $document->id }}]"
                                                            id="document[{{ $document->id }}]"
                                                            data-filename="{{ $document->id . '_filename' }}"
                                                            onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                    {{-- <a href="#"><p class="{{ $document->id . '_filename' }} "></p></a> --}}
                                                    <img id="{{ 'blah' . $key }}"
                                                        src="{{ isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? asset(url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                        width="25%" />

                                                </div>

                                                @if (!empty($employeedoc[$document->id]))
                                                    <span class="text-xs-1"><a
                                                            href="{{ !empty($employeedoc[$document->id]) ? asset(url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                            target="_blank"></a>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Additional Information') }}</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $employee_add = $employee->additional()->pluck('additional_value', 'additional_id');
                                @endphp

                                @foreach ($additionalInformation as $key => $additional)
                                    <div class="row">
                                        <div class="form-group">
                                            <label for="additional"
                                                class="float-left pt-1 form-label">{{ $additional->name }}
                                                @if ($additional->is_required == 1)
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label>
                                            <input type="hidden" name="additional_id[{{ $additional->id }}]"
                                                id="" value="{{ $additional->id }}">
                                            <input
                                                class="form-control  @error('additional') is-invalid @enderror"
                                                @if ($additional->is_required == 1) required @endif
                                                name="additional[{{ $additional->id }}]" type="{{ $additional->type == 'date' ? 'date' : 'text' }}"
                                                id="additional[{{ $additional->id }}]"
                                                value="{{ !empty($employee_add[$additional->id]) ? $employee_add[$additional->id] : '' }}">

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Bank Account Detail') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-6">
                                        {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('account_holder_name', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                        {!! Form::number('account_number', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                        {!! Form::text('bank_identifier_code', null, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                        {!! Form::text('branch_location', null, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group col-6">
                                        {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                        {!! Form::text('tax_payer_id', null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-6 ">
                        <div class="employee-detail-wrap">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Document Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                                        @endphp
                                        @foreach ($documents as $key => $document)
                                            <div class="col-md-12">
                                                <div class="info">
                                                    <strong>{{ $document->name }}</strong>
                                                    <span><a href="{{ !empty($employeedoc[$document->id]) ? asset(url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                            target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ">
                        <div class="employee-detail-wrap">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Bank Account Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Account Holder Name') }}</strong>
                                                <span>{{ $employee->account_holder_name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info font-style">
                                                <strong>{{ __('Account Number') }}</strong>
                                                <span>{{ $employee->account_number }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info font-style">
                                                <strong>{{ __('Bank Name') }}</strong>
                                                <span>{{ $employee->bank_name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Bank Identifier Code') }}</strong>
                                                <span>{{ $employee->bank_identifier_code }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Branch Location') }}</strong>
                                                <span>{{ $employee->branch_location }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="info">
                                                <strong>{{ __('Tax Payer Id') }}</strong>
                                                <span>{{ $employee->tax_payer_id }}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->type != 'employee')
                <div class="float-end">
                    <button type="submit" class="btn  btn-primary">{{ 'Update' }}</button>
                </div>
            @endif
            <div class="col-12">
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@push('script-page')
    <script type="text/javascript">
        function getDesignation(did) {

            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.designation_id').empty();
                    var emp_selct = ` <select class="form-control  designation_id select2" name="designation_id" id="choices-multiple"
                                            placeholder="Select Designation" >
                                            </select>`;
                    $('.designation_div').html(emp_selct);
                    $('.designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ $employee->designation_id }}') {
                            select = 'selected';
                        }

                        $('.designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }

        $(document).ready(function() {
            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });
    </script>
@endpush
