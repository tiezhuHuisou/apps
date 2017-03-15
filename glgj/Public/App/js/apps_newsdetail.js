$(function() {
    if ( appsign == '1' ) {
        /* 遍历图文详情中的图片 */
        $('article img').each(function(i,n){
            if ( $(n).parent().get(0).tagName == 'A' ) {            // 图片一级父元素为a
                $(n).parent().attr( 'href', 'http://huisou.m.huisou.com/?flag=clickimg&index=' + i );
            } else {                                                // 图片一级父元素不为a
                var html = '';
                html += '<a href="http://huisou.m.huisou.com/?flag=clickimg&index=' + i + '">';
                html += $(n).prop('outerHTML');
                html += '</a>';
                $(n).prop('outerHTML', html);
            }
        });
    }

    var ajaxFlag = true;
    var page     = 10;
    var num      = page;
    $(window).scroll(function(){
        var scrollTop    = $(this).scrollTop();                             // 滚动条距离顶部的高度
        var scrollHeight = $(document).height();                            // 当前页面的总高度
        var windowHeight = $(this).height();                                // 当前可视的页面高度
        var expectHeight = 0;                                               // 预加载距离
        if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {    // 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
            if ( ajaxFlag ) {
                $('.loading').show();
                $.get(window.location.url,{ num : num }, function(data) {
                    console.log(data);
                    $('.loading').hide();
                    ajaxFlag = false;
                    if ( !data.length ) {
                        ajaxFlag = false;
                        $('.loading').html('没有了').show();
                        return false;
                    } else {
                        ajaxFlag = true;
                        num += data.length;
                    }
                    if ( data.length < page ) {
                        ajaxFlag = false;
                        $('.loading').html('没有了').show();
                    }
                    var html  = '';
                    for ( var i = 0; i < data.length; i ++ ) {
                        html += '<div class="comment_list">';
                        html += '<div class="list_one">';
                        html += '<img src="' + data[i].head_pic + '" width="44" height="44" />';
                        html += '<div class="one_detail">';
                        html += '<p class="detail_top">';
                        html += '<span class="top_name">' + data[i].username + '</span>';
                        if ( data[i].company_id ) {
                            html += '<span class="top_line">|</span>';
                            html += '<a class="top_company" href="javascript:void(0);">' + data[i].company_name + '</a>';
                        }
                        html += '</p>';
                        html += '<p class="detail_word">' + data[i].content + '</p>';
                        html += '<div class="detail_more">';
                        html += '<p class="more_time">' + data[i].ctime + '</p>';
                        html += '<p class="more_comment">';
                        html += '<span>' + data[i].reply_count + '</span>';
                        html += '</p>';
                        html += '<p class="more_like">';
                        html += '<span>' + data[i].praise_count + '</span>';
                        html += '</p>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        if ( data[i].reply ) {
                            var replyData = data[i].reply;
                            var replyTemp = '';
                            for ( var j = 0; j < replyData.length; j ++ ){
                                replyTemp += '<div class="list_two">';
                                replyTemp += '<div class="two_backs">';
                                replyTemp += '<img src="' + replyData[j].head_pic + '" width="30" height="30" />';
                                replyTemp += '<div class="backs_detail">';
                                replyTemp += '<p class="detail_top">';
                                replyTemp += '<span class="top_name">' + replyData[j].username + '</span>';
                                if ( replyData[j].company_id ) {
                                    replyTemp += '<span class="top_line">|</span>';
                                }
                                if ( !replyData[j].company_name ) {
                                    replyTemp += '<a class="top_company" href="javascript:void(0);"></a>';
                                } else {
                                    replyTemp += '<a class="top_company" href="javascript:void(0);">' + replyData[j].company_name + '</a>';
                                }
                                replyTemp += '<span class="top_time">' + replyData[j].ctime + '</span>';
                                replyTemp += '</p>';
                                replyTemp += '<p class="detail_word">' + replyData[j].content + '</p>';
                                replyTemp += '</div>';
                                replyTemp += '</div>';
                                replyTemp += '</div>';
                            }
                            html += replyTemp;
                        }
                        html += '</div>';
                    }
                    $('.comment_wrap').append(html);
                },'json');
            }
        }
    });
});