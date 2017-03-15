$(function() {
	var submitBtn = $('.link_btn');
	var isAjax 	  = submitBtn.hasClass('ajax-post') ? true : false;
	if ( isAjax ) {
		submitBtn.removeClass('ajax-post');
	}
	$('.chkform').Validform({
	    tiptype   : function( msg, o, cssctl ) {
	        if ( !o.obj.is('form') ) {
	            var objtip  = o.obj.parent().next().find('.Validform_checktip');
	            cssctl( objtip, o.type );
	            objtip.text( msg );
	            var infoObj = o.obj.parent().next().find('.info');
	            if ( o.type == 2 ) {
	                infoObj.fadeOut();
	            } else {
	                if( infoObj.is(':visible') ) {
	                    return;
	                }
	                var left = o.obj.position().left, // offset()
	                top      = o.obj.position().top;
	                infoObj.css({
	                    left : left,
	                    top  : top - o.obj.height() + 10
	                }).show().animate({
	                    top  : top - o.obj.height() - 10
	                },200);
	            }
	        }
	    },
	    beforeSubmit : function() {
	    	var _form=$("form");
	    	$.post(_form.attr("action"),_form.serialize(),function(data){
	    		if(data.status){
	    			layer.open({
	    			    content : '资料更新成功',
	    			    style 	: 'background-color:#fff; color:red; border:none;',
	    			    time 	: 1,
	    			    end		: function() {
	    			    	window.history.go(-1);
	    			    }
	    			});
	    		}else{
	    			layer.open({
	    			    content : data.info,
	    			    style 	: 'background-color:#fff; color:red; border:none;',
	    			    time 	: 1
	    			});
	    		}
	    	},'json');
	    	return false;
	    }
	});
	$('body').on('click', '.info', function() {
		$(this).hide().children('.Validform_checktip').removeClass('Validform_wrong').addClass('Validform_right').text('通过信息验证！');
		$(this).parent().prev().find('input').removeClass('Validform_error');
	});
	
	
	//----------------------------------我的JS--------------------------------
	//点击 编辑
	$(".-mob-editor-open").click(function(){
		$(this).text("保存").addClass("save");
		$(".exit").hide();
		$(".weui-cell").each(function(index,ele){
			$(this).addClass("weui-cell_access");
			$(this).click(function(){
				if (index > 0 && index < 5) {
					window.location.href="?g=app&m=member&a=modify_information"
				} else if(index == 5){
					$.actions({
						actions: [{
							text: "男",
							onClick: function() {
								//do something
							}
						}, {
							text: "女",
							onClick: function() {
								//do something
							}
						}]
					});
				}
			})
		})
		$(".save").click(function(){
			location.reload()
		})
	})
});