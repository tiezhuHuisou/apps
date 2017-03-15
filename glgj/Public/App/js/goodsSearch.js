$("#start_address").cityPicker({//地址选择插件
	title: "起始地"
});
$("#end_address").cityPicker({//地址选择插件
	title: "目的地"
});
$('.start, .end').click(function(){ //点击隐藏文本显示地区选择器
	$(this).children('span').hide();
	$(this).children('input').removeClass('op0');
});
$('.type_choose span').click(function(){
	$(this).siblings('.active').removeClass('active');
	$(this).addClass('active');
})
$('.header_title').click(function(){//点击标题切换列表
	$('.search_list').toggle();
});
// 点击查询进行交互
$('.btn_group .weui-btn_primary').click(function(){
	var _form = $('form');
	console.log(_form.serialize())
	$.ajax('',_form.serialize(),function(data){
		console.log(data);
	});
});