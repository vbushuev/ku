<div class="input-group {{$required or ''}}">
  <span class="input-group-addon" id="sizing-addon1">{!!$addon or '<i class="fa fa-spin"></i>'!!}</span>
  <input type="text" class="form-control" placeholder="{{$placeholder or ''}}" name="{{$name or 'name'}}" aria-describedby="sizing-addon1" value="{{$value or ''}}">
</div>
