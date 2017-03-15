$(function(){
	$(".link_btn").click(function(){
		var _from=$("form");
		$.post(_form.attr("action"),_form.serialize(),function(data){
			if(data.status==1){
				location.reload();
			}else{
				layer.open({
		            content: data.info,
		            style: 'background-color:#fff; color:red; border:none;',
		            time: 2
		        });
		        return false;
			}
		},'json');
		return false;
	});

})