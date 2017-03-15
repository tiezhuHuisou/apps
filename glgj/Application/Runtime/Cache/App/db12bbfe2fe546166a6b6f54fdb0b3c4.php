<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="wap-font-scale" content="no" />
	<meta name="Keywords" content="<?php echo ($site_keywords); ?>" />
	<meta name="Description" content="<?php echo ($site_description); ?>" />
	<title><?php echo ($site_title); ?></title>
	<link rel="stylesheet" type="text/css" href="./Public/static/css/base_red.css" />
	<link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-weui/1.0.1/css/jquery-weui.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/find.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/truckDetail.css"/>
</head>
<body>
    <?php if(($appsign) != "1"): ?><header>
    <a class="header_left" href="javascript:history.go(-1);">
        <div class="lt">
            <div></div>
        </div>
    </a>
    <p class="header_title"><?php echo ($site_title); ?></p>
    <div class="top_nav"></div>
</header><?php endif; ?>
    <!-- 可视区域 开始 -->
    <section class="viewport">
        <!-- 头部区域 -->
        <div class="weui-flex area">
            <div class="weui-flex__item">浙江金华</div>
            <div><img src="./Public/App/images/side_to.png"></div>
            <div class="weui-flex__item">江苏淮安</div>
        </div>
        <div class="weui-panel">
            <div class="weui-panel__bd">
                <div class="weui-media-box weui-media-box_small-appmsg">
                    <div class="weui-cells">
                        <a class="weui-cell weui-cell_access" href="javascript:;">
                            <div class="weui-cell__hd">
                                <img src="https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1487821101335&di=758aead12f07295fe2152f3bd27f68c6&imgtype=0&src=http%3A%2F%2Ff.hiphotos.baidu.com%2Fzhidao%2Fpic%2Fitem%2Fadaf2edda3cc7cd960628dc03901213fb80e9158.jpg" alt="">
                            </div>
                            <div class="weui-cell__bd weui-cell_primary">
                                <span>魏先生</span>
                                <img src="./Public/App/images/dh.png">
                                <span class="desc">查看该车主其他路线</span>
                            </div>
                            <span class="weui-cell__ft"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- 头部区域 -->
        <!-- 详细信息区域 -->
        <div class="detail">
            <div class="detail_title">详细信息</div>
            <div class="weui-tab">
                <div class="weui-navbar">
                    <a href="javascript:;" class="weui-tabbar__item">
                        <div class="weui-tabbar__icon">
                            车辆类型
                        </div>
                        <p class="weui-tabbar__label">返程</p>
                    </a>
                    <a href="javascript:;" class="weui-tabbar__item">
                        <div class="weui-tabbar__icon">
                            车辆数量
                        </div>
                        <p class="weui-tabbar__label">1辆</p>
                    </a>
                    <a href="javascript:;" class="weui-tabbar__item">
                        <div class="weui-tabbar__icon">
                            车辆长度
                        </div>
                        <p class="weui-tabbar__label">13.0m</p>
                    </a>
                    <a href="javascript:;" class="weui-tabbar__item">
                        <div class="weui-tabbar__icon">
                            车辆类型
                        </div>
                        <p class="weui-tabbar__label">高栏车</p>
                    </a>
                </div>
            </div>
            <div class="desc_list">
                <div class="desc_item">
                    <span class="desc_title">可载轻货:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">可载重货:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">发车时间:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">途径地区:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">有效时间:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">车牌号码:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">更新时间:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
                <div class="desc_item">
                    <span class="desc_title">备注:</span>
                    <span class="desc_detail">车主未填写此信息</span> 
                </div>
            </div>
        </div>
    </section>
</body>
</html>