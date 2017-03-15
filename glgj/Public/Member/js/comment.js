$(function() {
    $('.comment').click(function() {        // 点击回复
        var _this = $(this);
        layer.open({
            type        : 1,
            content     : $('#reply'),
            title       : '管理员回复',
            area        : [ '500px', '300px' ],
            scrollbar   : false,
            closeBtn    : 2,
            success     : function() {
                $('.comment_id').val( _this.parents('tr').children('td').eq(0).children('span').text() );
            }
        }); 
    });

    $('.reply_cancel').click(function() {   // 回复弹窗取消按钮
        layer.closeAll();
    });

});