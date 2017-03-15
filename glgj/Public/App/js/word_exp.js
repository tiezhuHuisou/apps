$(function() {
    // 设置保存按钮、工作描述文本域大小
    $('.submit').width( $('.experience').width() );
    $('.exp').width( $('.exp').parent().width() - 16 );
    $('.exp').parents('.row').height( $('.exp').height() + 16 );

    // 防止横竖屏切换
    $(window).resize(function() {
        $('.submit').width( $('.experience').width() );
        $('.exp').width( $('.exp').parent().width() - 16 );
        $('.exp').parents('.row').height( $('.exp').height() + 16 );
    });

    // 加载在职时间数据
    var nowDate  = new Date();
    var nowYear = nowDate.getFullYear();    // 当前年份
    for (var i = nowYear; i > nowYear - 51; i--) {
        var brginHtml = '<option value="'+i+'">'+i+'年</option>';
        var endHtml   = '<option value="'+i+'">'+i+'年</option>';
        if ( $('#begin_time').val().split('-')[0] == i ) {
            brginHtml = '<option value="'+i+'" selected="selected">'+i+'年</option>';
        }
        if ( $('#end_time').val().split('-')[0] == i ) {
            endHtml = '<option value="'+i+'" selected="selected">'+i+'年</option>';
        }
        $('#begin_year').append(brginHtml);
        $('#end_year').append(endHtml);
    };
    for (var i = 1; i < 13; i++) {
        var k = i;
        if ( k < 10 ) {
            k = '0' + k;
        }
        var brginHtml = '<option value="'+k+'">'+k+'月</option>';
        var endHtml   = '<option value="'+k+'">'+k+'月</option>';
        if ( $('#begin_time').val().split('-')[1] == k ) {
            brginHtml = '<option value="'+k+'" selected="selected">'+k+'月</option>';
        }
        if ( $('#end_time').val().split('-')[1] == k ) {
            endHtml = '<option value="'+k+'" selected="selected">'+k+'月</option>';
        }
        $('#begin_month').append(brginHtml);
        $('#end_month').append(endHtml);
    };
    $('#begin_time').val( $('#begin_year').val() + '-' + $('#begin_month').val() );
    $('#end_time').val( $('#end_year').val() + '-' + $('#end_month').val() );

    // 在职时间选择
    $('#begin_year,#begin_month').change(function() {
        $('#begin_time').val( $('#begin_year').val() + '-' + $('#begin_month').val() );
    });

    $('#end_year,#end_month').change(function() {
        $('#end_time').val( $('#end_year').val() + '-' + $('#end_month').val() );
    });

    // 提交表单前验证
    $('.submit').click(function() {
        var errorFlag      = true;                                  // 数据填写错误标志
        var companyNameVal = $('.company_name').val();              // 所在公司输入框的值
        var postVal        = $('.post').val();                      // 职位名称输入框的值
        var beginYear      = parseInt( $('#begin_year').val() );    // 开始年份
        var beginMonth     = parseInt( $('#begin_month').val() );   // 开始月份
        var endYear        = parseInt( $('#end_year').val() );      // 结束年份
        var endMonth       = parseInt( $('#end_month').val() );     // 结束月份

        // 表单验证
        if ( !companyNameVal ) {
            layer.open({
                content : '请填写所在公司',
                time    : 1
            });
        } else if ( !postVal ) {
            layer.open({
                content : '请填写职位名称',
                time    : 1
            });
        } else if ( (beginYear > endYear) || (beginYear == endYear && beginMonth > endMonth) ) {
            layer.open({
                content : '请正确选择在职时间',
                time    : 1
            });
        } else {
            // 全部验证通过
            errorFlag = false;
            var data = $('form').serialize();
            $.post(window.location.href,data,function(msg){
                layer.open({
                content :   msg.info,
                time:   1,
                end:function(){
                    window.history.go(-1);
                    //window.location.href = '?g=app&m=recruit&a=addapply';
                }
                });
            });
        }

        // 表单验证不通过，则不提交表单
        if ( errorFlag ) {
            return false;
        }
    });
});