$(function(){
    var swiper = new Swiper('.swiper-container', {	                                    // 轮播图初始化
        pagination						: '.swiper-pagination',
        slidesPerView					: 1,
        autoplay						: 3000,
        autoplayDisableOnInteraction	: false,
        loop							: true
    });

    var ajaxFlag = true;
    var nid      = $("#nid").val();
    var title    = $(".goods_name").text();
    var sign     = $("#sign").val();
    var uid      = $("#uid").val();
    var uuid     = $("#uuid").val() ? '&appsign=1&uuid=' + $("#uuid").val() : '';
    $('.collect_wrap').click(function() {			// 点击收藏图片
        if ( ajaxFlag === true ) {
            ajaxFlag = false;
        } else {
            return false;
        }
        sign = $("#sign").val();
        /* 判断登陆 */
        if ( uid == '' ) {
            layer.open({
                content : '请先登陆',
                time    : 1
            });
            ajaxFlag = true;
            return false;
        }
        /* 判断是否收藏请求相对于的地址 */
        if ( sign == 1 ) {
            var url = '?g=app&m=product&a=favorite_del' + uuid;
        } else {
            var url = '?g=app&m=product&a=favorite_add' + uuid;
        }
    	
        /* post请求 */
        $.post(url, {nid: nid, title: title}, function( data ) {
            layer.open({
                content : data.info,
                time    : 1,
                end     : function() {
                    if ( data.status == 1 ) {
                        $("#sign").val( 1 - sign );
                    }
                    ajaxFlag = true;
                }
            });
        });
	});

    $(".line").width( ( $('body').width() ) / 4 );                                      //设置每一项分享方式为屏幕宽度的 1/4
    $(".share_btn").click(function(){                                                   //分享按钮的点击事件
        $(".mask").show();                                                              //显示遮罩层        
        $(".share_bomb_box").show();                                                    //显示分享方式弹框
        $("body").bind("touchmove",function(e){                                         //禁止屏幕滑动     
            e.preventDefault();  
        });
    });
    $(".share_bomb_box_cancel").click(function(){                                       //弹框取消按钮点击事件
        $(".mask").hide();                                                              //隐藏遮罩层
        $(".share_bomb_box").hide();                                                    //隐藏分享方式弹框
        $("body").unbind("touchmove");                                                  //启用屏幕滑动
    });
    //分享
	mobShare.config( {
	    appkey: 'a537fde044f0', // appkey
	    params: {
	        title: $('.goods_name').html(), // 分享标题
	        description: $('.goods_info_sketch').html(), // 分享内容
	    }
	} );
});