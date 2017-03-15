$(function() {
	$('.flex').click(function() {							// 分类收缩
		if ( $(this).hasClass('flexed') ) {
			var className = '.' + $(this).siblings('span').text();
			$(this).removeClass('flexed');
			$(className).hide().children('td').eq(1).children('i').removeClass('flexed');
			if ( $(this).parents('tr').attr('class') ) {
				$( '.' + $(className).children('td').eq(1).children('span').text() ).hide().children('td').eq(1).children('i').removeClass('flexed');
			} else {
				$( '.p' + $(this).siblings('span').text()).hide().children('td').eq(1).children('i').removeClass('flexed');
			}
		} else{
			$(this).addClass('flexed');
			$( '.' + $(this).siblings('span').text() ).show();
		}
	});
});