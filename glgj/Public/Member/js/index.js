$(function() {
	// $('.msg,.collect').width( ( $('.wrapper').width() - 84 ) * 0.5 );
    $('.msg,.collect').width( $('.wrapper').width() - 30 );
	$('.msg_col').eq( $('.msg_col').length - 1 ).css('border-right', 'none');
	$('.collect_col').eq( $('.collect_col').length - 1 ).css('border-right', 'none');
	$('.msg_col').width( ( $('.msg_content').width() - $('.msg_col').length - 1 ) / $('.msg_col').length );
	$('.collect_col').width( ( $('.collect_content').width() - $('.collect_col').length - 1 ) / $('.collect_col').length );
})