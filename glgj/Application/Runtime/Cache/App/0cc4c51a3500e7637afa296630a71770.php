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
    <link rel="stylesheet" type="text/css" href="./Public/static/css/carousel.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/news.css"/>
    <link rel="stylesheet" type="text/css" href="./Public/App/css/classify.css"/>
</head>
<body>
<!-- 头部 -->
<?php if(($appsign) != "1"): ?><header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title mgr50"><?php echo ($site_title); ?></p>
    </header><?php endif; ?>

<!-- 可视区域 -->
<div class="viewport">
    <div class="weui-navbar">
        <?php if(is_array($list['category'])): $i = 0; $__LIST__ = $list['category'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="weui-navbar__item <?php if(($_GET['cid']) == $vo["cid"]): ?>active<?php endif; ?>">
                <a href="?g=app&m=news&a=index&cid=<?php echo ($vo["cid"]); ?>"><?php echo ($vo["cname"]); ?></a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <?php if(!empty($list['banner'])): ?><!-- 轮播 -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if(is_array($list['banner'])): foreach($list['banner'] as $key=>$liv): ?><div class="swiper-slide">
                        <a href="<?php echo ((isset($liv['url']) && ($liv['url'] !== ""))?($liv['url']):'javascript:void(0);'); ?>"><img src="<?php echo ($liv['thumbnail']); ?>"
                                                                                   width="100%" height="auto"/></a>
                    </div><?php endforeach; endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <!-- <div class="swiper-mask">
                <p></p>
                <div class="swiper-pagination"></div>
            </div> -->
        </div><?php endif; ?>
    <?php if(!empty($list['article'])): ?><!-- 资讯1 -->
        <div class="news_wrap">
            <!-- 资讯列表 -->
            <div class="news_list">
                <ul>
                    <?php if(is_array($list['article'])): $i = 0; $__LIST__ = $list['article'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                            <a href="?g=app&m=news&a=detail&id=<?php echo ($vo['id']); ?>">
                                <?php if(!empty($vo["image"])): ?><img class="fl mgl16 delay" src="<?php echo ($vo['image']); ?>" data-echo="<?php echo ($vo['image']); ?>"
                                         width="90" height="70"/><?php endif; ?>
                                <div class="new_title">
                                    <p class="new_title_main"><?php echo ($vo['title']); ?></p>
                                    <p class="new_title_sub"><?php echo ($vo['short_title']); ?></p>
                                    <div class="opts">
                                        <a href="javascript:void(0)">
                                            <span class="comment">16</span>
                                        </a>
                                        <span class="collect">3</span>
                                        <span class="time"><?php echo ($vo['addtime']); ?></span>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
        <!-- 滑屏至底部提示信息 -->
        <div class="loading">
            <img src="./Public/static/images/loading.gif" width="20" height="20"/>
        </div>
        <?php else: ?>
        <!-- 数据为空时 -->
        <div class="common_empty">
            <img src="./Public/App/images/common_empty.png" width="120" height="120">
            <p>暂无资讯</p>
            <a href="?g=app&m=index">返回首页</a>
        </div><?php endif; ?>
</div>
<!-- 底部 -->
	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
<!-- 分类列表 开始 -->
<div class="classify_nav">
    <div class="classify_one">
        <a class="classify_close" href="javascript:void(0);">取消</a>
        <a class="classify_item" href="?g=app&m=news">全部</a>
        <?php if(!empty($category_list[0])): if(is_array($category_list[0])): foreach($category_list[0] as $key=>$val): if(!empty($category_list[$val['cid']])): ?><a class="classify_item" href="javascript:void(0);" data-cid="<?php echo ($val['cid']); ?>" data-flag="1"><?php echo ($val['cname']); ?></a>
                    <?php else: ?>
                    <a class="classify_item" href="?g=app&m=news&categoryid=<?php echo ($val['cid']); ?>" data-cid="<?php echo ($val['cid']); ?>"
                       data-flag="0"><?php echo ($val['cname']); ?></a><?php endif; endforeach; endif; ?>
            <?php else: ?>
            <a href="javascript:void(0);">暂无分类</a><?php endif; ?>
    </div>
    <div class="classify_two no">
        <a class="classify_close" href="javascript:void(0);">取消</a>
        <a class="back_one" href="javascript:void(0);">返回上级分类</a>
    </div>
    <div class="classify_three no">
        <a class="classify_close" href="javascript:void(0);">取消</a>
        <a class="back_two" href="javascript:void(0);">返回上级分类</a>
    </div>
</div>
<!-- 遮罩层 -->
<div class="mask"></div>
<!-- 分类列表 结束 -->
<script type="text/javascript">
    var title = "<?php echo ($_GET['title']); ?>";
    //var data 		= <?php echo ($category_arr); ?>;
    var cid = "<?php echo ($_GET['cid']); ?>";
    var ajaxFlag = false;
</script>
<script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./Public/static/js/carousel.js"></script>
<script type="text/javascript" src="./Public/App/js/news.js"></script>
<script type="text/javascript" src="./Public/App/js/classify.js"></script>
<script type="text/javascript" src="./Public/App/js/search.js"></script>
</body>
</html>