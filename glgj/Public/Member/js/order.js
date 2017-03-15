$(function() {
	$(".del_order").click(function() {	// 删除订单
		var _this 	= $(this);
		var orderid = _this.siblings(".order_id").val();
		var data 	= {orderid:orderid};

		layer.open({
			content : '确认删除订单？',
			btn 	: ['确定', '取消'],
			yes 	: function(index) {
				layer.close(index);
				$.post('?g=app&m=order&a=delorder',data,function(data) {
					if(data.status==1){
						_this.parents(".all_main").remove();
						layer.open({
							content : '删除成功',
							time 	: 1,
							end 	: function() {
								window.location.reload();
							}
						});
					}else{
						layer.open({
							content :data.info,
							time 	: 1
						});
					}
				},'json');
			},
			no 		: function(index) {
				layer.close(index);
			},
		});		
	});
	
	$(".order_refund").click(function(){	// 申请退款
		var _this=$(this);
		var orderid=_this.siblings(".order_id").val();
		var data={orderid:orderid};

		layer.open({
			content : '确认申请退款？',
			btn 	: ['确定', '取消'],
			yes 	: function(index) {
				layer.close(index);
				$.post('?g=app&m=order&a=refund',data,function(data){
					if(data.status==1){
						_this.parents(".all_main").remove();
						layer.open({
							content : '申请成功',
							time 	: 1,
							end 	: function() {
								window.location.reload();
							}
						});
					}else{
						layer.open({
							content :data.info,
							time 	: 1
						});
					}
				},'json');
			},
			no 		: function(index) {
				layer.close(index);
			},
		});
	});

	$(".order_surerefund").click(function() {	// 完成退款
		var _this=$(this);
		var orderid=_this.siblings(".order_id").val();
		var data={orderid:orderid};

		layer.open({
			content : '确认完成退款？',
			btn 	: ['确定', '取消'],
			yes 	: function(index) {
				layer.close(index);
				$.post('?g=app&m=order&a=surerefund',data,function(data){
					if(data.status==1){
						_this.parents(".all_main").remove();
						layer.open({
							content : '操作成功',
							time 	: 1,
							end 	: function() {
								window.location.reload();
							}
						});
					}else{
						layer.open({
							content :data.info,
							time 	: 1
						});
					}
				},'json');
			},
			no 		: function(index) {
				layer.close(index);
			},
		});
	});

	$(".order_cancelrefund").click(function() {		// 取消退款
		var _this=$(this);
		var orderid=_this.siblings(".order_id").val();
		var data={orderid:orderid};

		layer.open({
			content : '确认取消退款？',
			btn 	: ['确定', '取消'],
			yes 	: function(index) {
				layer.close(index);
				$.post('?g=app&m=order&a=cancelrefund',data,function(data){
					if(data.status==1){
						_this.parents(".all_main").remove();
						layer.open({
							content : '取消成功',
							time 	: 1,
							end 	: function() {
								window.location.reload();
							}
						});
					}else{
						layer.open({
							content :data.info,
							time 	: 1
						});
					}
				},'json');
			},
			no 		: function(index) {
				layer.close(index);
			},
		});
	});

	$(".sure_goods").click(function() {		// 确认收货
		var _this=$(this);
		var orderid=_this.siblings(".order_id").val();
		var data={orderid:orderid};

		layer.open({
			content : '确认收货？',
			btn 	: ['确定', '取消'],
			yes 	: function(index) {
				layer.close(index);
				$.post('?g=app&m=order&a=delivery',data,function(data){
					if(data.status==1){
						_this.parents(".all_main").remove();
						layer.open({
							content : '操作成功',
							time 	: 1,
							end 	: function() {
								window.location.reload();
							}
						});
					}else{
						layer.open({
							content :data.info,
							time 	: 1
						});
					}
				},'json');
			},
			no 		: function(index) {
				layer.close(index);
			},
		});
	});
})