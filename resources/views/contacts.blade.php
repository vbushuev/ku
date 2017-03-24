@foreach($contacts as $k=>$c)
    @if($c["code"]=="phone")
        <a class="contact" href="tel:{{$c["value"]}}"><i class="fa fa-phone"></i> {{$c["value"]}}</a>
    @elseif($c["code"]=="email")
        <a class="contact" href="mailto:{{$c["value"]}}"><i class="fa fa-envelope"></i> {{$c["value"]}}</a>
    @elseif($c["code"]=="skype")
        <a class="contact" href="skype:{{$c["value"]}}"><i class="fa fa-skype"></i> {{$c["value"]}}</a>
    @elseif($c["code"]=="whatsapp")
        <a class="contact" href="whatsapp:{{$c["value"]}}"><i class="fa fa-whatsapp"></i> {{$c["value"]}}</a>
    @elseif($c["code"]=="telegram")
        <a class="contact" href="telegram:{{$c["value"]}}"><i class="fa fa-telegram"></i> {{$c["value"]}}</a>
    @elseif($c["code"]=="viber")
        <a class="contact" href="viber:{{$c["value"]}}"><img alt='v' src='/css/viber.svg' style='width:1em;color:#555;fill-color:#555;'/> {{$c["value"]}}</a>
    @endif
    <br />
@endforeach
