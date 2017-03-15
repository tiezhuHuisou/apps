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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/company.css">
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
    <div class="weui-tab classify_nav" type="hidden">
        <div class="weui-navbar">
            <div class="weui-navbar">
                <?php if(is_array($list['category'])): $i = 0; $__LIST__ = $list['category'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="weui-navbar__item <?php if(($_GET['cid']) == $vo["id"]): ?>active<?php endif; ?>">
                        <a href="?g=app&m=company&a=index&cid=<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <!-- 列表页 -->
    <?php if(!empty($list['list'])): ?><div class="list_wrap">
            <?php if(is_array($list['list'])): $i = 0; $__LIST__ = $list['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="?g=app&m=company&a=detail&id=<?php echo ($vo["id"]); ?>">
                    <div class="weui-panel weui-panel_access">
                        <h2 class="weui-panel__hd"><?php echo ($vo["name"]); ?></h2>
                        <div class="weui-panel__bd">
                            <div class="weui-media-box weui-media-box_appmsg">
                                <div class="weui-media-box__bd">
                                    <p class="weui-media-box__desc">联系人：<span><?php echo ($vo["contact_user"]); ?></span></p>
                                    <p class="weui-media-box__desc">
                                    <span class="phone">电话：<?php echo ($vo["telephone"]); ?><a href="tel:<?php echo ($vo["telephone"]); ?>"><img
                                            src="./Public/App/images/dh.png"></a></span>
                                        <span class="phone">手机：<?php echo ($vo["subphone"]); ?><a href="tel:<?php echo ($vo["subphone"]); ?>"><img
                                                src="./Public/App/images/dh.png"></a></span>
                                    </p>
                                    <p class="weui-media-box__desc"><?php echo ($vo["address"]); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!-- 滑屏至底部提示信息 -->
        <div class="loading">
            <img src="./Public/static/images/loading.gif" width="20" height="20"/>
        </div>
        <?php else: ?>
        <!-- 数据为空时 -->
        <div class="common_empty">
            <img src="./Public/App/images/common_empty.png" width="120" height="120">
            <p>暂无暂无企业</p>
            <a href="?g=app&m=index">返回首页</a>
        </div><?php endif; ?>
    <input type="hidden" class="classify_nav" value="{'没有了'|default=0}">
</section>

	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
<script type="text/javascript">
    var title = "<?php echo ($_GET['title']); ?>";
    var cid = "<?php echo ($_GET['cid']); ?>";
    var ajaxFlag = false;

</script>
<script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./Public/static/js/carousel.js"></script>
<script type="text/javascript" src="./Public/App/js/company.js"></script>

</body>
</html>