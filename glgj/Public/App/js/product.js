$(function(){
    $('.shopping_car').click(function() {       // 添加到购物车
    	var tmpsign=$(".tmpsign").val();
        if(tmpsign==1){
            var id     = $(this).attr('data-id');   
            var data   = {id:id,num:1};             
            $.post('/shop/?g=app&m=cart&a=addgoods',data,function(data) {
                changeCartNum( 1 );
                layer.open({
                    content : data.info,
                    time    : 1
                });
                return false;
            },'json');
        }else{
           window.location.href="?g=app&m=login";
        }
    });

    ajaxFlag = true;
    var page = 10;
    var num  = page;
    $(window).scroll(function() {
        if ( $('.classify_nav').is(':hidden') ) {
            var scrollTop    = $(this).scrollTop();                             // 滚动条距离顶部的高度
        　　var scrollHeight = $(document).height();                            // 当前页面的总高度
        　　var windowHeight = $(this).height();                                // 当前可视的页面高度
            var expectHeight = 0;                                               // 预加载距离
            if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {    // 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
                var title = '';
				if ( $("#search").val() ) {
					title = $("#search").val();
				}
				var data = {num:num,categoryid:categoryid,title:title};
                if ( ajaxFlag ) {
                    ajaxFlag = false;
                    $('.loading').show();
                    $.get('?g=app&m=product&a=ajaxlist',data,function(data) {
                        $('.loading').hide();
                        if ( !data.length ) {
                            ajaxFlag = false;
                            return false;
                        } else {
                            ajaxFlag = true;
                            num += data.length;
                        }
                        if ( data.length < page ) {
                            ajaxFlag = false;
                            $('.loading').show().html('没有了');
                        }
                        for (var i = 0; i < data.length; i++) {
                            $('.product_list_wrap').append('<div class="product_list"> <a href="?g=app&m=product&a=detail&id=' + data[i]['id'] + '"><img class="delay" src="' + data[i]['img'] + '" width="130" height="130" /></a> <div class="product_list_info"> <a href="?g=app&m=product&a=detail&id=' + data[i]['id'] + '"> <p class="product_info_title">' + data[i]['title'] + '</p> </a> <p class="product_info_text">' + data[i]['short_title'] + '</p> <p class="product_price"> <span class="product_price_p">￥' + data[i]['price'] + '</span> </p> <div class="clear"></div> </div> </div>');
                        }
                    },'json');
                }
            }
        }
    });
});

/**
 * 加入购物车-改变购物车商品数量
 * @param  {[obj]} obj [购物车商品数量对象]
 * @param  {[int]} num [新增商品数量]
 */
function changeCartNum( num ) {
    var cartNum = parseInt( $('.cart_num').text() );
    num = parseInt( num );
    if ( cartNum == 99 ) {
        return false;
    } else if ( cartNum + num >= 99 ) {
        cartNum = 99;
    } else {
        cartNum += num;
    }
    $('.cart_num').text( cartNum ).show();
}