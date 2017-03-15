$(function () {
    ajaxFlag = true;
    var page = 1;
// var num  = page;
    $(window).scroll(function () {
        if ($('.classify_nav').is(':hidden')) {

            var scrollTop = $(this).scrollTop();                                // 滚动条距离顶部的高度
            var scrollHeight = $(document).height();                            // 当前页面的总高度
            var windowHeight = $(this).height();                                // 当前可视的页面高度
            var expectHeight = 0;                                                // 预加载距离
            if (scrollTop + windowHeight >= scrollHeight - expectHeight) {    // 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
                var data = {page: page, title: title};
                if (ajaxFlag) {
                    ajaxFlag = false;
                    $('.loading').show();
                    $.get('?g=app&m=product&a=ajaxAnnounce', data, function (data) {
                        $('.loading').hide();
                        if (!data.length) {
                            ajaxFlag = false;
                            return false;
                        } else {
                            ajaxFlag = true;
                            page += 1;
                        }
                        if (data.length < 1) {
                            ajaxFlag = false;
                            $('.loading').show().html('没有了');
                        }

                        var html = '';
                        for (var i = 0; i < data.length; i++) {
                            html += '<a href="?g=app&m=product&a=announceDetail&id=' + data[i].id + '">';
                            html += '<div class="weui-media-box weui-media-box_text">';
                            html += '<span class="doit green_38d163">•</span>';
                            html += '<div class="content_desc">';
                            html += '<p class="weui-media-box__desc read_aleady">' + data[i].title + '</p>';
                            html += '<p class="weui-media-box__desc read_aleady">' + data[i].addtime + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</a>';


                        }
                        $(".weui-panel__bd").append(html);
                    }, 'json');
                }
            }
        }
    });
});