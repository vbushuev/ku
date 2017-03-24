<div class="row kurator"  data-id="{{$id}}" data-status="{{$status_id}}">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Статус:<strong class="pull-right">{{$status->name}}</strong></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">Осталось:</div>
                    <div class="col-md-8"><strong class="pull-right timing status-{{$status->code}}" data-type="countdown" data-val="{{$status->left}}">--:--:--</strong></div>
                </div>
                <div class="row" style="text-align:center">
                    <br />
                    <div class="btn-group" role="group">
                        @if($status->code == "working")
                            <button class="btn btn-default btn-lg" id="stopstart"><i class="fa fa-pause"></i> Пауза</button>
                        @elseif($status->code == "paused")
                            <button class="btn btn-success btn-lg" id="stopstart"><i class="fa fa-play"></i> Начать</button>
                        @endif
                        <div class="btn-group">
                            <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Оплатить <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="pay" data-val="100" href="javascript:{0}">100 <i class="fa fa-rub"></i></a></li>
                                <li><a class="pay" data-val="200" href="javascript:{0}">200 <i class="fa fa-rub"></i></a></li>
                                <li><a class="pay" data-val="500" href="javascript:{0}">500 <i class="fa fa-rub"></i></a></li>
                                <li><a class="pay" data-val="1000" href="javascript:{0}">1000 <i class="fa fa-rub"></i></a></li>
                                <!--<li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">О себе <b><div class="editable"contenteditable="contenteditable" data-ref="/user/{{$id}}/update" data-id="{{$id}}" data-field="name">{{$name}}</div></b></div>
            <div class="panel-body">
                <div class="col-md-12">
                    <div class="row photo">
                        <img id="image" src="/storage/public/{{$image}}" alt="Ваше фото">
                        <div class="overlay">
                            <a class="upload" href="javascript:page.upload();"><i class="fa fa-2x fa-upload"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="edit" class="form form-contact" data-rel="/user/{{$id}}/update" data-method="post">
                            Контакты:
                            @include('inputs.contacts',["contacts"=>$contacts,"id"=>$id])
                            <br />
                            <script>
                                function saveContacts(){
                                    $("input.editable").change();
                                }
                            </script>
                            <button type="button" onclick="saveContacts()" class="btn btn-primary pull-right">Сохранить</button>
                        </div>
                    </div>
                    <!--
                        Тел: <div class="editable" contenteditable="contenteditable" data-ref="/user/{{$id}}/contact" data-id="{{$id}}" data-field="phone"></div>
                        E-Mail: <div class="editable  email" contenteditable="contenteditable" data-ref="/user/{{$id}}/contact" data-id="{{$id}}" data-field="email"></div>
                    -->
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Ваши пользователи</div>
            <div class="panel-body">
                @foreach($clients as $client)
                    <div class="row clients">
                        <div class="col-md-4">
                            <b>{{$client["name"]}}</b><br />
                            Контакты:<br />
                            @include('contacts',["contacts"=>$client["contacts"]])
                        </div>
                        <div class="col-md-8">
                            <i class="pull-right">Дата регистрации: {{$created_at}}</i><br />
                            Комментарий:<br />
                            <div class="editable" contenteditable="contenteditable" data-ref="/user/{{$client['id']}}/update" data-id="{{$client['id']}}" data-field="comment">{{$client["comment"]}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="panel-footer">
                Куратор
            </div>
        </div>
    </div>
</div>
<form style="display:none;" id="upload" method="POST" data-rel="/user/{{$id}}/update" action="/user/{{$id}}/update" data-method="get" data-id="#image" enctype="multipart/form-data">
    <input type="file" name="image" accept="image/x-png, image/gif, image/jpeg, image/jpg"/>
</form>
