$(function() {
    /* 订单详情tab切换 */
	$('.order_info_btn li').click(function(){
		var index = $(this).index();
		$(this).addClass('hover').siblings('li').removeClass('hover');
		$('.order_info_wrapper').eq(index).show().siblings('.order_info_wrapper').hide();
	});

    /* 同意退款 */
    $('.refund_agree').click(function() {
        layer.confirm('确定要同意退款吗？', function(index) {
            $.post('?g=admin&m=order&a=refundAgree', { id: $('#refund_id').val() }, function(data) {
                var icon = data.status == 1 ? 1 : 2;
                layer.msg(data.info, {time: 2000, icon: icon}, function() {
                    if ( data.url ) {
                        window.location.href = data.url;
                    } else {
                        window.location.href = window.location.href + '&show=refund';
                    }
                });
            });
            layer.close(index);
        });
        
    });

    /* 拒绝退款 */
    $('.refund_refuse').click(function(){
        var _this = $(this);
        layer.open({
            title   : false,
            type    : 1,
            area    : '400px',
            content : '<div style="padding:10px;"><p style="margin-bottom:8px;">请输入拒绝理由：</p><textarea id="remark" style="width:100%;height:80px;border:1px solid #dcdcdc;resize:none;padding:0;margin:0;"></textarea></div>',
            btn     : ['确定', '取消'],
            yes     : function(index){
                if ( !$('#remark').val() ) {
                    layer.msg('请输入拒绝理由');
                    return false;
                } else {
                    $.post('?g=admin&m=order&a=refundRefuse',{
                        id          : $('#refund_id').val(),
                        uid         : $('#refund_uid').val(),
                        remark      : $('#remark').val(),
                        payment_id  : $('#refund_payment_id').val()
                     },function(data){
                        var icon = data.status == 1 ? 1 : 2;
                        layer.msg(data.info,{ time : 2000, icon: icon },function(){
                            if ( data.url ) {
                                window.location.href = data.url;
                            } else {
                                window.location.href = window.location.href + '&show=refund';
                            }
                        });
                    },'json');
                    return false;
                }
            }
        });
    });

    /* 拒绝退款 */
    // $('.refund_refuse').click(function() {
    //     var refuseRefund = $('#line_text').val();
    //     layer.confirm('确定要拒绝退款吗？', function(index) {
    //         $.post('?g=admin&m=order&a=refundAgree', {type:2, id: $('#refund_id').val(), 'uid': $('#refund_uid').val(),'refuseRefund':refuseRefund}, function(data) {
    //             var icon = data.status == 1 ? 1 : 2;
    //             layer.msg(data.info, {time: 10000, icon: icon}, function() {
    //                 if ( data.url ) {
    //                     window.location.href = data.url;
    //                 } else {
    //                     window.location.href = window.location.href + '&show=refund';
    //                 }
    //             });
    //         });
    //         layer.close(index);
    //     });
    // });

    /* 打款完成 */
    $('.refund_confirm').click(function(){
        layer.open({
            title   : '打款完成',
            type    : 1,
            area    : '300px',
            content : '<div style="padding:10px;"><p style="margin-bottom:8px;">请输入退款金额：</p><div style="padding:4px;border:1px solid #dcdcdc;"><input id="refusemoney" type="text" value="' + $('#refund_money').val() + '" style="width:100%;border:none;" /></div></div>',
            btn     : ['确定', '取消'],
            yes     : function(index){
                if ( !$('#refusemoney').val() ) {
                    layer.msg('请输入退款金额');
                    return false;
                } else {
                    $.post('?g=admin&m=order&a=refundComplete',{
                        id          : $('#refund_id').val(),
                        money       : $('#refusemoney').val()
                     },function(data){
                        var icon = data.status == 1 ? 1 : 2;
                        layer.msg(data.info, {time: 2000, icon: icon}, function() {
                            if ( data.url ) {
                                window.location.href = data.url;
                            } else {
                                if ( data.status == 1 ) {
                                    window.location.href = window.location.href + '&show=refund';
                                } 
                            }
                        });
                    },'json');
                    return false;
                }
            }
        });
    });
});
