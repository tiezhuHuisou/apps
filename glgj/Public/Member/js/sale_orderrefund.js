$(function() {
    /* 同意退款 */
    $('.refund_agree').click(function() {
        layer.confirm('确定要同意退款吗？', { title : '同意退款' }, function(index) {
            $.post('?g=member&m=sale&a=refundAgree', { id: $('#refund_id').val() }, function(data) {
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
        /* 自定义弹出层html */
        var content = '';
        content += '<div style="padding:10px;">';
        content += '<p style="margin-bottom:8px;">请输入拒绝理由：</p>';
        content += '<div style="padding:4px;border:1px solid #dcdcdc;">';
        content += '<textarea id="remark" style="width:100%;height:80px;resize:none;border:none;padding:0;margin:0;"></textarea>';
        content += '</div>';
        content += '</div>';
        /* 弹框 */
        layer.open({
            title   : '拒绝退款',
            type    : 1,
            area    : '400px',
            content : content,
            btn     : ['确定', '取消'],
            yes     : function(index){
                if ( !$('#remark').val() ) {
                    layer.msg('请输入拒绝理由');
                    return false;
                } else {
                    $.post('?g=member&m=sale&a=refundRefuse',{
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

    /* 打款完成 */
    $('.refund_confirm').click(function(){
        /* 自定义弹出层html */
        var content = '';
        content += '<div style="padding:10px;">';
        content += '<p style="margin-bottom:8px;">请输入退款金额：</p>';
        content += '<div style="padding:4px;border:1px solid #dcdcdc;">';
        content += '<input id="refusemoney" type="text" value="' + $('#refund_money').val() + '" style="width:100%;border:none;" />';
        content += '</div>';
        content += '</div>';
        /* 弹框 */
        layer.open({
            title   : '打款完成',
            type    : 1,
            area    : '300px',
            content : content,
            btn     : ['确定', '取消'],
            yes     : function(index){
                if ( !$('#refusemoney').val() ) {
                    layer.msg('请输入退款金额');
                    return false;
                } else {
                    $.post('?g=member&m=sale&a=refundComplete',{
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