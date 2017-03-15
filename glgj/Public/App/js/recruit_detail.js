$(function() {
	// 投递简历弹窗定位
	/*$('.delivery_window').css('margin-top', $('.delivery_window').height() * -0.5);*/
	// 投递简历
	// $('.delivery').click(function() {
	// 	$('.mask,.delivery_window').show();
	// });

	$('.delivered').click(function() {
		layer.open({
			content : '已投递',
			time	: 1
		});
	});

	// 点击蒙板关闭投递简历弹窗
/*	$('.mask').click(function() {
		$('.mask,.delivery_window').hide();
	});*/


	//提交弹窗
	$('.delivery').click(function(){
		var uuid = $('#uuid').val() ? '&uuid=' + $('#uuid').val() : '';
		var url  = 'http://ltjy.m.huisou.com/index.php?g=app&m=recruit&a=delivery' + uuid;
		var data = {id:$(this).attr('data-value')};
		$.get(url,data,function(msg){
			layer.open({
				content : msg.info,
				time	: 1,
				end 	: function() {
					window.location.reload();
				}
			});
		});
	});
	// $('.delivery_submit').click(function() {
	// 	var mobileReg = /^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/;     // 联系电话-手机正则
	// 	var url = 'http://qgjx.m.huisou.com/index.php?g=app&m=recruit&a=delivery';
	// 	var data = $("form[name='delivery']").serialize();
	// 	$.post(url,data,function(msg){
	// 		layer.open({
	// 			content : msg.info,
	// 			time	: 1,
	// 			end:function(){
	// 				$('.mask,.delivery_window').hide();
	// 				window.history.go(0);
	// 			}
	// 		});
	// 	});
	// 	return false;
	// });
});