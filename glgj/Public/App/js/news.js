var swiper = new Swiper('.swiper-container', {				// 轮播图初始化
	pagination						: '.swiper-pagination',
	slidesPerView					: 1,
	autoplay						: 3000,
	autoplayDisableOnInteraction	: false,
	loop							: true
});
$(function() {

	// 加载
	ajaxFlag = true;	//状态标记
	var page = 1;		//每次
	var num  = 0;

	$(window).scroll(function() {
		if ( $('.classify_nav').is(':hidden') ) {
			var scrollTop	 = $(this).scrollTop();								// 滚动条距离顶部的高度
		　　var scrollHeight = $(document).height();							// 当前页面的总高度
		　　var windowHeight = $(this).height();								// 当前可视的页面高度
			var expectHeight = 0;												// 预加载距离
			if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {	// 距离顶部+当前高度 >=文档总高度 即代表滑动到底部

				var data = {page:page,title:title,cid:cid,};
				if ( ajaxFlag ) {
					ajaxFlag = false;
					$('.loading').show();
					$.get('?g=app&m=news&a=ajaxlist',data,function(data) {
						$('.loading').hide();
						if ( !data.length ) {
							ajaxFlag = false;
							return false;
						} else {
							ajaxFlag = true;
							page += 1;
						}
						if ( data.length < 10 ) {
							ajaxFlag = false;
							$('.loading').show().html('没有了');
						}
						for (var i = 0; i < data.length; i++) {
							var html = '<li><a href="?g=app&m=news&a=detail&id=' + data[i]['id'] + '">';
							if(data[i]['image']){
								var head_pic = '<img class="fl mgl16" src="' + data[i]['image'] + '" width="90" height="70" />';
							}else{
								var head_pic = '';
							}
							html += head_pic;
							html += '<div class="new_title"> <p class="new_title_main">' + data[i]['title'] + '</p> <p class="new_title_sub">' + data[i]['short_title'] + '</p> \
							<div class="opts">\
								<a href="javascript:void(0)">\
									<span class="comment">16</span> \
								</a>\
								<span class="collect">3</span>\
								<span class="time">时间</span>\
							</div>\
							</div> <div class="clear"></div> </a> </li>';
							$('.news_list ul').append(html);
						}
					},'json');
				}
			}
		}
	});
});

// 点击收藏
$('.news_list').on('click','.collect',function(){
	$(this).toggleClass('active');
});