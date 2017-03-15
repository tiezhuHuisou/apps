$(function() {
    /* 遍历图文详情中的图片 */
    $('article img').each(function(i,n){
        if ( $(n).parent().get(0).tagName == 'A' ) {            // 图片一级父元素为a
            $(n).parent().attr( 'href', 'http://huisou.m.huisou.com/?flag=clickimg&index=' + i );
        } else {                                                // 图片一级父元素不为a
            var html = '';
            html += '<a href="http://huisou.m.huisou.com/?flag=clickimg&index=' + i + '">'
            html += $(n).prop('outerHTML');
            html += '</a>';
            $(n).prop('outerHTML', html);
        }
    });
});