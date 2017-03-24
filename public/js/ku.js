"use strict";
var page = {
    submit:function(){
        var a = $.extend({
            form:$("form")[0],
            action:null,
            type:"post",
            fields:[],
            dataType:"json",
            callback:function(d,x){}
        },arguments.length?arguments[0]:{}),rel = a.form.attr("data-rel");
        if(a.action==null)a.action = (rel && rel!=false)?rel:document.location.href;
        try{
            $.ajax({
                url:a.action,
                type:a.type,
                dataType:a.dataType,
                data:this.getfields((a.fields.length?a.fields:a.form.find("input,select,textarea"))),
                contentType: false,
                cache: false,
                processData:false,
                success:function(d,x,s){
                    a.callback(d,x);
                }
            });
        }
        catch(e){
            console.debug(e.name+": "+e.message);
            return false;
        }

    },
    getfields:function(fs){
        var ret = new FormData();
        ret.append('_token',Laravel.csrfToken);
        fs.each(function(){
            if(page.validate(this)){
                console.debug($(this).attr("name")+" "+$(this).attr("type")+" data:"+$(this).get());
                if($(this).attr("type")=="file")ret.append($(this).attr("name"),$(this).prop('files')[0]);
                else ret.append($(this).attr("name"),$(this).get());
            }
        });
        console.debug(ret);

        return ret;
    },
    validate:function(f){
        var $t = $(f);
        if($t.hasClass("required") || $t.parent().hasClass("required")){
            if(!$t.val().trim().length) {
                $t.parent().addClass('required-alert');
                $t.focus();
                $t.on('keyup',function(e){
                    console.debug(e.type+" occurs");
                    if($(this).val().trim().length){
                        $(this).parent().removeClass('required-alert');
                        $(this).unbind('keyup');
                    }
                })
                throw new Error($t.attr('name')+' is required field');
            }
        }
        return true;
    },
    upload:function(){
        console.debug($("#upload").get());
        $("#upload input[type=file]").trigger("click");
        $("#upload input[type=file]").unbind("change").on("change",function(){
            //$("#upload").submit();
            page.submit({
                form:$('#upload'),
                callback:function(d){
                    document.location.reload();
                }
            });
        });
    }
};
var ku = {
    city:{
        get:function(n){
            var r = [];
            $.ajax({
                url:"/city/"+n,
                dataType:"json",
                async:false,
                success:function(d,x,s){
                    r = d;
                },
                error:function(d,x,s){
                    if(d.status=="404"){
                        r = ku.city.create(n);
                    }
                }
            });
            return r;
        },
        create:function(n){
            var r = [];
            $.ajax({
                url:'/city/create/'+n,
                type:"POST",
                data:{_token:window.Laravel.csrfToken},
                dataType:"json",
                success:function(d){
                    r.push(d);
                }
            });
            return r;
        }
    },
    status:function(){
        var id = arguments.length?arguments[0]:null;
        if(id==null)return "nostatus";
        if(ku._store.statuses==null){
            $.ajax({
                url:"/statuses",
                dataType:"json",
                async:false,
                success:function(d,x,s){
                    console.debug(d);
                    ku._store.statuses = new Object();
                    for(var i in d){
                        ku._store.statuses[d[i].id] = d[i].name;
                    }

                }
            });
        }
        return ku._store.statuses[id];
    },
    _store:{
        cities:null,
        statuses:null
    }
};
$(document).ready(function(){
    $("input[name='city']").on("change",function(e){
        var cities = ku.city.get($(this).val());
        $("input[name='city_id']").val(cities[0].id);
    });
    $(".editable").on("change",function(e){
        //console.debug("key:"+e.key+" keyCode:"+e.keyCode);
        //if(e.key.match(/^[\w\s\d]$/)){
            var $t = $(this),d = {},val = $t.text();
            if(val.length<3)val=$t.val();
            if(val.length<3)return;
            d[$t.attr("data-field")]=val;
            $.ajax({
                url:$t.attr("data-ref"),
                dataType:"json",
                data:d,
                success:function(d){
                    console.debug(d);
                }
            });
        //}
    });
    $("#stopstart").on("click",function(e){
        var $t = $(this);
        if($t.hasClass("btn-default")){ //on going now
            // set status
            $.get("/status/paused/change/",function(){document.location.reload()});
        }else if($t.hasClass("btn-success")){//paused
            $.get("/status/working/change/",function(){document.location.reload()});
        }
    });
    $(".pay").on("click",function(e){
        var $t = $(this),a=$t.attr("data-val");
        $.get("/status/pay/"+a,function(){document.location.reload()});
    });
    if($(".timing").length){
        var interval=function(v){
            var r = {
                d : Math.floor(v/(1440*60)),h:0,m:0,s:0
            };
            r.h = Math.floor((v-r.d*1440*60)/(3600));
            r.m = Math.floor((v-r.d*1440*60-r.h*3600)/60);
            r.s = Math.floor((v-r.d*1440*60-r.h*3600)%60);
            return r;
        }
        var pad = function(d,max){
            d = d.toString();
            return d.length < max ? pad("0" + d, max) : d;
        };
        var pad2 = function(d){
            return pad(d,2);
        }
        var days = function(s){
            var r = "дней",d = s+"", l = parseInt(d[d.length-1]);
            if(10>parseInt(s) || parseInt(s)>15 ){
                //console.debug("func days "+d+" length is "+d.length+" last symbol is "+d[d.length-1]);
                if(l==1) r = "день";
                else if(0<l&&l<5) r = "дня";
            }

            return d+" "+r;
        }
        var settimming = function(th){
            var v = $(th).attr("data-val"),t=$(th).attr("data-type");
            switch(t){
                case "countdown":v--;break;
                default:v++;break;
            }
            var i = interval(v);
            $(th).text(days(i.d)+" "+pad2(i.h)+":"+pad2(i.m)+":"+pad2(i.s)).attr("data-val",v);
        };
        $(".timing").each(function(){
            settimming(this);
        })
        setInterval(function(){
            $(".timing.status-working").each(function(){
                settimming(this);
            });
        },1000);
    }

});
