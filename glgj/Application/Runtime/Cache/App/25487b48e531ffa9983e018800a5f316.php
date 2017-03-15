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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/weui_rewrite.css"/>
	<link rel="stylesheet" type="text/css" href="./Public/App/css/news_collect.css" />
</head>
<body>
	<!-- 头部 -->
	<header>
        <a class="header_left" href="javascript:history.go(-1);">
            <div class="lt"><div></div></div>
        </a>
        <p class="header_title"><?php echo ($site_title); ?></p>
        <a class="header_right f15252" href="javascript:void(0);">编辑</a>
    </header>
	<!-- 无底部区域 -->
	<section class="no_footer">
        <!-- 资讯列表 -->
        <div class="news_list">
        	<input id="classify" type="hidden" value="1"/>
            <ul>
                <?php if(!empty($list['ordered'])): ?><div class="news_list">
                        <input id="classify" type="hidden" value="1"/>
                        <ul>
                            <?php if(is_array($list['ordered'])): $i = 0; $__LIST__ = $list['ordered'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                                    <div class="unselected_icon"></div>
                                    <div class="list_detail">
                                        <img src="<?php echo ($vo['iamge']); ?>" height="60" width="60"/>
                                        <div class="desc">
                                            <span><?php echo ($vo['title']); ?></span>
                                            <p><?php echo ($vo['addtime']); ?></p>
                                        </div>
                                    </div>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul>
                    </div><?php endif; ?>
            </ul>
        </div>

        

        <?php if(empty($list)): ?><!-- 收藏为空时 -->
        <div class="collect_empty_wrap ">
            <div class="common_empty"  style="display: none;">
                <img src="./Public/App/images/collect_empty.png" width="120" height="120" />
                <p>您的<?php echo ($site_title); ?>还是空的哦</p>
                <a href="?g=app&m=news">去收藏</a>
            </div>
        </div><?php endif; ?>
	</section>
    <footer>
        <div class="unselected_icon allselected"></div>
        <span>全选</span>
        <button class="news_delete" href="javascript:void(0);">删除</button>
    </footer>

    <script type="text/javascript" src="./Public/static/js/delay.js"></script>
    <script type="text/javascript" src="./Public/static/js/layer.js"></script>
    <script type="text/javascript" src="./Public/App/js/news_collect.js"></script>
</body>
</html>