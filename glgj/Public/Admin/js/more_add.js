$(function() {
	KindEditor.ready(function(K) {      // 富文本编辑器插件初始化
		window.editor = K.create('#more_content',{			// 创建富文本编辑器
			width:'670px',
			height:'300px',
			items 		: ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright', 'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript', 'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/', 'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak', 'anchor', 'link', 'unlink', '|', 'about'],
			afterBlur	: function(){this.sync();},
			resizeType:0
		});

		/* 点击标签赋值到富文本编辑器中 */
		$('.sellabel').click(function() {
			var str = $(this).attr('instr');
			editor.insertHtml(str);
		});
	});
});