$(function () {
    ajaxFlag = true;
    var page = 1;
    // var num  = page;
    $(window).scroll(function () {
        if ($('.classify_nav').is(':hidden')) {

            var scrollTop = $(this).scrollTop();								// 滚动条距离顶部的高度
            var scrollHeight = $(document).height();							// 当前页面的总高度
            var windowHeight = $(this).height();								// 当前可视的页面高度
            var expectHeight = 0;												// 预加载距离
            if (scrollTop + windowHeight >= scrollHeight - expectHeight) {	// 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
                var data = {page: page, cid: cid, title: title};

                if (ajaxFlag) {
                    ajaxFlag = false;
                    $('.loading').show();

                    $.get('?g=app&m=company&a=ajaxlist', data, function (data) {

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
                            html += '<a href="?g=app&m=company&a=detail&id=' + data[i].id + '">';
                            html += '<div class="weui-panel weui-panel_access">';
                            html += '<h2 class="weui-panel__hd">' + data[i].name + '</h2>';
                            html += '<div class="weui-panel__bd">';
                            html += '<div class="weui-media-box weui-media-box_appmsg">';
                            html += '<div class="weui-media-box__bd">';
                            html += '<p class="weui-media-box__desc">联系人：<span>' + data[i].contact_user + '</span></p>';
                            html += '<p class="weui-media-box__desc">';
                            html += '<span class="phone">电话：' + data[i].telephone + '<a href="tel:' + data[i].telephone + '"><img';
                            html += 'src="__IMG__/dh.png"></a></span>';
                            html += '<span class="phone">手机：' + data[i].subphone + '<a href="tel:' + data[i].subphone + '"><img';
                            html += 'src="__IMG__/dh.png"></a></span>';
                            html += '</p>';
                            html += '<p class="weui-media-box__desc">' + data[i].address + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                            html += '</a>';
                        }
                        $(".list_wrap").append(html);
                    }, 'json');
                }
            }
        }
    });
});