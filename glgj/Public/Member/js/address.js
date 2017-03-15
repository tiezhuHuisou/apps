$(function(){
    /* 删除地址 */
    var delFlag = true;                 // 防止重复提交
    $(".del_address").click(function(){
        if ( delFlag == true ) {        // 点击之后变为false
            delFlag = false;
        } else {                        // 多次点击阻止事件
            return false;
        }

        var _this = $(this);
        layer.confirm('您确定要删除吗？', {
            title : '删除确认',
            btn   : ['确定','取消'] //按钮
        }, function(){
            $.get("?g=member&m=buy&a=deladdr",{ id : _this.data('id') },function(data){
                var iconType = 2;
                if ( data.status == 1 ) {
                    iconType = 1;
                }
                layer.msg( data.info,{ icon : iconType, time : 1000 }, function(){
                    if ( data.status == 1 ) {           // 成功，页面重载
                        window.location.reload();
                    } else {                            // 失败，可重新操作
                        delFlag = true;
                    }
                });
            },'json');
        }, function(){
            delFlag = true;
        });
    });

    /* 设为默认地址 */
    var setFlag = true;
    $(".set_address").click(function(){
        if ( setFlag == true ) {        // 点击之后变为false
            setFlag = false;
        } else {                        // 多次点击阻止事件
            return false;
        }

        var _this = $(this);
        layer.confirm('您确定要设为默认地址吗？', {
            title : '设为默认地址确认',
            btn   : ['确定','取消'] //按钮
        }, function(){
            $.get("?g=member&m=buy&a=setaddr",{ id : _this.data('id') },function(data){
                var iconType = 2;
                if ( data.status == 1 ) {
                    iconType = 1;
                }
                layer.msg( data.info,{ icon : iconType, time : 1000 }, function(){
                    if ( data.status == 1 ) {           // 成功，页面重载
                        window.location.reload();
                    } else {                            // 失败，可重新操作
                        setFlag = true;
                    }
                });
            },'json');
        }, function(){
            setFlag = true;
        });
    });

})