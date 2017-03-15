$(function() {
	var submitBtn = $('.chksubmit');
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
	                    top  : top - o.obj.height() + 5
	                },200);
	            }
	        }
	    },
	    beforeSubmit : function() {
	    	if ( isAjax ) {
	    		submitBtn.addClass('ajax-post');
	    		submitBtn.click();
	    		return false;
	    	}
	    }
	});
	
	$('body').on('click', '.info', function() {
		$(this).hide().children('.Validform_checktip').removeClass('Validform_wrong').addClass('Validform_right').text('通过信息验证！');
		$(this).parent().prev().find('input').removeClass('Validform_error');
		$(this).parent().prev().find('select').removeClass('Validform_error');
		$(this).parent().prev().find('textarea').removeClass('Validform_error');
	});
});