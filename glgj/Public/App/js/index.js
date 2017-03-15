var swiper = new Swiper('.swiper_auto .swiper-container', {  // 轮播图初始化
    pagination                      : '.swiper_auto .swiper-pagination',
    slidesPerView                   : 1,
    autoplay                        : 3000,
    autoplayDisableOnInteraction    : false,
    loop                            : true
});
// 公司
var swiper = new Swiper('.swiper_company .swiper-container', {  // 轮播图初始化
    slidesPerView                   : 3,
    spaceBetween                    : 10,
    slidesOffsetBefore              : 10,
    slidesOffsetAfter               : 10,
    loop                            : true
});
// 最新
var swiper = new Swiper('.swiper_newJoin .swiper-container', {  // 轮播图初始化
    slidesPerView                   : 3,
    spaceBetween                    : 10,
    slidesOffsetBefore              : 10,
    slidesOffsetAfter               : 10,
    loop                            : true
});

$(function(){
    /* 初始化 */
    setInterval('autoScroll(".report_title")', 2000 );
    initImgHeight();
    $(window).resize(function(){
        initImgHeight();
    });

    // 发布判断
    $('.js_release').on('touchend',function(e) {
        var logined = false;
        if( !logined ){         // 未登录

            $.modal({
                title: "温馨提示",
                text: "登录后才可以发布哦",
                buttons: [
                { text: "取消", className: "default", onClick: function(){ console.log(3)} },
                { text: "去登陆", onClick: function(){
                    window.location.href="页面链接"; 
                    console.log('跳转')
                } },
                ]
            });
            return false;
        }
        
    });
});


/*
 * 信息上下滚动
 */
function autoScroll(obj) {
    var listObj = $(obj).find('.list');
    var _mgt    = - listObj.find('li').eq(0).height() + 'px';
    if ( listObj.find('li').length == 1 ) {
        return false;
    } else {
        listObj.animate({
            marginTop : _mgt
        }, 500, function() {
            $(this).css({ marginTop : '0px' }).find('li:first').appendTo(this);
        });
    }
}

/*
 * 图片高度初始化
 */
function initImgHeight(){
    // 公司
    $('.swiper_company .img_wrap').height( $('.swiper_company .img_wrap').width() );
    // 更多
    $('.swiper_newJoin .img_wrap').height( $('.swiper_newJoin .img_wrap').width() );
}