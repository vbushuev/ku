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
        var ret = {};
        fs.each(function(){
            if(page.validate(this))ret[$(this).attr("name")]=$(this).val();
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
};
$(document).ready(function(){
    $("input[name='city']").on("change",function(e){
        var cities = ku.city.get($(this).val());
        $("input[name='city_id']").val(cities[0].id);
    });
});
