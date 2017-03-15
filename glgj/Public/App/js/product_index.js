$(function(){
    setInterval('autoScroll(".info_content")', 2000);
})
function autoScroll(obj) {
    var listObj = $(obj).find('.list');
    var _mgt    = - listObj.find('li').eq(0).height() + 'px';
    if ( listObj.find('li').length == 1 ) {
        return false;
    } else {
        listObj.animate({
            marginTop : _mgt
        }, 500, function() {
            $(this).css({ marginTop : '0px' }).find('li:first').appendTo(this);
        });
    }
}