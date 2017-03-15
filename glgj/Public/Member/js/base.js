$(function() {
	$('.left,.main').height( getPageSize()[3] - $('.top').height() );		// 设置左侧和主体的高度：页面高度 - 顶部高度
	$('.main').width( getPageSize()[0] - $('.left').width() );				// 设置主体宽度：页面宽度 - 左侧宽度
	var tempPt = ( getPageSize()[3] - $('.top').height() - 6 * 86 ) / 2;	// 设置左侧ul:padding-top = ( 浏览器高度 - 顶部高度 - 左侧图标个数 * 左侧图标高度 ) / 2
	$('.left_wrap').css('padding-top', tempPt);								// 左侧整体居中
	$(window).resize(function() {											// 监测窗体大小
		$('.main').width( getPageSize()[0] - $('.left').width() );			// 设置主体宽度：页面宽度 - 左侧宽度
		$('.left,.main').height( getPageSize()[3] - $('.top').height() );	// 设置左侧和主体的高度：页面高度 - 顶部高度
	});
});

/**
 * 获取横向滚动条和竖向滚动条的宽度
 * return object __scrollBarWidth 属性horizontal：横向滚动条宽度 属性 vertical：竖向滚动条宽度
 */
function getScrollBarWidth() {
	var scrollBarHelper = document.createElement("div");
	scrollBarHelper.style.cssText = "overflow:scroll;width:100px;height:100px;"; 
	document.body.appendChild(scrollBarHelper);
	if (scrollBarHelper) {
		__scrollBarWidth = {
			horizontal : scrollBarHelper.offsetHeight - scrollBarHelper.clientHeight,
			vertical   : scrollBarHelper.offsetWidth - scrollBarHelper.clientWidth
		};
	}
	document.body.removeChild(scrollBarHelper);
	return __scrollBarWidth;
}

/**
 * 获取页面的宽度、高度，浏览器的宽度、高度
 * return array arrayPageSize
 */
function getPageSize() {
	var xScroll, yScroll;
	if (window.innerHeight && window.scrollMaxY) {
		xScroll = window.innerWidth + window.scrollMaxX;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else {
		if (document.body.scrollHeight > document.body.offsetHeight) {
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else {
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
	}
	var windowWidth, windowHeight;
	if (self.innerHeight) {
		if (document.documentElement.clientWidth) {
			windowWidth = document.documentElement.clientWidth;
		} else {
			windowWidth = self.innerWidth;
		}
		windowHeight = self.innerHeight;
	} else {
		if (document.documentElement && document.documentElement.clientHeight) {
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else {
			if (document.body) {
				windowWidth = document.body.clientWidth;
				windowHeight = document.body.clientHeight;
			}
		}
	}
	if (yScroll < windowHeight) {
		pageHeight = windowHeight;
	} else {
		pageHeight = yScroll;
	}
	if (xScroll < windowWidth) {
		pageWidth = xScroll;
	} else {
		pageWidth = windowWidth;
	}
	arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight);
	return arrayPageSize;
}

/**
 * 获取相对网页顶部滚动的距离
 * @return int scrollTop 相对网页顶部滚动的距离
 */
 function getScrollTop() {
 	var scrollTop = 0;
 	if( document.documentElement && document.documentElement.scrollTop ) {
 		scrollTop = document.documentElement.scrollTop;
 	}
 	else if( document.body ) {
 		scrollTop = document.body.scrollTop;
 	}
 	return scrollTop;
 }