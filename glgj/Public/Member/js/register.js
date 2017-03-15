$(function(){
	//登录
    $(".register_btn").click(function(){
        var _form=$("form");
        $.post('?g=member&m=register',_form.serialize(),function(data){
            if(data.status==1){
            	layer.open({
                    content: data.info,
                    style: 'background-color:#fff; color:red; border:none;'
                });
            	setTimeout(function () {window.location.href=data.url},1000);
            }else{
            	layer.open({
                    content: data.info,
                    style: 'background-color:#fff; color:red; border:none;'
                });
                return false;
            }
        },'json');
        return false;
    });
});