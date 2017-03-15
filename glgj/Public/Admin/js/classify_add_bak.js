$(function() {   
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
});