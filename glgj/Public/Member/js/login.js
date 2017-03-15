$(function(){
	//登录
    $(".login_btn").click(function(){
        var _form=$("form");
        $.post('?g=member&m=login',_form.serialize(),function(data){
            if(data.status==1){            
                window.location.href=data.url;
            }else{
                layer.msg(data.info,{icon:2,time:1000})
                return false;
            }
        },'json');
        return false;
    });
});
