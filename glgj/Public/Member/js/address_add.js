$(function(){
	$(".province").change(function(){
		var id=$(this).val();
		if(id){
			var city_arr=data[id];
			var str='<option value="">请选择所在市</option>';
			for(var i=0;i<city_arr.length;i++){
				str +='<option value="'+city_arr[i]['id']+'">'+city_arr[i]['name']+'</option>';
			}
			$(".city").html(str);
		}else{
			$(".city").html('<option value="">请选择所在市</option>');
			$(".towns").html('<option value="">请选择所在区/县</option>');
		}
		
	});
	$(".city").change(function(){
		var id=$(this).val();
		if(id){
			var town_arr=data[id];
			
			var str_town='<option value="">请选择所在区/县</option>';
			for(var i=0;i<town_arr.length;i++){

				str_town +='<option value="'+town_arr[i]['id']+'">'+town_arr[i]['name']+'</option>';
			}
			$(".towns").html(str_town);
		}else{
			
			$(".towns").html('<option value="">请选择所在区/县</option>');
		}
		
	});

	$(".form_btn").click(function(){											 	//点击完成按钮，对所有数据验证不能为空
		var _this = $(this);
		_this.attr("disabled",true);
		$.post('?g=member&m=buy&a=increaseaddr',$("form").serialize(),function(data){
			if(data.status==1){
				layer.msg(data.info,{icon:1,time:1000},function(){
					window.location.href=data.url;
				});
			}else{
				layer.msg(data.info,{icon:2,time:1000},function(){
					_this.attr("disabled",false);
				});
                return false;
			}
		},'json');
		return false;
	});
})