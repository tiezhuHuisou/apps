$(function(){
    $('.login_content_password_see').click(function(){                          //可见按钮点击事件
        if ( $(this).hasClass('nosee') ){                                       //可见按钮包含class('nosee')
            $(this).removeClass('nosee');                                       //移除class        
            $(this).siblings('.login_content_password_input').attr("type","text");             //密码可见
            
        } else {
            $('.login_content_password_see').addClass('nosee');                 //添加class 
            $(this).siblings('.login_content_password_input').attr("type","password");         //密码不可见
            
        }
    });


    //登录
    $(".login_btn").click(function(){
        var _form=$("form");

        $.post('?g=app&m=login',_form.serialize(),function(data){
            if(data.status==1){            
                window.location.href=data.url;
            }else{
                $.toptip((data.info), 'error');
                return false;
            }
        },'json');
    });
});
var hieght =  window.screen.height;
window.onresize = function(){
    if(window.screen.height < hight){
        $('.login_three').hide();
        return false;
    }
    $('.login_three').show();
}