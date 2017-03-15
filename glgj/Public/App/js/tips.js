$(function(){
    /* 开启更多操作提示信息 */
    $('.tips_show').click(function(){
        layer.open({
            content     : '更多操作，请先下载客户端APP',
            btn         : ['下载', '取消'],
            shadeClose  : false,
            yes         : function(){
                window.open(downloadUrl);
            },
            no          : function(){
                layer.closeAll();
            }
        });
    });
    /* 关闭更多操作提示信息 */
    $('.tips_close').click(function(){
        $('.tips').hide();
    });
});