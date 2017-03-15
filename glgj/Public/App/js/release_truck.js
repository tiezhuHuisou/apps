$(function(){
	$("#start_city").cityPicker({
		title: "请选择始发地",
		showDistrict: false
	});
	$("#end_city").cityPicker({
		title: "请选择目的地",
		showDistrict: false
	});
	$('#way_city').cityPicker({
		title: "请途经地区",
	});
	// 发货日期
	$('#start_time').datetimePicker({
		title: '请选择发货日期',
		yearSplit: '年',
        monthSplit: '月',
        dateSplit: '日',
		onChange: function (picker, values, displayValues) {
		}
	});
	$('#end_time').datetimePicker({
		title: '请选择发货日期',
		yearSplit: '年',
        monthSplit: '月',
        dateSplit: '日',
		onChange: function (picker, values, displayValues) {
		}
	});
})