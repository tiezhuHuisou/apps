$(function() {
    var uuid = $('#uuid').val();
    if ( uuid ) {
        uuid = '&appsign=1&uuid=' + uuid;
    }
    /* 删除 */
    $('.del').click(function() {
        var _this = $(this);
        var id    = _this.data('id');
        /* 弹出提示框 */
        layer.open({
            // title: '提示',
            content: '您确定要删除该条需求信息吗？',
            btn: ['确定', '取消'],
            yes: function(index){
                /* 请求后台 */
                $.post('?g=app&m=member&a=delneed'+uuid, {id: id}, function( data ) {
                    layer.open({
                        content : data.info,
                        time    : 1,
                        end     : function() {
                            if ( data.status == 1 ) {
                                _this.parents('.list').remove();
                            }
                        }
                    });
                });
                layer.close(index);
            }
        });
    });
});