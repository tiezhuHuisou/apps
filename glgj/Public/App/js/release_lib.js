$(function(){
	$("#lib_address").cityPicker({
		title: "请选择始发地",
		showDistrict: false
	});
	// 图片上传
	var input = document.getElementById("uploaderInput");
	input.addEventListener('change',readFile,false);

	// 图片显示
	function readFile(){ 
		$('.upload_img').attr('src', window.URL.createObjectURL(this.files[0])).show().css('background-color','#fff');
		$('.weui-uploader__input-box').css('border','0');
	};
})