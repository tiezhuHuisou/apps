var swiper = new Swiper('.swiper-container', {                                      // 轮播图初始化
    pagination                      : '.swiper-pagination',
    slidesPerView                   : 1,
    autoplay                        : 3000,
    autoplayDisableOnInteraction    : false,
    loop                            : true
});
$(function(){
    if ( appsign == '1' ) {
        /* 遍历图文详情中的图片 */
        $('article img').each(function(i,n){
            if ( $(n).parent().get(0).tagName == 'A' ) {            // 图片一级父元素为a
                $(n).parent().attr( 'href', 'http://huisou.m.huisou.com/?flag=clickimg&index=' + i );
            } else {                                                // 图片一级父元素不为a
                var html = '';
                html += '<a href="http://huisou.m.huisou.com/?flag=clickimg&index=' + i + '">';
                html += $(n).prop('outerHTML');
                html += '</a>';
                $(n).prop('outerHTML', html);
            }
        });
    }
    
    /* 关闭更多操作提示信息 */
    $('.tips_close').click(function(){
        $('.tips').hide();
    });

    /* 滑动到底部加载更多产品 */
    var ajaxFlag = true;
    var page     = 10;
    var num      = page;
    $(window).scroll(function(){
        var scrollTop    = $(this).scrollTop();                             // 滚动条距离顶部的高度
        var scrollHeight = $(document).height();                            // 当前页面的总高度
        var windowHeight = $(this).height();                                // 当前可视的页面高度
        var expectHeight = 0;                                               // 预加载距离
        if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {    // 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
            if ( ajaxFlag ) {
                $('.loading').show();
                $.get('?g=app&m=apps&a=ajaxlist',{ num : num }, function(data) {
                    $('.loading').hide();
                    if ( !data.length ) {
                        ajaxFlag = false;
                        $('.loading').html('没有了').show();
                        return false;
                    } else {
                        ajaxFlag = true;
                        num += data.length;
                    }
                    if ( data.length < page ) {
                        ajaxFlag = false;
                        $('.loading').html('没有了').show();
                    }
                    var html  = '';
                    for ( var i = 0; i < data.length; i ++ ) {
                        if ( !data[i].img ){
                            datai[i].img = defaultImg;
                        }
                        html += '<a class="groom_list" href="javascript:void(0);">';
                        html += '<div class="list_wrap">';
                        html += '<div class="list_img">';
                        html += '<img src="http://img5.imgtn.bdimg.com/it/u=1889250223,3195298994&fm=21&gp=0.jpg" />';
                        html += '</div>';
                        html += '<div class="list_info">';
                        html += '<p class="info_title">标题标题</p>';
                        html += '<p class="info_desc">描述描述描述描述</p>';
                        html += '<p class="info_price">';
                        html += '<span class="price_start">￥120.00</span>';
                        html += '<span class="price_line">-</span>';
                        html += '<span class="price_end">￥220.00</span>';
                        html += '</p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</a>';
                    }
                    $('.groom_wrap').append(html);
                },'json');
            }
        }
    });
});