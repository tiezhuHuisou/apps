$(function() {
			$("[attr='BatchDel']").click(function() {
				var check = $("input[name='items']:checked");
				if (check.length < 1) {
					layer.open({
						content:'请选择删除项'
					});
					return false;
				}
				var url = $("#url").val();
				if(window.confirm('您确定要删除吗？')){
					var id = new Array();
					check.each(function(i) {
						id[i] = $(this).val();
					});
	
					$.post(url, {
						ids : id
					}, function(data) {
						if (data.errno == 0) {
							layer.open({
								content:data.error
							});
							setTimeout(function () {location.reload()},1000);
						} else {
							layer.open({
								content:data.error
							});
						}
	
					}, 'json');
				}

			});
			$("[attr='BatchUp']").click(function() {
				var check = $("input[name='items']:checked");
				if (check.length < 1) {
					layer.open({
						content:'请选择修改项'
					});
					return false;
				}
				if(window.confirm('您确定要改变类别吗？')){
					var id = new Array();
					check.each(function(i) {
						id[i] = $(this).val();
					});
					classify = $("#sel ").val();
					$.post('?g=admin&m=product&a=up_classify', {
						ids : id,
						classify : classify
					}, function(data) {
						if (data.errno == 0) {
							layer.open({
								content:data.error
							});
							setTimeout(function () {location.reload()},1000);
						} else {
							layer.open({
								content:data.error
							});
						}
	
					}, 'json');
				}

			});
		});