$(function(){
    /* 拒绝确认 */
    $('body').on('click', '.refuse', function(){
        $('.mask').show();
        $('.modal_wrap').show();
    });
    /* 确认 */
    var refuseFlag = true;
    $('.btn_sure').click(function(){
        if ( refuseFlag == true ) {
            refuseFlag = false;
        } else {
            return false;
        }
        var reason = $('#reason').val();
        if ( reason.length > 200 ) {
            layer.alert('拒绝理由不能大于200字');
            refuseFlag = true;
            return false;
        }
        $.post('?g=admin&m=member&a=refuse', { id : $('#companyid').val(), reason : reason }, function(data) {
            closeWindows();
            layer.open({
                content : data.info,
                time    : 2000,
                end     : function(){
                    if ( data.url ) {
                        window.location.href = data.url;
                    } else {
                        window.location.reload();
                    }
                }
            });
        });
        return false;
    });
    /* 关闭弹窗 */
    $('.btn_cancel,.btn_close').click(function(){
        closeWindows();
    });

    /* 通过 */
    $('.pass').click(function(){
        layer.confirm();
    });
});

/**
 * 关闭弹窗
 */
function closeWindows() {
    $('.mask').hide();
    $('.modal_wrap').hide();
    $('#refuse_id').val('');
    $('#reason').val('');
}