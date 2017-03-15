var swiper = new Swiper('.product_wrap .swiper-container', {                                      // 轮播图初始化
    pagination                      : '.product_wrap .swiper-pagination',
    slidesPerView                   : 4,
    slidesOffsetBefore              : 10,
    slidesOffsetAfter               : 10,
    spaceBetween                    : 6
});
$(function(){
    /* 初始化产品图片容器高度 */
    $('.product_wrap .list_img').height( $('.product_wrap .swiper-slide').width() );
    $(window).resize(function(){
        $('.product_wrap .list_img').height( $('.product_wrap .swiper-slide').width() );
    });
});