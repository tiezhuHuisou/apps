$(function() {
	$("#birthday").click( function() {                 // 生日选择
		laydate({
			elem : '#birthday',
			max  : laydate.now()
		});
	});

	KindEditor.ready(function(K) {
        var editor1 = K.editor({
            allowFileManager : true
        });

        $('#portrait_upload').click(function() {        // 头像
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#portrait_preview").attr('src',url);
                        $("#portrait_url").val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });
});