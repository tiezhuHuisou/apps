$(function(){
    /* 相册图片上传 */
    KindEditor.ready(function(K) {
        var editor1 = K.editor({
            allowFileManager : true,
            imageUploadLimit : 3
        });
        $('.list_table').on('click', '.pic_select', function() {
            var _this      = $(this);
            var urlObj     = _this.siblings('.pic_url');
            var previewObj = _this.parent().siblings('.preview_wrap').find('.pic_preview');
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        previewObj.attr('src',url);
                        urlObj.val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });

    /* 添加一行 */
    $('#addpic').click(function(){
        var html = '';
        html += '<tr class="list_items">';
        html += '<td class="tr preview_wrap">';
        html += '<img class="pic_preview" src="" width="50" height="50" />';
        html += '</td>';
        html += '<td class="tl">';
        html += '<input class="pic_url" type="hidden" name="img[]" value="" />';
        html += '<a class="opt_btn pic_select" href="javascript:void(0);">选择图片</a>';
        html += '</td>';
        html += '<td>';
        html += '<input class="list_sort" type="text" name="sort[]" value="50" />';
        html += '</td>';
        html += '<td>';
        /*html += '<label class="mgr">';
        html += '<input class="iscover_val" type="hidden" name="is_cover[]" value="0" />';
        html += '<input class="pt3 iscover" type="radio" name="coverbtn" />';
        html += '<span>设为封面</span>';
        html += '</label>';*/
        html += '<a class="list_del" href="javascript:void(0);">删除</a>';
        html += '</td>';
        html += '</tr>';
        $('.list_table').append(html);
    });

    /* 删除一行 */
    $('.list_table').on('click', '.list_del', function(){
        if ( $('.list_items').length == 1 ) {
            layer.msg('相册图片至少一张', { time : 2000 });
        } else {
            $(this).parent().parent().remove();
            for ( var i = 0; i < $('.list_items').length; i ++ ) {
                $('.list_items').eq(i).find('.iscover').val(i);
            }
        }
    });

    /* 设为封面改变事件 */
    $('.list_table').on('click', '.iscover', function(){
        if ( $(this).is(':checked') ) {
            $('.iscover_val').val(0);
            $(this).siblings('.iscover_val').val(1);
        }
    });

    /* 表单提交 */
    var ajaxFlag = true;
    $('.btn_submit').click(function(){
        ajaxFlag = false;

        /* 验证相册名称是否填写 */
        /*if ( !checkVal( $('.albumtitle') ) ) {
            layer.msg('请填写相册名称', { time : 1500 });
            ajaxFlag = true;
            return false;
        } else if( !checkLen( $('.albumtitle'), 2, 50 ) ) {
            layer.msg('相册名称为2-50位字符', { time : 1500 });
            ajaxFlag = true;
            return false;
        }*/

        /* 验证相册排序为0-99 */
        var sortReg  = /^\d{1,2}$/;
        /*if ( !checkRule( $('.album_sort'), sortReg ) ) {
            layer.msg('相册排序为0-99', { time : 1500 });
            ajaxFlag = true;
            return false;
        }*/

        /* 验证图片是否上传 */
        var picFlag = true;
        for( var i = 0; i < $('.list_items').length; i ++ ) {
            if ( !checkVal( $('.list_items').eq(i).find('.pic_url') ) ) {
                picFlag = false;
            }
        }
        if ( !picFlag ) {
            layer.msg('请上传相册图片', { time : 1500 });
            ajaxFlag = true;
            return false;
        }

        /* 验证图片排序为0-99 */
        var sortFlag = true;
        for( var i = 0; i < $('.list_items').length; i ++ ) {
            if ( !checkRule( $('.list_items').eq(i).find('.list_sort'), sortReg ) ) {
                sortFlag = false;
            }
        }
        if ( !sortFlag ) {
            layer.msg('图片排序为0-99', { time : 1500 });
            ajaxFlag = true;
            return false;
        }

        /* ajax提交表单 */
        $.post(window.location.href, $('form').serialize(), function(data) {
            layer.msg( data.info, { time : 1500 }, function(){
                if ( data.url ) {
                    window.location.href = data.url;
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