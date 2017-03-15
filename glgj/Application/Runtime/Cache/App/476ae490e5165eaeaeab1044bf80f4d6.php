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
    <link rel="stylesheet" type="text/css" href="./Public/static/css/carousel.css" />
    <link rel="stylesheet" type="text/css" href="./Public/App/css/index.css" />
</head>
<body>
    <!-- 顶部 开始 -->
    <div class="header">
        <div class="weui-flex header_wrap">
            <!-- 定位 -->
            <div class="gps"></div>
            <!-- 标题 -->
            <div class="weui-flex__item">首页</div>
            <!-- 占位 -->
            <div class="space"></div>
        </div>
        <div class="weui-search-bar" id="searchBar">
            <form class="weui-search-bar__form">
                <div class="weui-search-bar__box">
                    <i class="weui-icon-search"></i>
                    <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="">
                    <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                </div>
                <label class="weui-search-bar__label" id="searchText">
                    <i class="weui-icon-search"></i>
                    <span>搜索</span>
                </label>
            </form>
            <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
        </div>
    </div>
    <!-- 顶部 结束 -->
    <!-- 可视区域 开始 -->
    <section class="viewport">
        <!-- 导航 -->
        <div class="weui-grids nav_wrap">
            <a href="?g=app&m=member&a=publishGoods" class="js_release weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n1.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">发布货源</p>
            </a>
            <a href="?g=app&m=member&a=publishTruck" class="js_release weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n2.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">发布车源</p>
            </a>
            <a href="?g=app&m=member&a=depot" class="js_release weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n3.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">发布仓储</p>
            </a>
            <a href="?g=app&m=member&a=identity" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n4.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">身份验证</p>
            </a>
            <a href="javascript:void(0);" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n5.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">查询货物</p>
            </a>
            <a href="javascript:void(0);" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n6.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">查询货车</p>
            </a>
            <a href="javascript:void(0);" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n7.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">查询仓储</p>
            </a>
            <a href="?g=app&m=more&a=cooperation" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/n8.png" width="60" height="60" />
                </div>
                <p class="weui-grid__label">商务合作</p>
            </a>
        </div>
        <!-- 轮播 开始 -->
        <div class="swiper_auto">
            <div class="swiper-container">
                <div class="swiper-wrapper">

                    <?php if(is_array($list['banner'])): $i = 0; $__LIST__ = $list['banner'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                        <a href="<?php echo ($vo['url']); ?>">
                            <img src="<?php echo ($vo['thumbnail']); ?>" width="100%" />
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>

                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- 轮播 结束 -->
        <!-- 快讯 开始 -->
        <div class="report_wrap">
            <img src="./Public/App/images/report_img.png" width="50" height="16" />
            <span class="report_line"></span>
            <div class="report_title">
                <ul class="list">
                    <?php if(!empty($list['articleToday'])): if(is_array($list['articleToday'])): $i = 0; $__LIST__ = $list['articleToday'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="?g=app&m=news&a=detail&id=<?php echo ($vo["id"]); ?>"><span>最新</span><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                        <?php else: ?>
                        <li><a href="javascript:void(0);">暂无快报</a></li><?php endif; ?>
                </ul>
            </div>
            <span class="larger"></span>
        </div>
        <!-- 快讯 结束 -->
        <!-- 热门公司 -->
        <a class="weui-cells mgt10" href="?g=app&m=company">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>推荐物流</p>
                </div>
                <div class="weui-cell__ft">更多</div>
            </div>
        </a>
        <div class="swiper_company">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php if(is_array($list['flags_company'])): $i = 0; $__LIST__ = $list['flags_company'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                        <a href="?g=app&m=company&a=detail&id=<?php echo ($vo["id"]); ?>">
                            <div class="img_wrap">
                                <img src="<?php echo ($vo["logo"]); ?>" width="100%" />
                            </div>
                            <p class="items_title"><?php echo ($vo["name"]); ?></p>
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>
        <!-- 资讯 -->
        <div class="weui-panel weui-panel_access">
            <a class="weui-cells" href="?g=app&m=news">
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>推荐资讯</p>
                    </div>
                    <div class="weui-cell__ft">更多</div>
                </div>
            </a>
            <div class="weui-panel__bd">
                <?php if(is_array($list['article'])): $i = 0; $__LIST__ = $list['article'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="?g=app&m=news&a=detail&id=<?php echo ($vo["id"]); ?>" class="weui-media-box weui-media-box_appmsg">
                    <div class="weui-media-box__hd">
                        <img class="weui-media-box__thumb" src="<?php echo ($vo["image"]); ?>" />
                    </div>
                    <div class="weui-media-box__bd">
                        <p class="weui-media-box__desc"><?php echo ($vo["title"]); ?></p>
                        <p class="weui-media-box__title"><?php echo ($vo["addtime"]); ?></p>
                    </div>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <!-- 最新加入 -->
        <a class="weui-cells mgt10" href="?g=app&m=company&调到公司分类">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <p>最新加入</p>
                </div>
                <div class="weui-cell__ft">更多</div>
            </div>
        </a>
        <div class="swiper_newJoin">
            <div class="swiper-container">
                <div class="swiper-wrapper">

                    <?php if(is_array($list['company'])): $i = 0; $__LIST__ = $list['company'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="swiper-slide">
                        <a href="?g=app&m=company&a=detail&id=<?php echo ($vo["id"]); ?>">
                            <div class="img_wrap">
                                <img src="<?php echo ($vo["logo"]); ?>" width="100%" />
                            </div>
                            <p class="items_title"><?php echo ($vo["name"]); ?></p>
                        </a>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>

                </div>
            </div>
        </div>
    </section>
    <!-- 可视区域 结束 -->
    <!-- 底部 开始 -->
    	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
    <!-- 底部 结束 -->
    <script type="text/javascript">
        var city_name  = "<?php echo ($city_name); ?>";
        var defaultImg = './Public/static/images/default_icon.jpg';
        var imgUrl     = './Public/App/images';
    </script>
    <!-- <script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script> -->
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=xA4XirsaKbnL1fnErmlvfk4I"></script>
    <script type="text/javascript" src="./Public/static/js/carousel.js"></script>
    <script type="text/javascript" src="./Public/App/js/index.js"></script>
</body>
</html>