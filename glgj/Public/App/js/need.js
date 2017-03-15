$(function() {
	ajaxFlag = true;
	var page = 10;
	var num  = page;
	$(window).scroll(function() {
		if ( $('.classify_nav').is(':hidden') ) {
			var scrollTop	 = $(this).scrollTop();								// 滚动条距离顶部的高度
		　　var scrollHeight = $(document).height();							// 当前页面的总高度
		　　var windowHeight = $(this).height();								// 当前可视的页面高度
			var expectHeight = 0;												// 预加载距离
			if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {	// 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
				var title = '';
				if ( $("#search").val() ) {
					title = $("#search").val();
				}
				var data = {num:num,categoryid:categoryid,title:title};
				if ( ajaxFlag ) {
					ajaxFlag = false;
					$('.loading').show();
					$.get('?g=app&m=index&a=ajaxneedlist',data,function(data) {
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
							if ( !data[i]['logo'] ) {
								data[i]['logo'] = defaultImgUrl;
							}
							if ( !data[i]['img'] ) {
								data[i]['img'] = defaultImgUrl;
							}
							$('.buy_list_wrap').append('<div class="buy_wrap"> <div class="buy_time"> <img src="' + data[i]['logo'] + '" width="40" height="40" /> <div class="buy_name">' + data[i]['buy_name'] + '</div> <div class="time">' + data[i]['modify_time'] + '</div> </div> <a class="buy_list" href="?g=app&m=index&a=need_detail&id=' + data[i]['id'] + '"> <img src="' + data[i]['img'] + '" width="80" height="80" /> <div class="buy_info"> <p class="pro_title">' + data[i]['title'] + '</p> <p class="pro_dis">' + data[i]['short_title'] + '</p> </div> <div class="clear"></div> </a> </div>');
						}
					},'json');
				}
			}
		}
	});
});