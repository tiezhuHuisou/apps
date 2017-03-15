$(function () {
    $('.editor_btn').on('touchend', function () {
        window.location.href = "?g=app&m=member&a=editor_goods";
    });
    // 加载
    var loading = true;  //状态标记
    var page = 1;// 每次
    var num = 0;
    $(document.body).infinite().on("infinite", function () {
        if (!loading) {
            $.toast("没有了亲！", "text");
            return;
        }
        ;	//  不能加载
        $('.weui-loadmore').show();// 加载提示器

        $.get('?g=app&m=member&a=goodsAjax', {page: page}, function (data) {
            if (!data.length) {   			// 无数据
                loading = false;
                return false;
            } else {				// 有数据
                loading = true;
                page++;
            }
            if (data.length < 10) {  		// 数据返回完时
                if (num) {
                    loading = false;
                    $('.weui-loadmore').hide();
                    $.toast("没有了亲！", "text");
                    return false;
                }
                num++;
            }
            var html = '';
            for (var i = 0; i < data.length; i++) {
                html += '<a href="?g=app&m=member&a=publishGoods&id=' + data[i].id + '">';
                html += '<div class="mgt10 weui-form-preview">';
                html += '<div class="weui-form-preview__hd">';
                html += '<em class="weui-form-preview__value">' + data[i].location + '</em>';
                html += '</div>';
                html += '<div class="weui-form-preview__bd">';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">货物名称：</label>';
                html += '<span class="weui-form-preview__value">' + data[i].name + '</span>';
                html += '</div>';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">货物信息：</label>';
                html += '<span class="weui-form-preview__value">' + data[i].category_id + '</span>';
                html += '</div>';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">运输方式:</label>';
                html += '<span class="weui-form-preview__value">' + data[i].transport_id + '</span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</a>';
            }
            $(".list_wrap").append(html);
        });
    });
})