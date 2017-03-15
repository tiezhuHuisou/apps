$(function(){
	$(".header_right").click(function(){
		var textarea_val = $(".weui-textarea").val();
		var input_val = $(".weui-input").val();
		
		$.toast.prototype.defaults.duration = 1000;
		if (textarea_val == "" || input_val == "") {
			$.toast("您还有信息未填哦", "text");
		}else{
			$.toast("发送成功", "text", function(){
				window.history.back(-1);
			});
		}
	})
})