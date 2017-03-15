$(function() {
	KindEditor.ready(function(K) {      // 富文本编辑器插件初始化
		var editor1 = K.editor({
	        allowFileManager : true,
            imageUploadLimit : 3
	    });

		K.create('#news_content',{			// 创建富文本编辑器
			width:'670px',
			height:'300px',
			items 		: ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
			afterBlur	: function(){this.sync();},
			resizeType:0
		});
        $('#cover_btn').click(function() {           // 图片
            editor1.loadPlugin('multiimage', function() {
                editor1.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#cover_img');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="allpic[]" type="hidden" /></div>');
                        });
                        $('.delete_img').click(function() {
                            $(this).parents('.img_wrap').remove();
                        });
                        $('#cover_img').parents('tr').show();
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
	});
    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
});