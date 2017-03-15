$(function() {
    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
    
    KindEditor.ready(function(K) {
        /* 产品详情富文本编辑器初始化 */
        K.create('#summary',{
            width        : '670px',
            height       : '300px',
            items       : ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur    : function(){this.sync();},
            resizeType   : 0
        });

        /* 轮播图多图上传 */
        var editor3 = K.editor({
            allowFileManager : true
        });
        $('#carousel_upload').click(function() {           // 轮播图
            var _this = $(this);
            editor3.loadPlugin('multiimage', function() {
                editor3.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#carousel_preview');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="allpic[]" type="hidden" /></div>');
                        });
                        $('.delete_img').click(function() {
                            $(this).parents('.img_wrap').remove();
                        });
                        $('.default_img').remove();
                        _this.parents('.add_row').next().removeClass('no');
                        editor3.hideDialog();
                    }
                });
            });
            return false;
        });
    });
});