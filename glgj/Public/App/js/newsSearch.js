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