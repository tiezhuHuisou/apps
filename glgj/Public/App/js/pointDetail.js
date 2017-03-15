$(function(){
   var height = document.documentElement.clientHeight-$("header").height()-$('.weui-tab').height();
   $('#map').css('height',height);
   // 百度地图API功能
    var map = new BMap.Map("map");            // 创建Map实例
    map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);
     // 添加带有定位的导航控件
    var navigationControl = new BMap.NavigationControl({
        // 靠左上角位置
        anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
        // LARGE类型
        type: BMAP_NAVIGATION_CONTROL_SMALL,
        // 启用显示定位
        enableGeolocation: true
    });
    map.addControl(navigationControl);
    var local = new BMap.LocalSearch(map, {
        renderOptions: {map: map, panel: "r-result"}
    });
    local.search("餐饮");
})
