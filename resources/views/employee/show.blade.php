@extends('layouts.admin')

@section('page-title')
    {{ __('Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Employee') }}</li>
@endsection

@section('action-button')
    <div class="float-end">
        @can('Edit Employee')
            <a href="{{ route('employee.edit', Crypt::encrypt($employee->id)) }}" data-bs-toggle="tooltip"
                title="{{ __('Edit') }}"class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="row">
            <div class="col-sm-12 col-md-6">

                <div class="card ">
                    <div class="card-body employee-detail-body fulls-card emp-card">
                        <h5>{{ __('Personal Detail') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Employee ID') }} : </strong>
                                    <span>{{ $employeesId }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Name') }} :</strong>
                                    <span>{{ $employee->name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Email') }} :</strong>
                                    <span>{{ $employee->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Birthplace') }} : </strong>
                                    <span>{{ $employee->birthplace }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Date of Birth') }} :</strong>
                                    <span>{{ Auth::user()->dateFormat($employee->dob) }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Phone') }} :</strong>
                                    <span>{{ $employee->phone }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Address') }} :</strong>
                                    <span>{{ $employee->address }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Salary Type') }} :</strong>
                                    <span>{{ !empty($employee->salaryType) ? $employee->salaryType->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Basic Salary') }} :</strong>
                                    <span>{{ $employee->salary }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="card ">
                    <div class="card-body employee-detail-body fulls-card emp-card">
                        <h5>{{ __('Company Detail') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Branch') }} : </strong>
                                    <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Department') }} : </strong>
                                    <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Designation') }} : </strong>
                                    <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Position') }} : </strong>
                                    <span>{{ !empty($employee->position) ? $employee->position->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Employee Type') }} : </strong>
                                    <span>{{ !empty($employee->employeetype) ? $employee->employeetype->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Education') }} : </strong>
                                    <span>{{ !empty($employee->education) ? $employee->education->name : '' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Date Of Joining') }} : </strong>
                                    <span>{{ Auth::user()->dateFormat($employee->company_doj) }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-6">

                <div class="card ">
                    <div class="card-body employee-detail-body fulls-card emp-card">
                        <h5>{{ __('Document Detail') }}</h5>
                        <hr>
                        <div class="row">
                            @php
                                $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                            @endphp
                            @if (!$documents->isEmpty())
                                @foreach ($documents as $key => $document)
                                    <div class="col-md-6">
                                        <div class="info text-sm">
                                            <strong class="font-bold">{{ $document->name }} : </strong>
                                            <span><a href="{{ !empty($employeedoc[$document->id]) ? asset(url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                    target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center">
                                    No Document Type Added.!
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="employee-detail-wrap">
                    <div class="card">
                        <div class="card-body employee-detail-body fulls-card emp-card">
                            <h5>{{ __('Additional Information') }}</h5>
                            <hr>
                            <div class="row">
                                @php
                                    $employee_add = $employee->additional()->pluck('additional_value', 'additional_id');
                                @endphp

                                @foreach ($additionalInformation as $key => $additional)
                                    <div class="col-md-6">
                                        <div class="info text-sm">
                                            <strong class="font-bold">{{ $additional->name }} : </strong>
                                            <span>{{ !empty($employee_add[$additional->id]) ? $employee_add[$additional->id] : '' }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">

                <div class="card ">
                    <div class="card-body employee-detail-body fulls-card emp-card">
                        <h5>{{ __('Bank Account Detail') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Account Holder Name') }} : </strong>
                                    <span>{{ $employee->account_holder_name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm font-style">
                                    <strong class="font-bold">{{ __('Account Number') }} :</strong>
                                    <span>{{ $employee->account_number }}</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Bank Name') }} :</strong>
                                    <span>{{ $employee->bank_name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Bank Identifier Code') }} :</strong>
                                    <span>{{ $employee->bank_identifier_code }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Branch Location') }} :</strong>
                                    <span>{{ $employee->branch_location }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info text-sm">
                                    <strong class="font-bold">{{ __('Tax Payer Id') }} :</strong>
                                    <span>{{ $employee->tax_payer_id }}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
