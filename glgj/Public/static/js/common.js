//dom加载完成后执行的js
$(function(){
    //ajax get请求
    $('body').on('click', '.ajax-get', function() {
        var target;
        var that = this;
        var _this=$(this);
        console.log(_this.hasClass(confirm));
        if ( _this.hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = _this.attr('href')) || (target = _this.attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    layer.msg(data.info,{icon:1,time:1000});
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    layer.msg(data.info,{icon:2,time:1000});
                }
            });

        }
        return false;
    });

    //ajax post submit请求
    $('body').on('click', '.ajax-post', function() {
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                    target = $(this).attr('url');
                }else{
                    target = window.location.href;
                    // target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            
            $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    layer.msg(data.info,{icon:1,time:1000});
                    setTimeout(function(){
                        $(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            location.reload();
                        }
                    },1500);
                }else{
                    layer.msg(data.info,{icon:2,time:1000});
                    setTimeout(function(){
                        $(that).removeClass('disabled').prop('disabled',false);
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $('#top-alert').find('button').click();
                        }
                    },1500);
                }
            });
        }
        return false;
    });

    // 复选框全选
    $('#check_all').change(function() {
        if ( $(this).prop('checked') ) {
            $('.check_box').prop( 'checked', true );
        } else {
            $('.check_box').prop( 'checked', false );
        }
    });

    $('.check_box').change(function() {
        if ( $(this).prop('checked') ) {
            $( '.p' + $(this).val().toString() ).find('.check_box').prop( 'checked', true );
        } else {
            $( '.p' + $(this).val().toString() ).find('.check_box').prop( 'checked', false );
        }
    });

});

