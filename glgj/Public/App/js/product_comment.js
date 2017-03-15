$(function() {
    var ajaxFlag = true;
    $('#sub').click(function() {
        if ( ajaxFlag === true ) {
            ajaxFlag = false;
        } else {
            return false;
        }
        content = $('#content').val();
        id      = $('#id').val();
        if ( $.trim(content) == '' ) {
            layer.open({
                content : '请输入评价内容',
                time    : 1
            });
            return false;
        }
        $.post(window.location.href, {id: id, content: content}, function(data) {
            layer.open({
                content : data.info,
                time    : 1,
                end     : function() {
                    if ( data.status == 1 ) {
                        window.location.reload();
                    } else {
                        ajaxFlag = true;
                    }
                }
            });
        });
        return false;               
    });
});