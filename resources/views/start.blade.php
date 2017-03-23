<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <!-- CSRF Token -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>{{ config('app.name', 'Laravel') }}</title>

            <!-- Styles -->
            <link href="{{ asset('css/app.css') }}" rel="stylesheet">
            <link href="{{ asset('css/ku.css') }}" rel="stylesheet">

            <!-- Scripts -->
            <script>
                window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
                ]) !!};
            </script>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Панель</a>
                    @else
                        <a href="{{ url('/login') }}">Вход</a>
                        <a href="{{ url('/register') }}">Регистрация</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    {{ config('app.name', 'Laravel') }}

                </div>
                <div id="start" class="form" data-rel="/user/register" data-method="post">
                    {{ csrf_field() }}
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"name","required"=>'required',"placeholder"=>"Ваше имя","addon"=>"<i class='fa fa-user-o'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"city","required"=>'required',"placeholder"=>"Ваш город","addon"=>"<i class='fa fa-map-marker'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"email","required"=>'required',"placeholder"=>"E-mail","addon"=>"<i class='fa fa-envelope'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"phone","placeholder"=>"Ваш телефон","addon"=>"<i class='fa fa-phone'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"skype","placeholder"=>"Skype","addon"=>"<i class='fa fa-skype'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"whatsapp","placeholder"=>"WhatsApp","addon"=>"<i class='fa fa-whatsapp'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3">@include('inputs.text',["name"=>"viber","placeholder"=>"Viber","addon"=>"<img alt='v' src='/css/viber.svg' style='width:1em;color:#555;fill-color:#555;'></i>"])</div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3"><div class="g-recaptcha" data-sitekey="6LfX5RkUAAAAABiNI70gBkNum67HHtlxefP_WX19"></div></div></div>
                    <div class="row"><div class="col-md-6 col-md-offset-3"><button type="button" onclick="page.submit({form:$('#start'),callback:function(){document.location='/home';}})" class="btn btn-primary pull-right">Продолжить</button></div></div>
                    <input type="hidden" name="city_id" />
                    <input type="hidden" name="type" value="0" />
                    <input type="hidden" name="status_id" value="4" />
                </div>
                <div class="bottom links">
                    <a href="#">Для пользователей</a>
                    <a href="#">Для кураторов</a>
                    <a href="#">новости</a>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/ku.js') }}"></script>

    </body>
</html>
