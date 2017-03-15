$(function(){
    if ( !$('.buy_wrap').length ) {                                             // 购物车为空
        collectEmpty();                                                                    // 执行购物车为空状态函数
    }
    var count = 0;                                                                                                // 记录选中的个数

    $('.header_right').click(function() {                                                                       // 编辑按妞
        if( $(this).text() == '编辑' ) {                                                                        // 点击之前为待编辑状态
            $(this).text('完成');                                                                               // 改变文字
            $('.buy_wrap').find('a').attr('href','javascript:void(0);');                                        // 去掉链接
            $('section.no_footer').addClass('viewport').removeClass('no_footer').css('margin-top','65px');                               // 主体的类名改变
            $('footer').css('display','-webkit-box');                                                           // 底部显示
            $('.unselected_icon').css('display','block');                                                       // 列表编辑显示
        }else {
            $(this).text('编辑');
            $('.buy_wrap').find('a').attr('href','?g=app&m=index&a=need_detail&id={$li.id}');                                 // 去掉链接
            $('section.viewport').addClass('no_footer').removeClass('viewport');                                // 主体的类名改变
            $('footer').css('display','none');                                                                  // 底部隐藏
            $('.unselected_icon').css('display','none');                                                        // 列表编辑隐藏
        }
    });

    $('.unselected_icon').click( function(){                                                                    // 列表选中
        if(!$(this).hasClass('allselected')){                                                                   // 判断是全选还是单选
            if($(this).hasClass('selected_icon')){
                $(this).removeClass('selected_icon');                                                           // 选中时，点击取消
                $('.buy_wrap').each(function(){                                                         // 得到选中的个数
                    if($(this).find('.unselected_icon').hasClass('selected_icon')){
                        count++;
                    }   
                }); 
                if( count == 0 ){                                                                               // 没有选中的按钮                                                                      
                    $('.news_delete').removeClass('news_delete_selected');                                      // 删除按钮背景色改变
                }
                if( count == $('.buy_wrap').length ){                                                   // 全部选中
                    $('.allselected').addClass('selected_icon');
                }else{
                    $('.allselected').removeClass('selected_icon');
                }
            }else{
                $( this ).addClass('selected_icon');                                                            // 未选中时，点击选中
                $('.buy_wrap').each(function(){
                    if($(this).find('.unselected_icon').hasClass('selected_icon')){
                        $('.news_delete').addClass('news_delete_selected');                                     // 添加按钮背景色改变
                        count++;                    
                    }
                }); 
                if(count == $('.buy_wrap').length){                                                     // 若列表全选则底部全选按钮选中
                    $('.allselected').addClass('selected_icon');
                }else{
                    $('.allselected').removeClass('selected_icon');
                }
            }
        }else{}
        count=0;
    });

   $('.allselected').click( function(){                                                                         // 全选、反选
        if( $(this).hasClass('selected_icon') ){                                                                // 反选
            $(this).removeClass('selected_icon');
            $('.buy_wrap').find('.unselected_icon').removeClass('selected_icon');                               // 列表不能选中
            $('.news_delete').removeClass('news_delete_selected');                                              // 删除按钮背景色改变
        }else{                                                                                                  // 全选                                                                                                                                                                   
            $(this).addClass('selected_icon');
            $('.buy_wrap').find('.unselected_icon').addClass('selected_icon');  
            $('.news_delete').addClass('news_delete_selected');                                                 // 删除按钮背景色改变
        }
   });

   $('.news_delete').click( function(){                                                                         // 删除操作
    if ( !$(this).hasClass('news_delete_selected')) {
            layer.open({
                content : '请选择需要删除的求购',
                time    : 2
            });
        } else {
            layer.open({
                content : '您真的要删除选中的求购吗？',
                btn     : ["确定","取消"],
                yes     : function( index ) {
                    var delectNum  = $('.buy_wrap').length;                                                                         // 删除求购种类数量
                    var selectedId = '';
                    var classify = $("#classify").val(); //收藏类别// 删除求购ID
                    $('.buy_wrap').each(function() {
                    	if ( $(this).find('.unselected_icon').hasClass('selected_icon') ) {
                            selectedId += $(this).find('.unselected_icon').data('id').toString() + ',';                                             // 把求购的ID存入数组
                            delectNum--; 
                            $(this).remove();

                            console.log(delectNum);   
                        }
                    });
                    url = "?g=app&m=index&a=favorite_delall";
                    $.post(url, {
						ids : selectedId,
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
//                    selectedId = selectedId.substr( 0, selectedId.length - 1 );                                 // 删除求购ID去掉最后一个逗号
//                    layer.open({                                                                                // 弹窗提示
//                        content : '删除成功，您删除的求购有：' + selectedId,                                     // selectedId为测试用，上线时请删除
//                        time    : 0
//                    });

                    layer.close(index);                                                                         // 关闭当前弹窗
                },
                no      : function(){
                    return false;
                }
            });
        }
    });
});

function collectEmpty() {                                                       // 收藏为空状态函数
    $('.need_collect').html( $('.collect_empty_wrap').html() );                 // 清空购物车html代码容器，加载缺省页html代码
}