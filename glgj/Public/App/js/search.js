$( function () {
	$(".search_sort_show").click( function () {									//搜索类目列表
		$(".search_list").toggle();												//类目列表显示
		$(".select_down").toggle();
		$(".select_up").toggle();
	});

	$('.search_list').click( function () {
		$(".search_sort_show").text( $( this ).text() );   				    	//把选中的类目返回给父级		
		$(".search_list").toggle();												//类目列表隐藏
		$(".select_down").toggle();
		$(".select_up").toggle();
	});

	var isleave = false;
	$(".search_sort").mouseleave(function() {
		isleave = true;
	});
	$(".search_sort").mouseover(function() {
		isleave = false;
	});
	$(document).click(function() {
		if ( isleave ) {
			$(".search_list").hide();											//类目列表隐藏
			$(".select_down").show();
			$(".select_up").hide();
		}
	});
	//搜索
	$('.search_img').click(function() {
		url = window.location.search;
		if(url.indexOf("&title=") >0 ){
			url = url.substr(0, url.lastIndexOf('&'));
		}
		search = $("#search").val();
		location.href = url+'&title='+search;
	});
});