<div class="card bg-none card-box">
    {{ Form::open(['url' => 'attendanceemployee/changeaction', 'method' => 'post']) }}
    <div class="row">
        <div class="col-12">
            <table class="table table-striped mb-0 dataTable no-footer">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($employee->name) ? $employee->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Clock In') }}</th>
                    <td>{{ !empty($attendance->clock_in) ? $attendance->clock_in : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Permission') }}</th>
                    <td>{{ !empty($attendance->permission) ? $attendance->permission->title : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Reason') }}</th>
                    <td>{{ !empty($attendance->reason) ? $attendance->reason : '-' }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="text-center">
                            @if ($attendance->status == 'Absence')
                                <img src="{{ $public . '/' . $attendance->images_reason }}" width="300" alt="">
                            @else
                                <img src="{{ $public . '/' . $attendance->images }}" width="300" alt="">
                            @endif

                        </div>
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Approval') }}</th>
                    <td>
                        @foreach (json_decode($attendance->approve) as $item)
                            <li>
                                @if ($item->status == 'Approve')
                                    <div class="badge badge-pill badge-success">{{ $item->status }}</div>
                                @else
                                    <div class="badge badge-pill badge-danger">{{ $item->status }}</div>
                                @endif
                                - {{ $item->user }} ( <small>{{ $item->type }} </small> )
                            </li>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>{{ !empty($attendance->status) ? $attendance->status : '' }}</td>
                </tr>
                <input type="hidden" value="{{ $attendance->id }}" name="attendance_id">
            </table>
        </div>
        <div class="col-12">
            <input type="submit" class="btn-create badge-success" value="Approve" name="status">
            <input type="submit" class="btn-create bg-danger" value="Reject" name="status">
        </div>
    </div>
    {{ Form::close() }}
</div>
