$(function() {    
    KindEditor.ready(function(K) {
        var editor1 = K.editor({
            allowFileManager : true
        });

        K.create('#company_content',{                   // 企业详情
            width        : '670px',
            height       : '300px',
            items       : ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
            afterBlur    : function(){this.sync();},
            resizeType   : 0
        });

        $('#logo_upload').click(function() {   			// 企业logo
            editor1.loadPlugin('image', function() {
                editor1.plugin.imageDialog({
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {
                        $("#logo_preview").attr('src',url);
                        $("#logo_url").val(url);
                        editor1.hideDialog();
                    }
                });
            });
            return false;
        });
    });
});