$(function(){
	var viewHeight = getPageSize()[3];
	$(".top_nav").click( function () {	
		$(".classify_nav").show();
		$('.mask').show();
		$('html,body').scrollTop(0);
		if( viewHeight >= $(".classify_nav").height() ) {			// 如果遮罩层的高度超过屏幕的高度，则可以滚动
			$('body').bind('touchmove',function(e) {				// body禁止滚动
				e.preventDefault();
			});
			// $('.classify_nav').height( $('body').height() );
		}
	});

	$(".classify_close").click(function() {							// 关闭分类选择
		closeClassify();
	});

	$('.back_one').click(function() {								// 返回一级分类
		$(this).parent().hide().find('.classify_item').remove();
		$('html,body').scrollTop(0);
		$('.classify_one').show();
	});

	$('.back_two').click(function() {								// 返回二级分类
		$(this).parent().hide().find('.classify_item').remove();
		$('html,body').scrollTop(0);
		$('.classify_two').show();
	});

	$('body').on('click', '.classify_item', function() {			// 点击分类
		_this = $(this);											// 当前对象
		var cid = _this.attr('data-cid');							// 获取分类id
		var url = window.location.href;
		var arr = url.split('&');
		if ( arr.length > 2 && arr[2].substr( 0, 1 ) == 'a' ) {
			url = arr[0] + '&' + arr[1] + '&' + arr[2];
		} else {
			url = arr[0] + '&' + arr[1];
		}
		if ( _this.attr('data-flag') == 1 ) {						// 如果有下级分类
			var str = '';											// 下级分类html
			for ( var i = 0; i < data[cid].length; i++ ) {			// 循环下级分类
				if ( data[data[cid][i]['cid']] ) {
					str += '<a class="classify_item" href="javascropt:void(0);" data-cid="' + data[cid][i]['cid'] + '" data-flag="1">' + data[cid][i]['cname'] + '</a>';
				} else {
					str += '<a class="classify_item" href="' + url + '&categoryid=' + data[cid][i]['cid'] + '" data-cid="' + data[cid][i]['cid'] + '" data-flag="0">' + data[cid][i]['cname'] + '</a>';
				}
			}
			_this.parent().hide().next().append(str).show();
			$('html,body').scrollTop(0);
		} else {													// 如果没有下级分类
			closeClassify();										// 关闭分类选择弹出层
		}
	});
});

/**
 * [关闭分类选择]
 */
function closeClassify() {
	$('.classify_two,.classify_three').find('.classify_item').remove();
	$('.classify_one').show();
	$(".classify_two,.classify_three,.classify_nav,.mask").hide();
	$('body').unbind('touchmove');
}

// 获取页面的高度、宽度
function getPageSize() {
	var xScroll, yScroll;
	if (window.innerHeight && window.scrollMaxY) {
		xScroll = window.innerWidth + window.scrollMaxX;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else {
		if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac 
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else {	// Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari 
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
	}
	var windowWidth, windowHeight;
	if (self.innerHeight) {				// all except Explorer 
		if (document.documentElement.clientWidth) {
			windowWidth = document.documentElement.clientWidth;
		} else {
			windowWidth = self.innerWidth;
		}
		windowHeight = self.innerHeight;
	} else {
		if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode 
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else {
			if (document.body) {		// other Explorers 
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}
		}
	} 
	// for small pages with total height less then height of the viewport 
	if (yScroll < windowHeight) {
		pageHeight = windowHeight;
	} else {
		pageHeight = yScroll;
	} 
	// for small pages with total width less then width of the viewport 
	if (xScroll < windowWidth) {
		pageWidth = xScroll;
	} else {
		pageWidth = windowWidth;
	}
	arrayPageSize = new Array( pageWidth, pageHeight, windowWidth, windowHeight );
	return arrayPageSize;
}