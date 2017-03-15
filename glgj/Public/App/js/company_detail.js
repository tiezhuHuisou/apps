$(function() {
    var ajaxFlag = true;
    var nid      = $("#nid").val();
    var title    = $(".company_detail_top_nickname").text();
    var sign     = $("#sign").val();
    var uid      = $("#uid").val();
    var uuid     = $("#uuid").val() ? '&appsign=1&uuid=' + $("#uuid").val() : '';
	$('.collect').click(function() {
        /* 防止多次提交 */
        if ( ajaxFlag === true ) {
            ajaxFlag = false;
        } else {
            return false;
        }
        var _this = $(this);
        sign  = $("#sign").val();
        /* 判断登陆 */
        if ( uid == '' ) {
            layer.open({
                content : '请先登陆',
                time    : 1
            });
            ajaxFlag = true;
            return false;
        }
        /* 判断是否收藏请求相对于的地址 */
        if ( sign == 1 ) {
            var url = '?g=app&m=company&a=favorite_del' + uuid;
        } else {
            var url = '?g=app&m=company&a=favorite_add' + uuid;
        }

        /* post请求 */
        $.post(url, {nid: nid, title: title}, function( data ) {
            layer.open({
                content : data.info,
                time    : 1,
                end     : function() {
                    if ( data.status == 1 ) {
                        $("#sign").val( 1 - sign );
                        _this.toggleClass('collected');
                    }
                    ajaxFlag = true;
                }
            });
        });
	});
});