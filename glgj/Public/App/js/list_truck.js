$(function(){
	$('.editor_btn').on('touchend',function(){
		window.location.href="?g=app&m=member&a=editor_truck";
	});
	// 加载
	var loading = true;  //状态标记
	var page =1;// 每次
	var num =0;

	$(document.body).infinite().on("infinite", function() {
		if(!loading) {
			$.toast("没有了亲！", "text");
			return;
		};	//  不能加载
		$('.weui-loadmore').show();// 加载提示器	
			
		$.get('?g=app&m=member&a=truckAjax', {page:page }, function(data) {
			if(!data.length){   			// 无数据
				loading = false;
				return false;
			}else{				// 有数据
				loading = true;
				page++;
			}
			if( data.length < 10){  		// 数据返回完时
				if(num){
					loading = false;
					$('.weui-loadmore').hide();
					$.toast("没有了亲！", "text");
					return false;
				}
				num++;
			}
			var html='';
			for(var i=0;i<data.length;i++){
				html+='<a href="?g=app&m=member&a=publishTruck&id='+data[i].id+'">';
				html+='<div class="mgt10 weui-form-preview">';
				html+='<div class="weui-form-preview__hd">';
				html+='<em class="weui-form-preview__value">'+data[i].truck_type+'</em>';
				html+='</div>';
				html+='<div class="weui-form-preview__bd">';
				html+='<div class="weui-form-preview__item">';
				html+='<label class="weui-form-preview__label">路线</label>';
				html+='<span class="weui-form-preview__value">'+data[i].start+'->'+data[i].end+'</span>';
				html+='</div>';
				html+='<div class="weui-form-preview__item">';
				html+='<label class="weui-form-preview__label">货物重量</label>';
				html+='<span class="weui-form-preview__value">'+data[i].heavy+' '+data[i].heavy_unit+'</span>';
				html+='</div>';
				html+='<div class="weui-form-preview__item">';
				html+='<label class="weui-form-preview__label">货物体积</label>';
				html+='<span class="weui-form-preview__value">'+data[i].light+' '+data[i].light_unit+'</span>';
				html+='</div>';
				html+='</div>';
				html+='</div>';
				html+='</a>';
				
			}
			$(".list_wrap").append(html);
		});
	});
})