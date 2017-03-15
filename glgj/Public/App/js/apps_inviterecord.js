$(function(){


    ajaxFlag = true;
    var page = 10;
    var num  = page;
    $(window).scroll(function() {
        if ( $('.classify_nav').is(':hidden') ) {
            var scrollTop    = $(this).scrollTop();                             // 滚动条距离顶部的高度
        　　var scrollHeight = $(document).height();                            // 当前页面的总高度
        　　var windowHeight = $(this).height();                                // 当前可视的页面高度
            var expectHeight = 0;                                               // 预加载距离
            if ( scrollTop + windowHeight >= scrollHeight - expectHeight ) {    // 距离顶部+当前高度 >=文档总高度 即代表滑动到底部
                var title = '';
				if ( $("#search").val() ) {
					title = $("#search").val();
				}
				var data = {num:num,categoryid:categoryid,title:title};
                if ( ajaxFlag ) {
                    ajaxFlag = false;
                    $('.loading').show();
                    $.get('?g=app&m=apps&a=ajaxlist',data,function(data) {
                        $('.loading').hide();
                        if ( !data.length ) {
                            ajaxFlag = false;
                            return false;
                        } else {
                            ajaxFlag = true;
                            num += data.length;
                        }
                        if ( data.length < page ) {
                            ajaxFlag = false;
                            $('.loading').show().html('没有了');
                        }
                        var _html='';
                        for (var i = 0; i < data.length; i++) {
                            _html +='<li>';
                            _html +='<a href="javascript:void(0);">';
                            _html +='<img src="'+data[i].img+'" width="80" height="80" />';
                            _html +='<div class="items_wrap">';
                            _html +='<p class="items_title">用户名:'+data[i].name+'</p>';
                            _html +='</div>';
                            _html +='</a>';
                            _html +='</li>'
                        }
                        $('.code_items').append( _html );
                    },'json');
                }
            }
        }
    });
});

