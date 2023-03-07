@extends('layouts.admin')

@section('page-title')
    {{__('Manage Schedule')}}
@endsection

@section('action-button')
    @if ($level > 0)
        @if ($get_access == 'open')
            <div class="all-button-box row d-flex justify-content-end">
            @can('Create Schedule')
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                    {{-- <a href="#" data-url="{{ route('schedule.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create New schedule')}}">
                        <i class="fa fa-plus"></i> {{__('Create')}}
                    </a> --}}

                    <a href="{{ route('schedule.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fa fa-plus"></i> {{__('Create')}}
                    </a>
                </div>

                {{-- {{ date('W', strtotime('2022-05-18')) }} --}}
                {{-- {{ (new DateTime())->setISODate(2022, 21)->format('Y-m-d') }}
                {{ (new DateTime())->setISODate(2022, 21, 7)->format('Y-m-d') }}
                {{ date('N', strtotime('2022-05-22')) }} --}}
                {{-- {{ date('Y-m-t') }} --}}
                {{-- {{ date('Y-m-d', strtotime('-20 days', strtotime(date('Y-m-t')))) }} --}}
            @endcan
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('scheduleEmployee.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Change Schedule')}}">
                    <i class="fa fa-pencil-alt"></i> {{__('Change Schedule')}}
                </a>
            </div>
        </div>
        @endif
    @else
        <div class="all-button-box row d-flex justify-content-end">
            @can('Create Schedule')
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                    {{-- <a href="#" data-url="{{ route('schedule.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create New schedule')}}">
                        <i class="fa fa-plus"></i> {{__('Create')}}
                    </a> --}}

                    <a href="{{ route('schedule.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fa fa-plus"></i> {{__('Create')}}
                    </a>
                </div>
            @endcan
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('scheduleEmployee.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Change Schedule')}}">
                    <i class="fa fa-pencil-alt"></i> {{__('Change Schedule')}}
                </a>
            </div>
        </div>
    @endif
    
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable" >
                            <thead>
                            <tr>
                                <th>{{__('Employee')}}</th>
                                {{-- <th>{{__('Room')}}</th> --}}
                                <th>{{__('Monday')}}</th>
                                <th>{{__('Tuesday')}}</th>
                                <th>{{__('Wednesday')}}</th>
                                <th>{{__('Thursday')}}</th>
                                <th>{{__('Friday')}}</th>
                                <th>{{__('Saturday')}}</th>
                                <th>{{__('Sunday')}}</th>
                                @if(Gate::check('Edit Schedule') || Gate::check('Delete Schedule'))
                                    @if ($level > 0)
                                        @if ($get_access == 'open')
                                            <th width="200px">{{__('Action')}}</th>
                                        @endif
                                    @else
                                        <th width="200px">{{__('Action')}}</th>
                                    @endif
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($data as $x => $schedule)
                                <tr>
                                    <td>{{ ucfirst($schedule['employee']) }}</td>
                                    {{-- <td>
                                        <a href="#" class="gray-btn">{{ $schedule['department']." :: ".$schedule['designation'] }}</a>
                                    </td> --}}
                                    <td>
                                        <label class="{{ ($schedule['room'][1] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][1]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 1)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][2] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][2]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 2)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][3] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][3]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 3)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][4] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][4]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 4)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][5] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][5]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 5)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][6] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][6]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 6)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <label class="{{ ($schedule['room'][7] != __('Off') ? 'badge badge-info' : 'badge badge-warning') }}">{{ strtoupper($schedule['room'][7]) }}</label><br><hr>
                                        @foreach ($schedule['schedule'] as $x => $item)
                                            @if ($x == 7)
                                                @foreach ($item as $i)
                                                    <label class="{{ ($i != __('Off') ? 'badge badge-success' : 'badge badge-primary') }}">{{ strtoupper($i) }}</label><br>
                                                @endforeach
                                            @endif
                                        @endforeach

                                    </td>
                                    @if(Gate::check('Edit Schedule') || Gate::check('Delete Schedule'))
                                        @if ($level > 0)
                                            @if ($get_access == 'open')
                                                <td>
                                                    @can('Edit Schedule')
                                                        <a href="#" data-url="{{ URL::to('schedule/'.$schedule['id'].'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit schedule')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                    @endcan
                                                    @can('Delete Schedule')
                                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$schedule['id']}}').submit();"><i class="fas fa-trash"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['schedule.destroy', $schedule['id']],'id'=>'delete-form-'.$schedule['id']]) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                                </td>
                                            @endif
                                        @else
                                            <td>
                                                @can('Edit Schedule')
                                                    <a href="#" data-url="{{ URL::to('schedule/'.$schedule['id'].'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit schedule')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                @endcan
                                                @can('Delete Schedule')
                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$schedule['id']}}').submit();"><i class="fas fa-trash"></i></a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['schedule.destroy', $schedule['id']],'id'=>'delete-form-'.$schedule['id']]) !!}
                                                    {!! Form::close() !!}
                                                @endif
                                            </td>
                                        @endif
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>

        //Branch Wise Deapartment Get
        $(document).ready(function () {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });

        $(document).on('change', 'select[name=branch_id]', function () {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '{{route('schedule.getdepartment')}}',
                type: 'POST',
                data: {
                    "branch_id": bid, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    console.log(data);
                    $('#department_id').empty();
                    $('#department_id').append('<option value="">{{__('Select Department')}}</option>');

                    $('#department_id').append('<option value="0"> {{__('All Department')}} </option>');
                    $.each(data, function (key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }

        $(document).on('change', '#department_id', function () {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        $(document).on('change', '#type_id', function () {
            var type = $(this).val();

            if(type == 'day'){
                $('#date').css("display","none");
                $('#day').css("display","inline");
                $('#repeat_check').css("display","inline");
            } else if(type == 'date'){
                $('#date').css("display","inline");
                $('#day').css("display","none");
                $('#repeat_check').css("display","none");
            } else{
                $('#date').css("display","none");
                $('#day').css("display","none");
                $('#repeat_check').css("display","none");
            }
        });

        function getEmployee(did) {

            $.ajax({
                url: '{{route('schedule.getemployee')}}',
                type: 'POST',
                data: {
                    "department_id": did, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {

                    $('#employee_id').empty();
                    $('#employee_id').append('<option value="">{{__('Select Employee')}}</option>');
                    // $('#employee_id').append('<option value="0"> {{__('All Employee')}} </option>');

                    $.each(data, function (key, value) {
                        $('#employee_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
