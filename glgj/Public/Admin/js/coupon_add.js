$(function() {
    $("#start_date").click( function() {    // 活动开始日期
        laydate({
            elem   : '#start_date',         //需显示日期的元素选择器
            format : 'YYYY-MM-DD hh:mm:ss', //日期格式
            istime : true                   //是否开启时间选择
        });
    });

    $("#end_date").click( function() {      // 活动结束日期
        laydate({
            elem : '#end_date',             //需显示日期的元素选择器
            format : 'YYYY-MM-DD hh:mm:ss', //日期格式
            istime : true                   //是否开启时间选择
        });
    });

    /* 指定商家 */
    $('#coupon_type').change(function() {
        if ( $(this).val() == 2 ) {
            $('#company_id').parents('tr').show();
            $('#category_id').children('option').eq(0).prop('selected', true).parents('tr').hide();
        } else if ( $(this).val() == 3 ) {
            $('#category_id').parents('tr').show();
            $('#company_id').children('option').eq(0).prop('selected', true).parents('tr').hide();
        } else {
            $('#company_id').children('option').eq(0).prop('selected', true).parents('tr').hide();
            $('#category_id').children('option').eq(0).prop('selected', true).parents('tr').hide();
        }
    });

    KindEditor.ready(function(K) {
        /* 分类logo图片上传 */
        var editor1 = K.editor({
            allowFileManager : true
        });
        $('#logobtn').click(function() {
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#logoId").attr('src',url).parents('tr').show();
                        $("#logourl").val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });

    /* 指定分类 */
});