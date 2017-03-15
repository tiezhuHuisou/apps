$(function(){
	// 返回
	$('.header_left').on('touchend',function(){
		$.confirm({
			title: '你还没有提交申请确定退出？',
			onOK: function () {
				window.history.go(-1);
			},
			onCancel: function () {
			}
		});
	});
	// 图片上传
	var inputs =$(".upload_input");
	for(var i=0;i<inputs.length;i++){
		inputs[i].addEventListener('change',function(){
			$(this).siblings('.upload_img').attr('src', window.URL.createObjectURL(this.files[0])).show().css('background-color','#fff');
			$(this).parent('.weui-uploader__input-box').css('border','0');
		},false);
	}
	// 提交
	$('.submit_btn').click(function(){
		$.toast("操作成功");
	});
})