$(function() {
    $('.delete_img').click(function() {
        $(this).parents('.img_wrap').remove();
    });
    
    KindEditor.ready(function(K) {      // 富文本编辑器插件初始化
        K.create('#company_add_content',{           // 创建富文本编辑器
            width:'670px',
            height:'300px',
            items 		: ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur	: function(){this.sync();},
            resizeType:0
        });

        /* 企业logo */
        var editor1 = K.editor({
            allowFileManager : true
        });
        $('#thumbnail').click(function() {
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                    	$("#preview").attr('src',url).parents('tr').show();
                    	$("#imgurl").val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });

        /* 企业主页背景图 */
        var editor2 = K.editor({
            allowFileManager : true
        });
        $('#bgimg_btn').click(function() {
            editor2.loadPlugin('image', function() {
                editor2.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#bgimg_preview").attr('src',url).parents('tr').show();
                        $("#bgimg_url").val(url);
                        editor2.hideDialog();
                    }
                });
            });
            return false;
        });

        /* 企业主页背景图 */
        var editor3 = K.editor({
            allowFileManager : true
        });
        $('#introduction_bgimg_btn').click(function() {
            editor3.loadPlugin('image', function() {
                editor3.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#introduction_bgimg_preview").attr('src',url).parents('tr').show();
                        $("#introduction_bgimg_url").val(url);
                        editor3.hideDialog();
                    }
                });
            });
            return false;
        });

        /* 企业资质图片 */
        var editor4 = K.editor({
            allowFileManager : true
        });
        $('#carousel_btn').click(function() {           // 轮播图
            editor4.loadPlugin('multiimage', function() {
                editor4.plugin.multiImageDialog({
                    clickFn : function(urlList) {
                        var temp = K('#carousel_img');
                        K.each(urlList, function(i, data) {
                            temp.append('<div class="img_wrap"><img class="delete_img" src="' + imgUrl + '/delete_img.png" width="20" height="20" /><img src="' + data.url + '" width="100" height="100" /><input value="' + data.url + '" name="cert[]" type="hidden" /></div>');
                        });
                        $('.delete_img').click(function() {
                            $(this).parents('.img_wrap').remove();
                        });
                        $('#carousel_img').parents('tr').show();
                        editor4.hideDialog();
                    }
                });
            });
            return false;
        });

        /* 主题图片上传 */
        var editor5 = K.editor({
            allowFileManager : true
        });
        $('#themebtn').click(function() {
            editor5.loadPlugin('image', function() {
                editor5.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#themeId").attr('src',url).parents('tr').show();
                        $("#themeurl").val(url);
                        editor5.hideDialog();
                    }
                });
            });
            return false;
        });
    });
    
    /* 选择省 拉取后台城市数据并展示 重置区/县数据 */
    $('.province').change(function(){
		var id = $(this).val();
		if ( id ) {
			var city_arr = data[id];
			var str_city = '<option value="">请选择所在市</option>';
			for ( var i = 0; i < city_arr.length; i++ ) {
				str_city +='<option value="' + city_arr[i]['id'] + '">' + city_arr[i]['name'] + '</option>';
			}
			$('.city').html(str_city);
		}else{
			$('.city').html('<option value="">请选择所在市</option>');
			$('.towns').html('<option value="">请选择所在区/县</option>');
		}
	});
    /* 选择市 拉取后台区/县数据并展示 */
	$('.city').change(function(){
		var id = $(this).val();
		if ( id ) {
			var town_arr = data[id];
			var str_town = '<option value="">请选择所在区/县</option>';
			for (var i = 0; i < town_arr.length; i++) {
				str_town +='<option value="' + town_arr[i]['id'] + '">' + town_arr[i]['name'] + '</option>';
			}
			$('.towns').html(str_town);
		} else {
			$('.towns').html('<option value="">请选择所在区/县</option>');
		}
	});
});