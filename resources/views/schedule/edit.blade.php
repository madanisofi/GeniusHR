<div class="card bg-none card-box">
    {{Form::model($schedule,array('route' => array('schedule.update', $schedule->id), 'method' => 'PUT')) }}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('employee',__('Employee'),['class'=>'form-label'])}}
                {{Form::text('employee',null,array('class'=>'form-control', 'readonly'))}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <h6 class="my-3">{{__('Assign Permission to Roles')}} </h6>
                <table class="table table-striped mb-0" id="dataTable-1">
                    <thead>
                    <tr>
                        <th>{{__('Day')}} </th>
                        <th>{{ __('Room') }}</th>
                        <th>{{__('Shift')}} </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ __('Monday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_monday" id="room_monday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_monday[]',$item->id,(in_array($item->id, $day[1]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_monday'.$item->id])}}
                                            {{Form::label('shift_monday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Tuesday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_tuesday" id="room_tuesday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_tuesday[]',$item->id,(in_array($item->id, $day[2]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_tuesday'.$item->id])}}
                                            {{Form::label('shift_tuesday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Wednesday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_wednesday" id="room_wednesday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_wednesday[]',$item->id,(in_array($item->id, $day[3]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_wednesday'.$item->id])}}
                                            {{Form::label('shift_wednesday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Thursday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_thursday" id="room_thursday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_thursday[]',$item->id,(in_array($item->id, $day[4]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_thursday'.$item->id])}}
                                            {{Form::label('shift_thursday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Friday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_friday" id="room_friday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_friday[]',$item->id,(in_array($item->id, $day[5]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_friday'.$item->id])}}
                                            {{Form::label('shift_friday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Saturday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_saturday" id="room_saturday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_saturday[]',$item->id,(in_array($item->id, $day[6]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_saturday'.$item->id])}}
                                            {{Form::label('shift_saturday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>{{ __('Sunday') }}</td>
                            <td>
                                <select class="form-control select2" name="room_sunday" id="room_sunday">
                                    <option value="">{{__('Select Room')}}</option>
                                    @foreach($roomtype as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <div class="row">
                                    @foreach ($shift as $item)
                                        <div class="col-md-4 custom-control custom-checkbox">
                                            {{Form::checkbox('shift_sunday[]',$item->id,(in_array($item->id, $day[7]) ? true : false), ['class'=>'custom-control-input','id' =>'shift_sunday'.$item->id])}}
                                            {{Form::label('shift_sunday'.$item->id,$item->name,['class'=>'custom-control-label font-weight-500'])}}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-12">
            <input type="submit" value="{{__('Update')}}" class="btn-create badge-blue">
            <input type="button" value="{{__('Cancel')}}" class="btn-create bg-gray" data-dismiss="modal">
        </div>
    </div>
    {{Form::close()}}
</div>
