$(function(){
    /* 添加一行 */
    $('#addline').click(function(){
        var html = '';
        html += '<tr class="list_items">';
        html += '<td>';
        html += '<input class="list_full" type="text" name="reach[]" value="" />';
        html += '</td>';
        html += '<td>';
        html += '<input class="list_reduce" type="text" name="give[]" value="" />';
        html += '</td>';
        html += '<td>';
        html += '<input class="list_sort" type="text" name="sort[]" value="50" />';
        html += '</td>';
        html += '<td>';
        html += '<a class="list_del" href="javascript:void(0);">删除</a>';
        html += '</td>';
        html += '</tr>';
        $('.list_table').append(html);
    });

    /* 删除一行 */
    $('.list_table').on('click', '.list_del', function(){
        if ( $('.list_items').length == 1 ) {
            layer.msg('充值规则至少一条', { time : 2000 });
        } else {
            $(this).parent().parent().remove();
        }
    });

    /* 表单提交 */
    var ajaxFlag = true;
    $('.btn_submit').click(function(){
        // ajaxFlag = false;                   // 防止表单重复提交

        // var numReg     = /^\d+$/;           // 正则表达式验证正整数
        // var sortReg    = /^\d{1,2}$/;       // 正则表达式验证0-99
        // var fullFlag   = true;              // 满额验证正整数标帜
        // var reduceFlag = true;              // 减额验证正整数标帜
        // var sortFlag   = true;              // 排序验证0-99标帜
        // var sizeFlag   = true;              // 满额与减额大小验证标帜
        // for( var i = 0; i < $('.list_items').length; i ++ ) {
        //     /* 验证满额为正整数 */
        //     if ( !checkRule( $('.list_full').eq(i), numReg ) ) {
        //         fullFlag = false;
        //     }
        //     /* 验证减额为正整数 */
        //     if ( !checkRule( $('.list_reduce').eq(i), numReg ) ) {
        //         reduceFlag = false;
        //     }
        //     /* 验证规则排序为0-99 */
        //     if ( !checkRule( $('.list_sort').eq(i), sortReg ) ) {
        //         sortFlag = false;
        //     }
        //     /* 验证减额不能大于满额 */
        //     if ( checkSize( $('.list_full'), $('.list_reduce') ) < 0 ) {
        //         sizeFlag = false;
        //     }
        // }
        // if ( !fullFlag ) {
        //     layer.msg('满额为数字', { time : 1500 });
        //     ajaxFlag = true;
        //     return false;
        // }
        // if ( !reduceFlag ) {
        //     layer.msg('减额为数字', { time : 1500 });
        //     ajaxFlag = true;
        //     return false;
        // }
        // if ( !sortFlag ) {
        //     layer.msg('规则排序为0-99', { time : 1500 });
        //     ajaxFlag = true;
        //     return false;
        // }
        // if ( !sizeFlag ) {
        //     layer.msg('减额不能大于满额', { time : 1500 });
        //     ajaxFlag = true;
        //     return false;
        // }

        /* ajax提交表单 */
        $.post(window.location.href, $('form').serialize(), function(data) {
            layer.msg( data.info, { time : 1500 }, function(){
                if ( data.status == 1 ) {
                    window.location.reload();
                }
            });
        });
        return false;
    });
});
/* 验证是否为空 */
function checkVal(obj){
    var flag = true;
    if ( !$.trim( obj.val() ) ) {
        flag = false;
    }
    return flag;
}
/* 验证正则表达式 */
function checkRule( obj, reg ){
    var flag = true;
    if ( !reg.test( $.trim( obj.val() ) ) ) {
        flag = false;
    }
    return flag;
}
/* 验证长度 */
function checkLen( obj, min, max ){
    var flag = true;
    var len = $.trim( obj.val() ).length;
    if ( len < min ) {
        flag = false;
    } else if( len > max ){
        flag = false;
    }
    return flag;
}
/*
 * 验证大小
 * 返回num( 1为大于，-1为小于 )
 */
function checkSize( firstObj, twoObj ){
    var num = 1;
    if ( $.trim(firstObj.val()) <= $.trim(twoObj.val()) ) {
        num = -1;
    }
    return num;
}