$(function(){
	var count=0;																								// 记录选中的个数

    $('.header_right').click(function() {											    						// 编辑按妞
        if( $(this).text() == '编辑' ) {																		// 点击之前为待编辑状态
            $(this).text('完成');						
//          $('.news_list').find('a').attr('href','javascript:void(0);');										// 去掉链接
//          $('section.no_footer').addClass('viewport').removeClass('no_footer');								// 主体的类名改变
            $('footer').css('display','-webkit-box');															// 底部显示
            $('.unselected_icon').css('display','block');														// 列表编辑显示
//          $('.news_list li a').find('img').removeClass('mgl16');												// 图片左侧的空隙删除
        }else {
            $(this).text('编辑');
//          $('.news_list').find('a').attr('href','?g=app&m=news&a=detail');									// 去掉链接
//          $('section.viewport').addClass('no_footer').removeClass('viewport');								// 主体的类名改变
            $('footer').css('display','none');																	// 底部隐藏
            $('.unselected_icon').css('display','none');        												// 列表编辑隐藏
//          $('.news_list li a').find('img').addClass('mgl16');													// 图片左侧的空隙添加
        }
    });

    $('.unselected_icon').click( function(){																	// 列表选中
    	if(!$(this).hasClass('allselected')){                                              					 	// 判断是全选还是单选
	    	if($(this).hasClass('selected_icon')){
	    		$(this).removeClass('selected_icon');															// 选中时，点击取消
	    		$('.news_list ul li').each(function(){															// 得到选中的个数
	    			if($(this).find('.unselected_icon').hasClass('selected_icon')){
	    				count++;
	    			}	
	    		});	
	    		if( count == 0 ){																				// 没有选中的按钮																		
	    			$('.news_delete').removeClass('news_delete_selected');										// 删除按钮背景色改变
	    		}
	    		if( count == $('.news_list ul li').length ){													// 全部选中
					$('.allselected').addClass('selected_icon');
				}else{
					$('.allselected').removeClass('selected_icon');
				}
	    	}else{
	    		$( this ).addClass('selected_icon');															// 未选中时，点击选中
	    		$('.news_list ul li').each(function(){
	    			if($(this).find('.unselected_icon').hasClass('selected_icon')){
	    				$('.news_delete').addClass('news_delete_selected');										// 添加按钮背景色改变
	    				count++;	   				
	    			}
	    		});	
	    		if(count == $('.news_list ul li').length){														// 若列表全选则底部全选按钮选中
					$('.allselected').addClass('selected_icon');
				}else{
					$('.allselected').removeClass('selected_icon');
				}
	    	}
    	}else{}
    	count=0;
    });
    
    //点击全选的时候
   $('.allselected').click( function(){														      				// 全选、反选
   		if( $(this).hasClass('selected_icon') ){																// 反选
	   	 	$(this).removeClass('selected_icon');
	   	 	$('.news_list').find('.unselected_icon').removeClass('selected_icon');								// 列表不能选中
	   	 	$('.news_delete').removeClass('news_delete_selected');												// 删除按钮背景色改变
	   	}else{																									// 全选																																									
	   	 	$(this).addClass('selected_icon');
	   	 	$('.news_list').find('.unselected_icon').addClass('selected_icon');	
	   	 	$('.news_delete').addClass('news_delete_selected');								     				// 删除按钮背景色改变
	   	}
   });

   $('.news_delete').click( function(){																			// 删除操作
   	if ( !$(this).hasClass('news_delete_selected')) {
            $.toast("选择需要删除的企业", "text");
        } else {
        	$.confirm({
				text: '您真的要删除选中的企业吗？',
				onOK: function() {
					var delectNum  = 0;                                             							// 删除企业种类数量
                    var selectedId = '';                                           								// 删除企业ID
                    var classify = $("#classify").val(); //收藏类别
                    $('.company_collect_list').each(function() {
                        if ( $(this).find('.unselected_icon').hasClass('selected_icon') ) {
                            selectedId += $(this).find('.unselected_icon').data('id').toString() + ',';                                             // 把企业的ID存入数组
                            $(this).remove();
                            delectNum--;  
                        }
                    });
                    url = "?g=app&m=index&a=favorite_delall";
                    $.post(url, {
						ids : selectedId,
						classify : classify
					}, function(data) {
						if (data.errno == 0) {
							$.toast(data.error, "text");
							setTimeout(function () {location.reload()},1000);
						} else {
							$.toast(data.error, "text");
						}
	
					}, 'json');
				},
				onCancel: function() {
					return false;
				}
			});
        	
//          layer.open({
//              content : '您真的要删除选中的企业吗？',
//              btn     : ["确定","取消"],
//              yes     : function( index ) {
//                  var delectNum  = 0;                                             							// 删除企业种类数量
//                  var selectedId = '';                                           								// 删除企业ID
//                  var classify = $("#classify").val(); //收藏类别
//                  $('.company_collect_list').each(function() {
//                      if ( $(this).find('.unselected_icon').hasClass('selected_icon') ) {
//                          selectedId += $(this).find('.unselected_icon').data('id').toString() + ',';                                             // 把企业的ID存入数组
//                          $(this).remove();
//                          delectNum--;  
//                      }
//                  });
//                  url = "?g=app&m=index&a=favorite_delall";
//                  $.post(url, {
//						ids : selectedId,
//						classify : classify
//					}, function(data) {
//						if (data.errno == 0) {
//							layer.open({
//								content:data.error
//							});
//							setTimeout(function () {location.reload()},1000);
//						} else {
//							layer.open({
//								content:data.error
//							});
//						}
//	
//					}, 'json');
////                    selectedId = selectedId.substr( 0, selectedId.length - 1 );    								// 删除企业ID去掉最后一个逗号
////                    layer.open({                                                  								// 弹窗提示
////               			content : '删除成功',              						    // selectedId为测试用，上线时请删除
////                		time    : 0
////            		});
//
//                  layer.close(index); 																		// 关闭当前弹窗
//              },
//              no      : function(){
//                  return false;
//              }
//          });
        }
    });
});