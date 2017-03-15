$(function(){
	//登录
    $(".forget_btn").click(function(){
        var _form=$("form");
        $.post('?g=member&m=register&a=forget',_form.serialize(),function(data){
        	if(data.status==1){ 
                window.location.href=data.url;
            }else{
                layer.open({
                    content: data.info,
                    style: 'background-color:#fff; color:red; border:none;',
                    time: 2
                });
                return false;
            }
        },'json');
        return false;
    });
});