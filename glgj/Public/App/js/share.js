$(function() {
    $(".line").width( ( $('body').width() ) / 4 );                                      //设置每一项分享方式为屏幕宽度的 1/4
    $('.header_right,.share_btn').click(function(){
        $(".mask").show();                                                              //显示遮罩层        
        $(".share_bomb_box").show();                                                    //显示分享方式弹框
        $("body").bind("touchmove",function(e){                                         //禁止屏幕滑动     
            e.preventDefault();  
        });
    });
    $(".share_bomb_box_cancel").click(function(){                                       //弹框取消按钮点击事件
        $(".mask").hide();                                                              //隐藏遮罩层
        $(".share_bomb_box").hide();                                                    //隐藏分享方式弹框
        $("body").unbind("touchmove");                                                  //启用屏幕滑动
    });
});