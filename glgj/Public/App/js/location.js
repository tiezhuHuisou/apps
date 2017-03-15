$( function() {
	/* 选择省 */
	$(".province_name").click(function(){
		$(".select_province").hide();
		$(".select_city").show();
		var id=$(this).attr('data-id');		
		var city_arr=data[id];
		var str='<p class="go_back">返回</p>';
		for(var i=0;i<city_arr.length;i++){
			str +='<p class="city_name" data-id="'+city_arr[i]['id']+'">'+city_arr[i]['name']+'</p>';
		}
		$(".city").html(str);
		$(document).scrollTop(0);
		$('.go_back').click(function() {
			$(this).parent().parent().hide().prev().show();
			$(document).scrollTop(0);
		});
		/* 选择市 */
		$('.city_name').click(function() {
			var id=$(this).attr('data-id');		
			var area_arr=data_area[id];
			if ( !area_arr) {
				var data={id:id};
				$.post('?g=app&m=index&a=location',data,function(data){
					if(data.status==1){
						window.location.href=data.url;
					}
				},'json');
			} else {
				var str='<p class="go_back">返回</p>';
				for(var i=0;i<area_arr.length;i++){
					str +='<p class="area_name" data-id="'+area_arr[i]['id']+'">'+area_arr[i]['name']+'</p>';
				}
				$(".area").html(str);
				$(".select_city").hide();
				$(".select_area").show();
				$('.go_back').click(function() {
					$(this).parent().parent().hide().prev().show();
					$(document).scrollTop(0);
				});
				/* 选择区 */
				$('.area_name').click(function() {
					var id=$(this).attr('data-id');		
					var area_arr=data_area[id];
					if ( !area_arr) {
						var data={id:id};
						$.post('?g=app&m=index&a=location',data,function(data){
							if(data.status==1){
								window.location.href=data.url;
							}
						},'json');
					} else {
						var str='<p class="go_back">返回</p>';
						for(var i=0;i<area_arr.length;i++){
							str +='<p class="area_name" data-id="'+area_arr[i]['id']+'">'+area_arr[i]['name']+'</p>';
						}
						$(".area").html(str);
						$(".select_city").hide();
						$(".select_area").show();
					}
					$(document).scrollTop(0);
				});
			}
			$(document).scrollTop(0);
		});
	});

	$('.go_back').click(function() {
		$(this).parent().parent().hide().prev().show();
		$(document).scrollTop(0);
	});
});