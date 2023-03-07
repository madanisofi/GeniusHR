@extends('layouts.admin')

@push('css-page')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/af-2.5.1/datatables.min.css"/>
@endpush

@section('page-title')
    {{ __('Manage Employee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Employee') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('employee.export') }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-file-export"></i>
    </a>

    <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true"
        data-title="{{ __('Import  Employee CSV File') }}" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Import') }}">
        <i class="ti ti-file"></i>
    </a>
    @can('Create Employee')
        <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
{{--            <div class="card-header card-body table-border-style">--}}
{{--                <div class="table-responsive">--}}
{{--                    <table class="table datatable">--}}
{{--                        <thead>--}}
{{--                            <tr>--}}
{{--                                <th>{{ __('Employee ID') }}</th>--}}
{{--                                <th>{{ __('Name') }}</th>--}}
{{--                                <th>{{ __('Position') }}</th>--}}
{{--                                <th>{{ __('Employee Type') }}</th>--}}
{{--                                <th>{{ __('Email') }}</th>--}}
{{--                                <th>{{ __('Role') }}</th>--}}
{{--                                <th>{{ __('Department') }}</th>--}}
{{--                                <th>{{ __('Designation') }}</th>--}}
{{--                                <th>{{ __('Education') }}</th>--}}
{{--                                @if ($automatic_salary == 'on')--}}
{{--                                    <th>{{ __('Group') }}</th>--}}
{{--                                @endif--}}
{{--                                <th>{{ __('Date Of Joining') }}</th>--}}
{{--                                <th>{{ __('Length of work') }}</th>--}}
{{--                                @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))--}}
{{--                                    <th width="200px">{{ __('Action') }}</th>--}}
{{--                                @endif--}}
{{--                            </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                            @foreach ($employees as $employee)--}}
{{--                                <tr>--}}
{{--                                    <td>--}}
{{--                                        @can('Show Employee')--}}
{{--                                            <a class="btn btn-outline-primary"--}}
{{--                                                href="{{ route('employee.show', Crypt::encrypt($employee->id)) }}">{{ Auth::user()->employeeIdFormat($employee->employee_id) }}</a>--}}
{{--                                        @else--}}
{{--                                            <a href="#"--}}
{{--                                                class="btn btn-outline-primary">{{ Auth::user()->employeeIdFormat($employee->employee_id) }}</a>--}}
{{--                                        @endcan--}}
{{--                                    </td>--}}
{{--                                    <td>{{ $employee->name }}</td>--}}
{{--                                    <td>--}}
{{--                                        {{ isset($employee->position) ? $employee->position->name : '' }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{ isset($employee->employeetype) ? $employee->employeetype->name : '' }}--}}
{{--                                    </td>--}}
{{--                                    <td>{{ $employee->email }}</td>--}}
{{--                                    <td>--}}
{{--                                        {{ !empty(Auth::user()->getType($employee->id)) ? Auth::user()->getType($employee->id)->type : '' }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{ isset($employee->department) ? $employee->department->name : '' }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{ isset($employee->designation) ? $employee->designation->name : '' }}--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        {{ isset($employee->education) ? $employee->education->name : '' }}--}}
{{--                                    </td>--}}
{{--                                    @if ($automatic_salary == 'on')--}}
{{--                                        <td>--}}
{{--                                            {{ isset($employee->group) ? $employee->group->name : '' }}--}}
{{--                                        </td>--}}
{{--                                    @endif--}}
{{--                                    <td>--}}
{{--                                        {{ Auth::user()->dateFormat($employee->company_doj) }}</td>--}}
{{--                                    <td>--}}
{{--                                        {{ Auth::user()->lengthOfWork($employee->company_doj) }}--}}
{{--                                    </td>--}}
{{--                                    @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))--}}
{{--                                        <td class="Action">--}}
{{--                                            @if ($employee->is_active == 1)--}}
{{--                                                <span>--}}
{{--                                                    @can('Edit Employee')--}}
{{--                                                        <div class="action-btn bg-info ms-2">--}}
{{--                                                            <a href="{{ route('employee.edit', Crypt::encrypt($employee->id)) }}"--}}
{{--                                                                class="mx-3 btn btn-sm  align-items-center"--}}
{{--                                                                data-bs-toggle="tooltip" title=""--}}
{{--                                                                data-bs-original-title="{{ __('Edit') }}">--}}
{{--                                                                <i class="ti ti-pencil text-white"></i>--}}
{{--                                                            </a>--}}
{{--                                                        </div>--}}
{{--                                                    @endcan--}}

{{--                                                    @can('Delete Employee')--}}
{{--                                                        <div class="action-btn bg-danger ms-2">--}}
{{--                                                            {!! Form::open([--}}
{{--                                                                'method' => 'DELETE',--}}
{{--                                                                'route' => ['employee.destroy', $employee->id],--}}
{{--                                                                'id' => 'delete-form-' . $employee->id,--}}
{{--                                                            ]) !!}--}}
{{--                                                            <a href="#"--}}
{{--                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"--}}
{{--                                                                data-bs-toggle="tooltip" title=""--}}
{{--                                                                data-bs-original-title="Delete" aria-label="Delete"><i--}}
{{--                                                                    class="ti ti-trash text-white"></i></a>--}}
{{--                                                            </form>--}}
{{--                                                        </div>--}}
{{--                                                    @endcan--}}
{{--                                                </span>--}}
{{--                                            @else--}}
{{--                                                <i class="ti ti-lock"></i>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
{{--                                    @endif--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="card-header card-body table-border-style m-3">
                <div class="table-responsive">
                    <table class="table yajra-datatable">
                        <thead>
                            <tr>
                                <th>{{ __('Employee ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Position') }}</th>
                                <th>{{ __('Employee Type') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Designation') }}</th>
                                <th>{{ __('Education') }}</th>
                                @if ($automatic_salary == 'on')
                                    <th>{{ __('Group') }}</th>
                                @endif
                                <th>{{ __('Date Of Joining') }}</th>
                                <th>{{ __('Length of work') }}</th>
                                @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.13.1/af-2.5.1/datatables.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            let table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('employee.datatable') }}",
                // autoFill: true,
                columns: [
                    { data: 'employee_id', orderable: false, searchable: true },
                    { data: 'name' },
                    { data: 'position.name' },
                    { data: 'employeetype.name' },
                    { data: 'email' },
                    { data: 'getType', orderable: true, searchable: true },
                    { data: 'department.name' },
                    { data: 'designation.name' },
                    { data: 'education.name' },
                    @if ($automatic_salary == 'on')
                    { data: 'group.name' },
                    @endif
                    { data: 'dateFormat', orderable: true, searchable: true },
                    { data: 'lengthOfWork',  orderable: true, searchable: true },
                    { data: 'action', orderable: true, searchable: true },
                ]
            })
        })
    </script>
@endpush
