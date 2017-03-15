$(function() {
    // 设置保存按钮宽度
    $('.submit').width( $('.contact').width() );

    // 防止横竖屏切换
    $(window).resize(function() {
        $('.submit').width( $('.contact').width() );
    });

    // 提交表单前验证
    $('.submit').click(function() {
        $.post(window.location.href, $('form').serialize(), function(msg){
            layer.open({
                content : msg.info,
                time : 1,
            });
        });
        return false;
    });
});