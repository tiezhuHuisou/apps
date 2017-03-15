$(function () {
    $('.save_btn').on('touchend', function () {
        window.location.href = "?g=app&m=member&a=truck";
    });
    checkAll('.js_check_all', '.js_check_single');
    // 加载
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
        $.get('?g=app&m=member&a=truckAjax', {page: page}, function (data) {
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
                html += '<div class="mgt10 weui-cells weui-cells_checkbox">';
                html += '<div class="weui-cell">';
                html += '<label class="weui-cell__hd">';
                html += '<input type="checkbox" class="js_check_single weui-check" name="checkbox1" data-id="' + data[i].id + '">';
                html += '<i class="weui-icon-checked"></i>';
                html += '</label>';
                // html += '<a href="?g=app&m=member&a=publishTruck&id=' + data[i].id + '">';
                html += '<div class="weui-cell__bd">';
                html += '<div class="weui-form-preview__hd">';
                html += '<em class="weui-form-preview__value">' + data[i].truck_type + '</em>';
                html += '</div>';
                html += '<div class="weui-form-preview__bd">';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">路线</label>';
                html += '<span class="weui-form-preview__value">' + data[i].start + '->' + data[i].end + '</span>';
                html += '</div>';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">货物重量</label>';
                html += '<span class="weui-form-preview__value">' + data[i].heavy + ' ' + data[i].heavy_unit + '</span>';
                html += '</div>';
                html += '<div class="weui-form-preview__item">';
                html += '<label class="weui-form-preview__label">货物体积</label>';
                html += '<span class="weui-form-preview__value">' + data[i].light + ' ' + data[i].light_unit + '</span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                // html += '</a>';
                html += '</div>';
                html += '</div>';
            }

            $(".list_wrap").append(html);
        });
    });

    // 删除
    $('.del_btn').click(function () {
        $.confirm({
            title: '确认删除？',
            onOK: function () {
                dellist();
            },
            onCancel: function () {
            }
        });
    });
})

function dellist() {
    var ids = '';
    for (var i = 0; i < $('.js_check_single').length; i++) {
        if ($('.js_check_single').eq(i).prop('checked')) { // 选中
            ids += $('.js_check_single').eq(i).data('id') + ','
        }
    }
    ids = ids.substr(0, ids.length - 1);
    var url = '?g=app&m=member&a=delInfo&type=1&mold=1&ids=' + ids
    $.get(url, function (data) {
        $.toast(data.info, "text");
        setTimeout(function () {
            window.location.reload()
        }, 1000)

    });
}
/**
 * [checkAll 全选、反选函数]
 * @param  {[type]} allObj    [ 全选选择器 ]
 * @param  {[type]} singleObj [ 单选选择器 ]
 * @return {[type]}           [无]
 */
function checkAll(allSelector, singleSelector) {
    // 全选&反选
    $('body').on('change', allSelector, function () {
        if ($(this).prop('checked')) {              // 全选
            $(singleSelector).prop('checked', true);
            $('.del_btn').removeClass('weui-btn_disabled');
        } else {                                      // 反选
            $(singleSelector).prop('checked', false);
            $('.del_btn').addClass('weui-btn_disabled');
        }
    });
    // 单选
    $('body').on('change', singleSelector, function () {
        var length = $(singleSelector).length;

        var num = 0;
        $(singleSelector).each(function () {
            if ($(this).prop('checked')) {
                num++;
            }
        });

        if (num == length) {                        // 判断单选点击是否已经全部选中
            $(allSelector).prop('checked', true); // 全选
        } else {
            $(allSelector).prop('checked', false); // 反选
        }
        if (num == 0) {
            $('.del_btn').addClass('weui-btn_disabled');
        } else {
            $('.del_btn').removeClass('weui-btn_disabled');
        }
    });
}