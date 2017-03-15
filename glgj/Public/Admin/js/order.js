$(function() {
	$("#time_start").click( function() {				// 开始时间
		laydate({
			elem   : '#time_start',
			format : 'YYYY-MM-DD hh:mm:ss',
			istime : true,
			max    : laydate.now()
		});
	});
	
	$("#time_end").click(function() {					// 结束时间
		laydate({
			elem   : '#time_end',
			format : 'YYYY-MM-DD hh:mm:ss',
			istime : true,
			max    : laydate.now()
		});
	});
});