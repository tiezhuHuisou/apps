$(function(){
	$("#start_city").cityPicker({
		title: "请选择始发地",
		showDistrict: false
	});
	$("#end_city").cityPicker({
		title: "请选择目的地",
		showDistrict: false
	});
	// 发货日期
	$('#start_time,#end_time').datetimePicker({
		title: '请选择发货日期',
		yearSplit: '年',
        monthSplit: '月',
        dateSplit: '日',
		times: function () {
			return '';
		},
		onChange: function (picker, values, displayValues) {
		}
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