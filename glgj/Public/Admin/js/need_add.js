$(function() {
	KindEditor.ready(function(K) {
		var editor1 = K.editor({
	        allowFileManager : true,
            imageUploadLimit : 5,
	    });

		K.create('#need_content',{
			width:'670px',
			height:'300px',
			items 		: ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
			afterBlur	: function(){this.sync();},
			resizeType:0
		});

        $('#carousel_btn').click(function() {           // 轮播图
            editor1.loadPlugin('multiimage', function() {
                editor1.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#carousel_img');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="allpic[]" type="hidden" /></div>');
                        });
                        $('#carousel_img').parents('tr').show();
                        editor1.hideDialog();
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
    /* 删除轮播图 */
    $('body').on('click', '.delete_img', function() {
        $(this).parents('.img_wrap').remove();   
    });
});