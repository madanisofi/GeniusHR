@extends('layouts.admin')

@section('page-title')
    {{ __('Event') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Event') }}</li>
@endsection

@section('action-button')
    @can('Create Event')
        <a href="#" data-url="{{ route('event.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Event') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection


@section('content')



    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('Calendar') }}</h5>
            </div>
            <div class="card-body">
                <div id='calendar' class='calendar'></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-4">{{ __('Upcoming Events') }}</h4>
                <ul class="event-cards list-group list-group-flush mt-3 w-100">
                    @if (!$events->isEmpty())
                        @foreach ($current_month_event as $event)
                            <li class="list-group-item card mb-3">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-calendar-event"></i>
                                            </div>
                                            <div class="ms-3">

                                                <h6 class="  text-primary">
                                                    <a href="#" data-size="lg"
                                                        data-url="{{ route('event.edit', $event->id) }}"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Event') }}"
                                                        class="fc-event-title text-primary">{{ $event->title }}</a>
                                                </h6>
                                                <p class="card-text small text-dark mt-0">
                                                    {{ __('Start Date : ') }}
                                                    {{ Auth::user()->dateFormat($event->start_date) }}<br>
                                                    {{ __('End Date : ') }}
                                                    {{ Auth::user()->dateFormat($event->end_date) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <div class="text-center">

                        </div>
                    @endif

                </ul>
            </div>
        </div>
    </div>
@endsection


@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>



    <script type="text/javascript">
        (function() {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },
                buttonText: {
                    timeGridDay: "{{ __('Day') }}",
                    timeGridWeek: "{{ __('Week') }}",
                    dayGridMonth: "{{ __('Month') }}"
                },
                themeSystem: 'bootstrap',

                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! $arrEvents !!},


            });

            calendar.render();
        })();
    </script>


    <script>
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '{{ route('event.getdepartment') }}',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {


                    $('.department_id').empty();
                    var emp_selct = ` <select class="form-control  department_id" name="department_id[]" id="choices-multiple"
                                            placeholder="Select Department" multiple >
                                            </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });


                }
            });
        }

        $(document).on('change', '.department_id', function() {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        function getEmployee(did) {
            $.ajax({
                url: '{{ route('event.getemployee') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('.employee_id').empty();
                    var emp_selct = ` <select class="form-control  employee_id" name="employee_id[]" id="choices-multiple1"
                                            placeholder="Select Employee" multiple >
                                            </select>`;
                    $('.employee_div').html(emp_selct);

                    $('.employee_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.employee_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple1', {
                        removeItemButton: true,
                    });

                }
            });
        }
    </script>
@endpush
