$(function() {
    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
    
    KindEditor.ready(function(K) {
        var editor = K.editor({
            allowFileManager : true,
            imageUploadLimit : 9
        });
        $('#carousel_btn').click(function() {           // 图片
            editor.loadPlugin('multiimage', function() {
                editor.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#carousel_img');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="allpic[]" type="hidden" /></div>');
                        });
                        $('.delete_img').click(function() {
                            $(this).parents('.img_wrap').remove();
                        });
                        $('#carousel_img').parents('tr').show();
                        editor.hideDialog();
                    }
                });
            });
            return false;
        });

    });
    /* ajax请求发布人（企业）下拉框信息 */
    $('#issue_type input[type="radio"]').change(function() {
        var _type = $(this).val();
        if ( $(this).prop('checked') ) {
            $.post('?g=admin&m=product&a=issuetype', {type: _type}, function(data) {
                var _html   = '<option value="0">请选择</option>';
                for (var i = 0; i < data.length; i++) {
                    var issueName = data[i]['name'] ? data[i]['name'] : '个人';
                    var _mobile   = _type == 1 ? '('+data[i]['mobile']+')' : '';
                    _html += '<option value="'+data[i]['id']+'">'+issueName+_mobile+'</option>';
                }
                $('#issue_author').html(_html);
            });
        }
    });
});