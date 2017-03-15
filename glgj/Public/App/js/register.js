$( function() {
    $('.register_content_password_see').click(function(){                            //可见按钮点击事件
        if ( $(this).hasClass('nosee') ){                                            //可见按钮包含class('nosee')
            $(this).removeClass('nosee');                                            //移除class        
            $('.register_content_password_input').attr("type","text");               //密码可见
        } else {
            $('.register_content_password_see').addClass('nosee');                   //添加class 
            $('.register_content_password_input').attr("type","password");           //密码不可见
        }
    });
	var times = 60;		                                                            //初始化倒计时的时间
	$(".register_content_valid_btn").click( function(){								//点击发送，开始倒计时
		$(this).hide();
		$(".sent").css( 'display','block' );									    
		sendTime(times);
		var mphone = $('.register_content_phone_input').val();
		var data = {
			mphone:mphone
		}
		$.post('/apps/register/smsVerify',data,function(data){
			if(data.code == 40000){
				$.toptip(data.hint,'success');
			}else{
				$.toptip(data.hint,'error');
			}
		},'json');															//倒计时
	});
	
	$(".register_btn").click(function(){
		var _form=$("form");

		$.post('?g=app&m=register&a=index',_form.serialize(),function(data){
		    if(data.status==1){            
		        window.location.href=data.url;
		    }else{
		    	$.toptip(data.info, 'error');
		        return false;
		    }
		},'json');
		return false;
	});
	
});

function sendTime(times){															//倒计时函数
	if( times == 0 ) {
		$(".register_content_valid_btn").show().text("重新发送");
		$(".sent").hide();
	} else {
		times--;
		$(".sent").text( times +'秒后可重发');
		setTimeout( function(){sendTime(times)}, 1000 );
	}
	
};