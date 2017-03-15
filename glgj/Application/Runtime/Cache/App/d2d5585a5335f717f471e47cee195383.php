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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/station_message.css" />
</head>
<body>
    <!-- 头部 -->
    <?php if(($appsign) != "1"): ?><header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title mgr50"><?php echo ($site_title); ?></p>
    </header><?php endif; ?>

    <!-- 无底部区域 -->
    <section class="no_footer">
        <?php if(!empty($list)): ?><div class="message_wrap">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><!-- 一条站内消息 -->
                    <div class="station_message_list">
                        <p class="station_message_list_time"><?php echo (date("Y-m-d H:i",$li["addtime"])); ?></p>
                        <div class="station_message_list_info">
                            <p class="station_message_list_title">系统消息</p>
                            <p class="station_message_list_con"><?php echo ($li["message"]); ?></p>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="loading">
                <img src="./Public/static/images/loading.gif" width="20" height="20" />
            </div>
            <?php else: ?>
            <!-- 数据为空时 -->
            <div class="common_empty">
                <img src="./Public/App/images/common_empty.png" width="120" height="120">
                <p>暂无消息</p>
                <?php if(($appsign) != "1"): ?><a href="javascript:history.go(-1);">返回</a><?php endif; ?>
            </div><?php endif; ?>
    </section>
    <script type="text/javascript">
        var ajaxFlag    = false;
    </script>
    <script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
    <script type="text/javascript" src="./Public/App/js/message.js"></script>
</body>
</html>