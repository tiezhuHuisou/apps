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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/announce.css"/>
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
    <?php if(!empty($list)): ?><div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="?g=app&m=product&a=announceDetail&id=<?php echo ($vo["id"]); ?>">
                        <div class="weui-media-box weui-media-box_text">
                            <span class="doit green_38d163">•</span>
                            <div class="content_desc">
                                <p class="weui-media-box__desc read_aleady"><?php echo ($vo["title"]); ?></p>
                                <p class="weui-media-box__desc read_aleady"><?php echo (date("Y-m-d",$vo["addtime"])); ?></p>
                            </div>
                        </div>
                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <!-- 滑屏至底部提示信息 -->
        <div class="loading">
            <img src="./Public/static/images/loading.gif" width="20" height="20"/>
        </div>
        <?php else: ?>
        <!--数据为空时-->
        <div class="common_empty">
            <img src="./Public/App/images/common_empty.png" width="120" height="120">
            <p>暂无暂无公告信息</p>
            <a href="?g=app&m=index">返回首页</a>
        </div><?php endif; ?>
    <input type="hidden" class="classify_nav" value="{'没有了'|default=0}">
</section>
<script type="text/javascript">
    var title = "<?php echo ($_GET['title']); ?>";
    var ajaxFlag = false;

</script>
<script type="text/javascript" src="./Public/App/js/announce.js"></script>
</body>
</html>