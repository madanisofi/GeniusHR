@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Attendance List') }}
@endsection
@push('script-page')
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();

            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>
@endpush
@section('action-button')
    @can('Create Attendance')
        <a href="#" data-url="{{ route('attendanceemployee.create') }}" class="btn btn-sm btn-primary" data-ajax-popup="true"
            data-size="lg" data-title="{{ __('Create New Attendance') }}">
            <i class="fa fa-plus"></i>
        </a>
    @endcan
@endsection
@section('content')
    <div class="col">
        <div class="card">
            <div class="card-body">
                {{ Form::open(['route' => ['attendanceemployee.index'], 'method' => 'get', 'id' => 'attendanceemployee_filter']) }}
                <div class="row align-items-center justify-content-end">
                    <div class="col-auto">
                        <div class="btn-box">
                            <label class="form-label">{{ __('Type') }}</label> <br>
                            <div class="d-flex radio-check">
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="daily" value="daily" name="type"
                                        class="form-check-input"
                                        {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : 'checked' }}>
                                    <label class="custom-control-label" for="daily">{{ __('Daily') }}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="monthly" value="monthly" name="type"
                                        class="form-check-input"
                                        {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="monthly">{{ __('Monthly') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto month">
                        <div class="btn-box">
                            {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                            {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : null, ['class' => 'month-btn form-control month-btn']) }}
                        </div>
                    </div>
                    <div class="col-auto date">
                        <div class="btn-box">
                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                            {{ Form::date('date', isset($_GET['date']) ? $_GET['date'] : '', ['class' => 'form-control datepicker month-btn']) }}
                        </div>
                    </div>
                    @if (Auth::user()->type != 'employee')
                        <div class="col-xl-2 col-lg-3">
                            <div class="btn-box">
                                {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3">
                            <div class="btn-box">
                                {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    @endif
                    <div class="col-auto mt-4">
                        <div class="row">
                            <div class="col-auto"><a href="#" class="btn btn-sm btn-success"
                                    onclick="document.getElementById('attendanceemployee_filter').submit(); return false;">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('attendanceemployee.index') }}" class="btn btn-sm btn-danger">
                                    <span class="btn-inner--icon"><i class="ti ti-trash text-white-off"></i></span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                @if (Auth::user()->type != 'employee')
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                @if ($shift == 'on')
                                    <th>{{ __('Shift') }}</th>
                                @endif

                                @if ($qr_presence == 'on')
                                    <th>{{ __('Approval') }}</th>
                                @endif
                                <th>{{ __('Clock In') }}</th>
                                <th>{{ __('Clock Out') }}</th>
                                <th>{{ __('Late') }}</th>
                                @if ($late_fee_calculation == 'on')
                                    <th>{{ __('Salary cuts') }}</th>
                                @endif
                                @if (Gate::check('Edit Attendance') || Gate::check('Delete Attendance'))
                                    <th>{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($attendanceEmployee as $attendance)
                                <tr>
                                    @if (Auth::user()->type != 'employee')
                                        <td>{{ !empty($attendance->employee) ? $attendance->employee->name : '' }}
                                        </td>
                                    @endif
                                    <td>{{ Auth::user()->dateFormat($attendance->date) }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    @if ($shift == 'on')
                                        <td>{{ !empty($attendance->shift) ? $attendance->shift->name : '' }}</td>
                                    @endif
                                    @if ($qr_presence == 'on')
                                        <td>
                                            @foreach (json_decode($attendance->approve) as $item)
                                                <li>
                                                    @if ($item->status == 'Approve')
                                                        <div class="badge badge-pill badge-success">
                                                            {{ $item->status }}</div>
                                                    @else
                                                        <div class="badge badge-pill badge-danger">
                                                            {{ $item->status }}</div>
                                                    @endif
                                                    - {{ $item->user }} ( <small>{{ $item->type }} </small> )
                                                </li>
                                            @endforeach
                                        </td>
                                    @endif
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            data-url="{{ URL::to('attendanceemployee/showpicture/' . $attendance->id) }}"
                                            data-size="lg" data-ajax-popup="true"
                                            data-title="{{ __('Show Picture Login') }}" data-toggle="tooltip"
                                            data-original-title="{{ __('Show') }}">
                                            <i class="fas fa-eye"></i>
                                            {{ $attendance->clock_in != '00:00:00' ? Auth::user()->timeFormat($attendance->clock_in) : '00:00' }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary"
                                            data-url="{{ URL::to('attendanceemployee/showpictureout/' . $attendance->id) }}"
                                            data-size="lg" data-ajax-popup="true"
                                            data-title="{{ __('Show Picture Logout') }}" data-toggle="tooltip"
                                            data-original-title="{{ __('Show') }}">
                                            <i class="fas fa-eye"></i>
                                            {{ $attendance->clock_out != '00:00:00' ? Auth::user()->timeFormat($attendance->clock_out) : '00:00' }}
                                        </a>
                                    </td>
                                    <td>{{ isset($attendance->latecharge) ? ($attendance->latecharge->working_hours != '00:00:00' ? $attendance->latecharge->working_late : $attendance->late) : $attendance->late }}
                                    </td>
                                    @if ($late_fee_calculation == 'on')
                                        <td>{{ Auth::user()->priceFormat(isset($attendance->latecharge) ? $attendance->latecharge->salary_cuts : $attendance->salary_cuts) }}
                                        </td>
                                    @endif

                                    @if (Gate::check('Edit Attendance') || Gate::check('Delete Attendance'))
                                        <td>

                                            @if ($attendance->status != 'Present' and
                                                $user->type != 'employee' and
                                                $attendance->employee_id != (!empty($user->employee) ? $user->employee->id : 0))
                                                <a href="#"
                                                    data-url="{{ URL::to('attendanceemployee/' . $attendance->id . '/action') }}"
                                                    data-size="lg" data-ajax-popup="true"
                                                    data-title="{{ __('Attendance Action') }}" class="edit-icon bg-success"
                                                    data-toggle="tooltip"
                                                    data-original-title="{{ __('Attendance Action') }}"><i
                                                        class="fas fa-caret-right"></i> </a>
                                            @endif

                                            @php
                                                $start = date_create($attendance->created_at);
                                                $akhir = date_create();
                                                $diff = date_diff($start, $akhir);
                                            @endphp
                                            @can('Edit Attendance')
                                                @if ($diff->d * 24 + $diff->h <= 24)

                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#"
                                                        data-url="{{ URL::to('attendanceemployee/' . $attendance->id . '/edit') }}"
                                                            data-size="lg" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ route('account-assets.edit', $attendance->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Assets') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>

                                                @endif
                                            @endcan
                                            @can('Delete Attendance')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['attendanceemployee.destroy', $attendance->id],
                                                        'id' => 'delete-form-' . $attendance->id,
                                                    ]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Delete" aria-label="Delete"><i
                                                            class="ti ti-trash text-white "></i></a>
                                                    </form>
                                                </div>
                                        @endif
                                        </td>
                                @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('script-page')
        <script>
            $(document).ready(function() {
                $('.daterangepicker').daterangepicker({
                    format: 'yyyy-mm-dd',
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });
            });
        </script>
    @endpush
