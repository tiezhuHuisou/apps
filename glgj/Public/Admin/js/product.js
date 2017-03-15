$(function() {
    $('#qrcode').click(function() {
        var check = $("input[name='items']:checked");
        if (check.length < 1) {
            layer.open({
                title   : '生成二维码',
                content : '请选择要生成二维码的产品',
                time    : 2000
            });
            return false;
        }
        layer.confirm('确定要生成二维码吗？', function(luxury) {
            var id = new Array();
            check.each(function(i) {
                id[i] = $(this).val();
            });

            $.post('?g=admin&m=product&a=qrcode', {id: id}, function( data ) {
                var icon = 2;
                if ( data.status == 1 ) {
                    icon = 1;
                }
                layer.msg(data.info, {
                    time : 2000,
                    icon : icon
                }, function() {
                    window.location.reload();
                });
            });

            layer.close(luxury);
        });  
    });
});