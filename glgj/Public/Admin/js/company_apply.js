$(function(){
    /* 关闭弹窗 */
    $('.btn_close, .btn_cancel').click(function(){
        closeWindows();
    });

    /* 拒绝确认 */
    $('body').on('click', '.refuse', function(){
        $('.mask').show();
        $('.modal_wrap').show();
        $('#refuse_id').val( $(this).data('id') );
    });
    /* 确认 */
    var ajaxFlag = true;
    $('.btn_sure').click(function(){
        if ( ajaxFlag == true ) {
            ajaxFlag = false;
        } else {
            return false;
        }
        var id     = $('#refuse_id').val();
        var reason = $('#reason').val();
        if ( reason.length > 200 ) {
            layer.alert('拒绝理由不能大于200字');
            ajaxFlag = true;
            return false;
        }
        $.post('?g=admin&m=member&a=refuse', { id : id, reason : reason }, function(data) {
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