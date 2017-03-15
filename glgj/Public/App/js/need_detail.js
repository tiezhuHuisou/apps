$(function() {
    var ajaxFlag = true;
    var sign  = $("#sign").val();
    var nid   = $("#nid").val();
    var uid   = $("#uid").val();
    var title = $("#title").text();
    var uuid  = $('#uuid').val();
    var para  = uuid ? '&uuid=' + uuid : '';
	$(".collect_wrap").click(function(){
        console.log(ajaxFlag);
        if ( ajaxFlag === true ) {
            ajaxFlag = false;
        } else {
            return false;
        }
        if( !uid ) {
            layer.open({
                content : '请先登陆',
                time    : 1
            });
            ajaxFlag = true;
            return false;
        }
        if ( sign == 1 ) {
            url = '?g=app&m=index&a=favorite_del' + para;
        } else {
            url = '?g=app&m=index&a=favorite_add' + para;
        }
    	$.ajax( {    
    	    url: url,// 跳转到 action    
    	    data:{    
    	    	nid : nid,
    			uid : uid,
    			title : title
    	    },    
    	    type:'post',    
    	    cache:false,    
    	    dataType:'json',    
    	    success:function(data) {    
    	    	layer.open({
                    content : data.error,
                    time    : 1,
                    end     : function() {
                        if ( data.errno == 0 ) {
                            window.location.reload();
                        } else {
                            ajaxFlag = true;
                        }
                    }
                });
    	    }
    	});  
    });
});