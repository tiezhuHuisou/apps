$(function() {
    var editFlag         = false;       // 标记是否为
    var editIndex        = 0;           // 编辑元素的index值
    var calc_method_id;                 // 记录当前计价方式
    var firstFlag        = true;        // 第一次标记
    select_init();

    // var leftLen = ( $(document).width() - $("#select_area_add").width() ) * 0.5;
    // if ( leftLen < 0 ) {
    //     leftLen = 0;
    // }
    // $("#select_area_add").css({"top":"6px","left":leftLen});
    /*if ( $("#select_area_add").height() > $(window).height() ) {
        var leftLen = ( $(document).width() - $("#select_area_add").width() ) * 0.5;
        if ( leftLen < 0 ) {
            leftLen = 0;
        }
        $("#select_area_add").css({"top":"0","left":leftLen});
        alert("您的屏幕高度过小，建议更换屏幕高度更大的设备浏览，谢谢");
    } else {
        var leftLen = ( $(document).width() - $("#select_area_add").width() ) * 0.5;
        var topLen = ( $(window).height() - $("#select_area_add").height() ) * 0.5;
        if ( leftLen < 0 ) {
            leftLen = 0;
        }
        $("#select_area_add").css({"top":topLen,"left":leftLen});
    }*/
    // 修改进来的时候把已选中的地区隐藏
    if ( $("input[name='id']").val() ) {
        var selectPlaceA    = "";
        var selectPlaceCArr = $("#needHideC").val().split(",");
        var selectPlacePArr = $("#needHideP").val().split(",");
        $("input[class='placeValue']").each(function(i,n) {
            selectPlaceA += $(n).val();
        });
        selectPlaceA = selectPlaceA.substr(5);
        var selectPlaceAArr = selectPlaceA.split(",");
        var i = 0;
        // 隐藏已选区
        for ( i; i < selectPlaceAArr.length ; i++ ) {
            $("#all_place").find(".n" + selectPlaceAArr[i]).hide();
        }
        i = 0;
        // 隐藏已选市
        for ( i; i < selectPlaceCArr.length ; i++ ) {
            $("#all_place").find(".n" + selectPlaceCArr[i]).hide().children().hide();
        }
        i = 0;
        // 隐藏已选省
        for ( i; i < selectPlacePArr.length ; i++ ) {
            $("#all_place").find(".n" + selectPlacePArr[i]).hide().children().hide();
        }
    }
    // 判断默认计价方式
    if ( $("#package").attr("checked") ) {
        calc_method_id = "package";
    } else if ( $("#weight").attr("checked") ) {
        calc_method_id = "weight";
    } else {
        alert("非法操作！！！");
    }

    // 控制输入框区域高度与可配送区域高度一致，并居中（因为高度是变化的所以用margin-top）
    $(".courier").find(".select_price").each(function(i,n) {
        $(n).children("li").height( $(n).siblings(".add_area").height() );
        $(n).find("input").css( "margin-top", ( $(n).siblings(".add_area").height() - 32 ) * 0.5 );
    });

    /*------- 计价方式切换 开始 -------*/
    // 点击按件数上方的遮罩层
    $("body").on("click",".calc_method_package",function() {
        if ( calc_method_id == "weight" ) {
            $(".calc_method_confirm").show();
        }       
    });
    // 点击按重量上方的遮罩层
    $("body").on("click",".calc_method_weight",function() {
        if ( calc_method_id == "package" ) {
            $(".calc_method_confirm").show();
        }
    });
    // 点击确认框中的确认按钮
    $("body").on("click","#calc_method_confirm_yes",function() {
        if ( calc_method_id == "package" ) {
            $("#weight_box").click();
            $(".select_price").find("input").val("");
            calc_method_id = "weight";
        } else {
            $("#package_box").click();
            $(".select_price").find("input").val("");
            calc_method_id = "package";
        }
        $(".calc_method_confirm").hide();
    });
    // 点击确认框中的取消按钮
    $("body").on("click","#calc_method_confirm_no",function() {
        $(".calc_method_confirm").hide();
    });
    // 选择按重量
    $("body").on("click","#weight_box",function() {
            $(".c_header").find("li").eq(0).text("首重（千克）");
            $(".c_header").find("li").eq(2).text("续重（千克）");
            $(".c_add").remove();
            select_init();                      // 地区选择区域初始化
            editFlag  = false;                  // 标记为非编辑操作
    });
    // 选择按件数
    $("body").on("click","#package_box",function() {
            $(".c_header").find("li").eq(0).text("首件（个）");
            $(".c_header").find("li").eq(2).text("续件（个）");
            $(".c_add").remove();
            select_init();                      // 地区选择区域初始化
            editFlag  = false;                  // 标记为非编辑操作
    });
    /*------- 计价方式切换 结束 -------*/

    // 点击 省(+/-) 显示/隐藏 市
    $("body").on("click","#all_place .province_icon",function() {
        $(this).siblings(".city_left").toggle();
        if ( $(this).text() == "+" ) {
            $(this).text("-");
            $(this).css({"width":"4px","padding":"0 5px"});
        } else if ( $(this).text() == "-" ) {
            $(this).text("+");
            $(this).css({"width":"8px","padding":"0 3px"});
        } else {
            alert("非法操作！！！");
        }
    });
    
    // 点击 市(+/-) 显示/隐藏 区
    $("body").on("click","#all_place .city_icon",function() {
        $(this).siblings(".area_left").toggle();
        if ( $(this).text() == "+" ) {
            $(this).text("-");
            $(this).css({"width":"4px","padding":"0 5px"});
        } else if ( $(this).text() == "-" ) {
            $(this).text("+");
            $(this).css({"width":"8px","padding":"0 3px"});
        } else {
            alert("非法操作！！！");
        }
    });

    // 点击 省(名称) 改变 省/市/区 三者的样式
    $("body").on("click","#all_place .province_name",function() {
        if ( $(this).parent().is(".place_select_left") ) {  // 如果点击之前该省已被选中
            $(this).parent().removeClass("place_select_left").find(".municipal_left").removeClass("place_select_left").find(".area_name").removeClass("place_select_left");
            $(this).siblings(".province_icon").removeClass("icon_change").siblings(".city_left").find(".city_icon").removeClass("icon_change");
        } else {
            $(this).parent().addClass("place_select_left").find(".municipal_left").addClass("place_select_left").find(".area_name").addClass("place_select_left");
            $(this).siblings(".province_icon").addClass("icon_change").siblings(".city_left").find(".city_icon").addClass("icon_change");
        }
    });

    // 点击 市(名称) 改变 市/区 二者的样式
    $("body").on("click","#all_place .city_name",function() {
        var cityAllFlag = true;                             // 城市全选中标记变量
        var thisCity    = "." + $(this).parent().attr("class").split(" ")[1];
        if ( $(this).parent().is(".place_select_left") ) {  // 如果点击之前该城市已被选中
            $(this).parent().removeClass("place_select_left").find(".area_name").removeClass("place_select_left");
            $(this).siblings(".city_icon").removeClass("icon_change");
            $(this).parents(".province_left").removeClass("place_select_left").find(".province_icon").removeClass("icon_change");
        } else {
            $(this).parent().addClass("place_select_left").find(".area_name").addClass("place_select_left");
            $(this).siblings(".city_icon").addClass("icon_change");
        }
        // 判断该城市所属省份下的各城市是否全部选中（代码块放在这个位置相当于点击之后判断）
        $(this).parents(".city_left").children("li").each(function(i,n){
            if ( !$(n).is(".place_select_left") ) {         // 有一个没选中即为没有全部选中
                cityAllFlag = false;
            }
        });
        if ( cityAllFlag ) { // 如果该城市所属省份下的各城市全部选中，该城市所属省份也要改为被选中状态
            $(this).parents(".province_left").addClass("place_select_left").find(".province_icon").addClass("icon_change");
        }
    });

    // 点击 区(名称) 改变 区 样式
    $("body").on("click","#all_place .area_name",function() {
        var cityAllFlag = true;
        var areaAllFlag = true;
        if ( $(this).is(".place_select_left") ) {   // 如果该地区在点击之前已被选中
            $(this).removeClass("place_select_left").parents(".municipal_left").removeClass("place_select_left").parents(".province_left").removeClass("place_select_left");
            $(this).parent().siblings(".city_icon").removeClass("icon_change").parents(".city_left").siblings(".province_icon").removeClass("icon_change");
        } else {
            $(this).addClass("place_select_left");
        }
        // 判断该地区所属城市下的各地区是否全部选中（代码块放在这个位置相当于点击之后判断）
        $(this).parent().children("li").each(function(i,n){
            if ( !$(n).is(".place_select_left") ) { // 有一个没选中即为没有全部选中
                areaAllFlag = false;
            }
        });
        if ( areaAllFlag ) {    // 如果该地区所属城市下的各地区全部选中，该地区所属城市也要改为被选中，并且判断省级选中情况
            $(this).parents(".municipal_left").addClass("place_select_left").find(".city_icon").addClass("icon_change");
            // 判断市级选中情况，若全选则省级改为选中状态
            $(this).parents(".city_left").children("li").each(function(i,n){
                if ( !$(n).is(".place_select_left") ) { // 有一个没选中即为没有全部选中
                cityAllFlag = false;
                }
            });
            if ( cityAllFlag ) { // 如果城市所属省份下的各城市全部选中，该省份也要改为被选中状态
                $(this).parents(".province_left").addClass("place_select_left").find(".province_icon").addClass("icon_change");
            }
        }
    });

    // 显示选择区域模块
    $("body").on("click","#new_add",function() {
        $("#select_area_add").show();   // 显示地区选择区域     
        select_right_init();            // 地区选择区域右侧初始化
        editFlag = false;               // 标记为非编辑操作
    });

    // 隐藏选择区域模块
    $("body").on("click",".option_cancel",function() {
        $("#select_area_add").hide();   // 隐藏地区选择区域
        editFlag = false;               // 标记为非编辑操作
    });

    // 点击移动将选中区域添加到右边
    $("body").on("click",".region_add",function() {
        // 锁定符号为-号
        $("#all_place_right .province_icon").css({"width":"4px","padding":"0 5px"});
        $("#all_place_right .city_icon").css({"width":"4px","padding":"0 5px"});
        $("#all_place_right .province_icon").text("-");
        $("#all_place_right .city_icon").text("-");
        // 所有选中省移动
        $("#all_place").find(".province_left").each(function(i,n) {
            if ( $(n).is(".place_select_left") ) {
                var provinceN = "." + $(n).attr("class").split(" ")[1];
                $("#all_place").find(n).removeClass("place_select_left").find(".municipal_left").removeClass("place_select_left").find(".area_name").removeClass("place_select_left");
                $("#all_place").find(provinceN).find("*").hide();
                $("#all_place_right").children(provinceN).show();
                $("#all_place_right").children(provinceN).find("*").show();
            }
        });
        // 所有选中市移动
        $("#all_place").find(".municipal_left").each(function(i,n) {
            if ( $(n).is(".place_select_left") && !$(n).parents(".province_left").is(".place_select_left") ) {
                var provinceN   = "." + $(n).parents(".province_left").attr("class").split(" ")[1];
                var cityN       = "." + $(n).attr("class").split(" ")[1];
                var cityAllFlag = true;
                $("#all_place").find(cityN).find("*").hide();
                $("#all_place").find(cityN).removeClass("place_select_left").find(".area_name").removeClass("place_select_left");
                $("#all_place_right").find(provinceN).show().children().show();
                $("#all_place_right").find(cityN).show();
                $("#all_place_right").find(cityN).find("*").show();
            }
        });
        // 所有选中区移动
        $("#all_place").find(".area_name").each(function(i,n) {
            if ( $(n).is(".place_select_left") && !$(n).parents(".municipal_left").is(".place_select_left") && !$(n).parents(".province_left").is(".place_select_left") ) {
                var provinceN = "." + $(n).parents(".province_left").attr("class").split(" ")[1];
                var cityN     = "." + $(n).parents(".municipal_left").attr("class").split(" ")[1];
                var areaN     = "." + $(n).attr("class").split(" ")[1];
                $("#all_place").find(areaN).hide();
                $("#all_place").find(areaN).removeClass("place_select_left");
                $("#all_place_right").find(provinceN).show().children().show();
                $("#all_place_right").find(cityN).show().children().show();
                $("#all_place_right").find(areaN).show();
                $("#all_place_right").find(areaN).children("i").show();
            }
        });
    });

    // 点击叉叉将右边信息删除
    $("body").on("click",".place_delect",function() {
        var numN          = "." + $(this).parent().attr("class").split(" ")[1];
        var placeN        = "." + $(this).parent().attr("class").split(" ")[0];
        var allCityDelect = true;
        var allAreaDelect = true;
        $("#all_place_right").find(numN).hide();
        $("#all_place_right").find(numN).find("*").hide();
        if ( placeN == ".municipal_left" ) {    // 删除市
            $("#all_place").find(numN).show().find("*").show();
            $("#all_place").find(numN).show().children("ul").hide();
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).find("i").text("+").css({"width":"8px","padding":"0 3px"}).removeClass("icon_change");
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).show().children().show();
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).children("ul").hide();
            $(this).parents(".city_left").children(".municipal_left").each(function(i,n) {
                if ( !$(n).is(":hidden") ) {
                    allCityDelect = false;
                }
            });
            if ( allCityDelect ) { // 删除所有选中城市
                $(this).parents(".province_left").hide();
                $(this).parents(".province_left").children().hide();
            }
        } else if ( placeN == ".area_name" ) {  // 删除区
            $("#all_place").find(numN).show().children().show();
            $("#all_place").find("." + $(this).parents(".municipal_left").attr("class").split(" ")[1]).show().children().show();
            $("#all_place").find("." + $(this).parents(".municipal_left").attr("class").split(" ")[1]).children("ul").hide();
            $("#all_place").find("." + $(this).parents(".municipal_left").attr("class").split(" ")[1]).children("i").removeClass("icon_change").text("+").css({"width":"8px","padding":"0 3px"});
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).show().children().show();
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).children("ul").hide();
            $("#all_place").find("." + $(this).parents(".province_left").attr("class").split(" ")[1]).children("i").removeClass("icon_change").text("+").css({"width":"8px","padding":"0 3px"});
            $(this).parents(".area_left").children(".area_name").each(function(i,n) {
                if ( !$(n).is(":hidden") ) {
                    allAreaDelect = false;
                }
            });
            if ( allAreaDelect ) {
                $(this).parents(".municipal_left").hide();
                $(this).parents(".municipal_left").children().hide();
            }
            $(this).parents(".city_left").children(".municipal_left").each(function(i,n) {
                if ( !$(n).is(":hidden") ) {
                    allCityDelect = false;
                }
            });
            if ( allCityDelect ) {
                $(this).parents(".province_left").hide();
                $(this).parents(".province_left").children().hide();
            }
        } else {                                // 删除省
            $("#all_place").find(numN).show();
            $("#all_place").find(numN).find("*").show();
            $("#all_place").find(numN).find("ul").hide();
            $("#all_place").find("i").removeClass("icon_change");
            $("#all_place").find("i").text("+");
            $("#all_place").find("i").css({"width":"8px","padding":"0 3px"});
        }
    });

    // 点击确定
    $("body").on("click",".option_confirm",function() {
        var infoArr     = [];
        var num         = 0;
        var infoId      = "";
        var placeAllId  = "";
        // 获取新增配送区域文字信息：tempP->html字符串;placeAllId->入库字符串
        $("#all_place_right").children(".province_left").each(function(i,n) {
            if ( !$(n).is(":hidden") ) {
                var tempP = $(n).children("span").text() + "（";
                $(n).children(".city_left").children(".municipal_left").each(function(j,m) {
                    if ( !$(m).is(":hidden") ) {
                        tempP += $(m).children("span").text() + "：" ;
                        if ( $(m).find(".area_name").length == 0 ) {
                            tempP       += $(m).children("span").text() + "，" ;
                            //infoId        += $(m).attr("id") + ",";
                            var tempStr = $(m).attr("class").split(" ")[1];
                            placeAllId += tempStr.substr( 1, tempStr.length - 1 ) + ",";
                        } else {
                            $(m).children(".area_left").children(".area_name").each(function(k,o) {
                                if ( !$(o).is(":hidden") ) {
                                    tempP       += $(o).text().substr( 0, $(o).text().length - 1 ) + "，" ;
                                    infoId      += $(o).attr("id") + ",";
                                    placeAllId  += $(o).attr("id").split("_")[3] + ",";
                                }
                            });
                        }
                        tempP = tempP.substr( 0, tempP.length - 1 ) + "；";
                    }
                });
                tempP  = tempP.substr( 0, tempP.length - 1 );
                tempP += "）<br /><br />";
                infoArr[num] = tempP;
                num ++;
            }
        });
        var infoTxt = "";
        var i       = 0;
        if ( infoArr.length == 0 ) {
            infoTxt = "<span>未添加地区</span>";
        } else {
            for ( i; i < infoArr.length ; i++ ) {
                infoTxt += "<span>" + infoArr[i] + "</span>";
            }
            infoId      = infoId.substr( 0 , infoId.length - 1 );
            placeAllId  = placeAllId.substr( 0 , placeAllId.length - 1 );
        }
        if ( editFlag ) {
            $(".c_add").eq( editIndex ).find(".add_area_w").html( infoTxt + '<a class="add_area_delect" href="javascript:void(0);">删除</a><a class="add_area_edit" href="javascript:void(0);">编辑</a>' );
            $(".c_add").eq( editIndex ).children("input").eq(0).attr( "value", infoId );
            $(".c_add").eq( editIndex ).children("#placeCatId").attr( "value", placeAllId );
            editFlag = false;
        } else {
            var htmlAdd = '<div class="c_add"><div class="add_area"><div class="add_area_w">' + infoTxt + '<a class="add_area_delect" href="javascript:void(0);">删除</a><a class="add_area_edit" href="javascript:void(0);">编辑</a></div></div><ul class="select_price"><li><input name="package_first[]" type="text" datatype="n" nullmsg="请输入数字" errormsg="请输入数字" /></li><li><input name="freight_first[]" type="text" datatype="n" nullmsg="请输入数字" errormsg="请输入数字" /></li><li><input name="package_other[]" type="text" datatype="n" nullmsg="请输入数字" errormsg="请输入数字" /></li><li><input name="freight_other[]" type="text" datatype="n" nullmsg="请输入数字" errormsg="请输入数字" /></li></ul><input name="placeId[]" type="hidden" value="' + infoId + '" /><input id="placeCatId" class="placeValue" name="placeAllId[]" type="hidden" value="' + placeAllId + '" /></div>';
            $(".c_default").before( htmlAdd );
        }
        // 垂直居中
        $(".courier").find(".select_price").each(function(i,n) {
            $(n).children("li").height( $(n).siblings(".add_area").height() );
            $(n).find("input").css( "margin-top", ( $(n).siblings(".add_area").height() - 32 ) * 0.5 );
        });
        // 关闭选择区域对话框
        $("#select_area_add").hide();
        // 右侧初始化
        $("#all_place_right").find("*").hide();
        $("#all_place_right .province_icon").css({"width":"4px","padding":"0 5px"});
        $("#all_place_right .city_icon").css({"width":"4px","padding":"0 5px"});
        $("#all_place_right .province_icon").text("-");
        $("#all_place_right .city_icon").text("-");
    });

    // 删除配送区域
    $("body").on("click",".add_area_delect",function() {
        var placeId = $(this).parents(".c_add").children("#placeCatId").val();  // 获取所有当前配送区id字符串
        if ( placeId != "" ) {
            var placeIdArry = placeId.split(",");                               // 获取所有当前配送区域所以的区数组
            var i             = 0;
            // 左侧显示被删除区域信息
            for ( i; i < placeIdArry.length ; i++ ) {                           // 遍历所有当前配送区域所以的区
                var areaObj = $("#all_place").find(".n" + placeIdArry[i]);      // 获取当前区对象
                // 显示区
                areaObj.removeClass("place_select_left").show();
                // 显示市
                areaObj.parents(".municipal_left").removeClass("place_select_left").show();
                areaObj.parents(".municipal_left").children("span").show();
                areaObj.parents(".municipal_left").children("i").removeClass("icon_change").text("+").css({"width":"8px","padding":"0 3px"}).show();
                // 显示省
                areaObj.parents(".province_left").removeClass("place_select_left").show();
                areaObj.parents(".province_left").children("span").show();
                areaObj.parents(".province_left").children("i").removeClass("icon_change").text("+").css({"width":"8px","padding":"0 3px"}).show();
            }
        }
        $(this).parents(".c_add").remove();
    });

    // 编辑新增配送区域
    $("body").on("click",".add_area_edit",function() {
        if ( firstFlag ) {                                                          // 第一次加载地区信息
            /*$("#loading").show();                                                 // 显示数据加载中...文字提示信息
            setTimeout(function() {                                                 // 延时函数
                loadInfoLeft( data );                                               // 获取citytree.js里json格式的数据[省、城、区(县)]————左侧
                loadInfoRight( data );                                              // 获取citytree.js里json格式的数据[省、城、区(县)]————右侧
                select_init();                                                      // 地区选择区域初始化
                $("#loading").hide();                                               // 信息加载完毕隐藏加载中提示信息
                firstFlag = false;                                                  // 标记为非第一次加载地区信息
            },100); */                                                              // 延时0.1秒
        } else {
            select_right_init();                                                    // 右侧初始化
        }
        editFlag    = true;                                                         // 标记为编辑操作
        editIndex   = $(this).parents(".c_add").index() - 2;                        // 获取当前大div的index值
        $("#select_area_add").show();                                               // 显示选择地区选择框
        var placeId = $(this).parents(".c_add").children("#placeCatId").val();      // 获取所有当前配送区id字符串
        if ( placeId != "" ) {
            var placeIdArry = placeId.split(",");                                   // 获取所有当前配送区域所以的区数组
            var i             = 0;
            // 右侧显示已选择区信息
            for ( i; i < placeIdArry.length ; i++ ) {                               // 遍历所有当前配送区域所以的区
                var areaObj = $("#all_place_right").find(".n" + placeIdArry[i]);    // 获取当前区对象
                // 显示区
                areaObj.show().children().show();
                // 显示市
                areaObj.parents(".municipal_left").show().children().show();
                areaObj.parents(".municipal_left").children(".city_icon").removeClass("icon_change").text("-").css({"width":"4px","padding":"0 5px"});
                // 显示省
                areaObj.parents(".province_left").show().children().show();
                areaObj.parents(".province_left").children(".province_icon").removeClass("icon_change").text("-").css({"width":"4px","padding":"0 5px"});
            }
            // 隐藏左侧信息
        }
    });

});

// 获取省市区信息(左)———— 通过不断的在$("#all_place")对象后面添加html代码实现前端呈现效果
/*function loadInfoLeft( data ) {
    var provinceArr = data.provinceList;                // 省数组
    var cityArr     = data.cityList;                    // 市数组
    var countryArr  = data.countyList;                  // 区数组
    var num         = 0;                                // 记录第N次输出数据(确保条数据具有独立特征)
    for ( var i = 0; i < provinceArr.length; i++ ) {    // 循环输出省
        num ++;
        var provinceHtml = '<li class="province_left n' + num + '"><i class="province_icon">+</i><span class="province_name">' + provinceArr[i].name + '</span><ul class="city_left province'+ provinceArr[i].id +'"></ul></li>';
        $("#all_place").append( provinceHtml );
        for ( var j = 0; j < cityArr["city_"+provinceArr[i].id].length; j++ ) {     // 循环输出市
            num ++;
            var cityHtml = '<li class="municipal_left n' + num + '"><i class="city_icon">+</i><span class="city_name">' + cityArr["city_"+provinceArr[i].id][j].name + '</span><ul class="area_left city' + cityArr["city_"+provinceArr[i].id][j].id + '"></ul></li>';
            $("#all_place .province"+provinceArr[i].id).append( cityHtml );
            if ( countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id] ) {   // 如果市下面没有区
                for ( var k = 0; k < countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id].length; k++ ) {   // 循环输出区
                    num ++;
                    var areaHtml = '<li class="area_name n' + num + ' _' + provinceArr[i].id + '_' + cityArr["city_"+provinceArr[i].id][j].id + '_' + countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id][k].id + '">' + countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id][k].name + '</li>';
                    $("#all_place .city"+cityArr["city_"+provinceArr[i].id][j].id).append( areaHtml );
                }
            }       
        }
    }
}*/

// 获取省市区信息(右)
/*function loadInfoRight( data ) {
    var provinceArr = data.provinceList;
    var cityArr     = data.cityList;
    var countryArr  = data.countyList;
    var num         = 0;
    for ( var i = 0; i < provinceArr.length; i++ ) {
        num ++;
        var provinceHtml = '<li class="province_left n' + num + '"><i class="province_icon">-</i><span id="' + provinceArr[i].id + '" class="province_name">' + provinceArr[i].name + '</span><i class="place_delect">×</i><ul class="city_left province'+ provinceArr[i].id +'"></ul></li>';
        $("#all_place_right").append( provinceHtml );
        for ( var j = 0; j < cityArr["city_"+provinceArr[i].id].length; j++ ) {
            num ++;
            var cityHtml = '<li class="municipal_left n' + num + '"><i class="city_icon">-</i><span id="' + cityArr["city_"+provinceArr[i].id][j].id + '" class="city_name">' + cityArr["city_"+provinceArr[i].id][j].name + '</span><i class="place_delect">×</i><ul class="area_left city' + cityArr["city_"+provinceArr[i].id][j].id + '"></ul></li>';
            $("#all_place_right .province"+provinceArr[i].id).append( cityHtml );
            if ( countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id] ) {
                for ( var k = 0; k < countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id].length; k++ ) {
                    num ++;
                    var areaHtml = '<li id="_' + provinceArr[i].id + '_' + cityArr["city_"+provinceArr[i].id][j].id + '_' + countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id][k].id + '" class="area_name n' + num + '">' + countryArr["city_"+cityArr["city_"+provinceArr[i].id][j].id][k].name + '<i class="place_delect">×</i></li>';
                    $("#all_place_right .city"+cityArr["city_"+provinceArr[i].id][j].id).append( areaHtml );
                }
            }       
        }
    }
}*/

// 地区选择区域初始化
function select_init() {
    select_left_init();
    select_right_init();
}

// 地区选择区域左侧侧初始化
function select_left_init() {
    $("#all_place").find("*").show();
    $("#all_place").find("ul").hide().removeClass("place_select_left");
    $("#all_place").find("i").text("+").removeClass("icon_change");
    $("#all_place").find("i").css({"width":"8px","padding":"0 3px"});
    $("#all_place").find(".area_name").removeClass("place_select_left");
}

// 地区选择区域右侧初始化
function select_right_init() {
    $("#all_place_right").find("*").hide();
    $("#all_place_right .province_icon").css({"width":"4px","padding":"0 5px"});
    $("#all_place_right .city_icon").css({"width":"4px","padding":"0 5px"});
    $("#all_place_right .province_icon").text("-");
    $("#all_place_right .city_icon").text("-");
}