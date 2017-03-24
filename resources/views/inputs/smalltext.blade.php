<div class="input-group  {{$required or ''}}">
    <span class="input-group-addon" id="sizing-addon1">{!!$addon or '<i class="fa fa-spin"></i>'!!}</span>
    <input type="text" class="form-control editable" placeholder="{{$placeholder or ''}}" name="{{$name or 'name'}}" aria-describedby="sizing-addon1" value="{{$value or ''}}"
        data-ref="/user/{{$id}}/contact" data-field="{{$name or 'name'}}">
</div>
