$(function() {

    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
    
    KindEditor.ready(function(K) {
        var editor1 = K.editor({
            allowFileManager : true
        });

        K.create('#need_content',{                      // 求购详情
            width        : '670px',
            height       : '300px',
            afterBlur    : function(){this.sync();},
            resizeType   : 0
        });

        $('#main_upload').click(function() {            // 求购主图
        	var _this = $(this);
        	editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#main_preview").attr('src',url);
                        $("#main_url").val(url);
                        _this.parents('.add_row').next().removeClass('no');
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });

});