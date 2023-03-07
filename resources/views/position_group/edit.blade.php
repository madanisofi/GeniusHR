{{ Form::model($positionGroup, ['route' => ['positiongroup.update', $positionGroup->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('position_id', __('Position'), ['class' => 'form-label']) }}
            <select class="form-control select2" name="position_id[]" id="position_id" placeholder="Select Position"
                multiple>
                <option value="">{{ __('Select Position') }}</option>
                @foreach ($position as $position)
                    <option value="{{ $position->id }}"
                        {{ in_array($position->id, $position_select) ? 'selected' : '' }}>{{ $position->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            @if (!empty($group))
                <h6 class="my-3">{{ __('Assign Permission to Roles') }} </h6>
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Group') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">
                                    @foreach ($group as $item)
                                        <div class="col-md-3 custom-control custom-checkbox">
                                            {{ Form::checkbox('group_id[]', $item->id, in_array($item->id, $group_select) ? true : false, ['class' => 'form-check-input isscheck', 'id' => 'group_id' . $item->id]) }}
                                            {{ Form::label('group_id' . $item->id, $item->name, ['class' => 'form-label align-middle  font-weight-500']) }}<br>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
