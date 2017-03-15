$(function() {
	ajaxFlag = true;
	var page = 10;
	var num  = page;
	$(window).scroll(function() {
			var scrollTop	 = $(this).scrollTop();								// 滚动条距离顶部的高度
		　　var scrollHeight = $(document).height();							// 当前页面的总高度
		　　var windowHeight = $(this).height();								// 当前可视的页面高度
			var expectHeight = 0;												// 预加载距离
			if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {	// 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
				var data = {num:num};
				if ( ajaxFlag ) {
					ajaxFlag = false;
					$('.loading').show();
					$.get('?g=app&m=member&a=ajax_message',data,function(data) {
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
							var html = '<div class="station_message_list"><p class="station_message_list_time">'+ data[i]['addtime'] +'</p><div class="station_message_list_info"><p class="station_message_list_title">系统通知</p><p class="station_message_list_con">'+ data[i]['message'] +'</p></div></div>';
							$('.message_wrap').append(html);
						}
					},'json');
				}
			}
	});
});