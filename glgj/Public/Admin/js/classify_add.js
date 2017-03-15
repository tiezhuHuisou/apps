$(function() {
    KindEditor.ready(function(K) {
        /* logo图片上传 */
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

        /* 主题图片上传 */
        var editor2 = K.editor({
            allowFileManager : true
        });
        $('#themebtn').click(function() {
            editor2.loadPlugin('image', function() {
                editor2.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#themeId").attr('src',url).parents('tr').show();
                        $("#themeurl").val(url);
                        editor2.hideDialog();
                    }
                });
            });
            return false;
        });
    });
});