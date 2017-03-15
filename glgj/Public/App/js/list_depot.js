$(function () {
    $('.editor_btn').on('touchend', function () {
        window.location.href = "?g=app&m=member&a=editor_depot";
    });
    // 加载
    var loading = true;  //状态标记
    var page = 1;// 每次
    var num = 0;

    $(document.body).infinite().on("infinite", function () {
        if (!loading) {
            $.toast("没有了亲！", "text");
            return;
        };	//  不能加载
        $('.weui-loadmore').show();// 加载提示器

        $.get('?g=app&m=member&a=depotAjax', {page: page}, function (data) {
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
                    html+='<div class="mgt10 weui-cells weui-cells_checkbox">';
                    html+='<label class="weui-cell">';
                    html+='<div class="weui-cell__hd">';
                    html+='<input type="checkbox" class="js_check_single weui-check" name="checkbox1">';
                    html+='<i class="weui-icon-checked"></i>';
                    html+='</div>';
                    html+='<div class="weui-cell__bd">';
                    html+='<div class="weui-form-preview__hd">';
                    html+='<em class="weui-form-preview__value">' + data[i].location + '</em>';
                    html+='</div>';
                    html+='<div class="weui-form-preview__bd">';
                    html+='<div class="weui-form-preview__item">';
                    html+='<label class="weui-form-preview__label">详细地址</label>';
                    html+='<span class="weui-form-preview__value">' + data[i].address + '</span>';
                    html+='</div>';
                    html+='<div class="weui-form-preview__item">';
                    html+='<label class="weui-form-preview__label">面积</label>';
                    html+='<span class="weui-form-preview__value">' + data[i].area + '平方米</span>';
                    html+='</div>';
                    html+='<div class="weui-form-preview__item">';
                    html+='<label class="weui-form-preview__label">建筑标准</label>';
                    html+='<span class="weui-form-preview__value">' + data[i].standard + '</span>';
                    html+='</div>';
                    html+='</div>';
                    html+='</div>';
                    html+='</label>';
                    html+='</div>';
            }
            $(".list_wrap").append(html);
        });
    });
})