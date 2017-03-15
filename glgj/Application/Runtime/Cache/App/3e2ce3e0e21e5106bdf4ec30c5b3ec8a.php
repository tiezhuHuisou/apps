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
    <link rel="stylesheet" type="text/css" href="./Public/App/css/member.css"/>
</head>
<body>
<!-- 主体内容 -->
<section class="member_wrapper no_header">
    <!-- 顶部 -->
    <div class="member_top">
        <!-- 顶部图片 开始 -->
        <img src="./Public/App/images/member_top_bg.png" width="100%"/>
        <!-- 顶部图片 结束 -->
        <!-- 顶部图片 结束 -->
        <!-- 顶部头像、昵称 结束 -->
        <div class="member_top_pn">
            <!-- 顶部头像 开始 -->
            <a class="member_top_sculpture" href="?g=app&m=member&a=info">
                <img class="delay" src="./Public/App/images/pixel.gif"
                     data-echo="<?php echo ((isset($list["memberInfo"]["head_pic"]) && ($list["memberInfo"]["head_pic"] !== ""))?($list["memberInfo"]["head_pic"]):'./Public/App/images/member_default.png'); ?>" width="60"
                     height="60"/>
                <!-- 顶部头像 结束 -->
            </a>
            <!-- 顶部昵称 开始 -->
            <p class="member_top_nickname"><?php echo ($list["memberInfo"]["name"]); ?></p>
            <!-- 顶部昵称 结束 -->
        </div>
        <!-- 顶部头像、昵称 结束 -->
    </div>
    <!-- 顶部 -->
    <!--我的发布-->
    <div class="weui-cells__title">我的发布</div>
    <div class="grid_block release">
        <div class="weui-grids member_collect">
            <a href="?g=app&m=member&a=truck" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/wdhc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>我的车源</span></p>
            </a>
            <a href="?g=app&m=member&a=depot" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/wdck@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>我的仓储</span></p>
            </a>
            <a href="?g=app&m=member&a=goods" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/wdhw@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>我的货物</span></p>
            </a>
        </div>
    </div>
    <!--我的发布结束-->
    <!--我的收藏-->
    <div class="weui-cells__title">我的收藏</div>
    <div class="grid_block collection">
        <div class="weui-grids member_collect">
            <a href="?g=app&m=member&a=company_collect" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/qysc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>企业收藏</span></p>
            </a>
            <a href="?g=app&m=member&a=news_collect" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/zzxsc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>资讯收藏</span></p>
            </a>
            <a href="?g=app&m=member&a=truckList" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/hcsc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>车源收藏</span></p>
            </a>
            <a href="?g=app&m=member&a=goodsList" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/hwsc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>货物收藏</span></p>
            </a>
            <a href="?g=app&m=member&a=depotList" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/ccsc@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>仓储收藏</span></p>
            </a>
        </div>
    </div>
    <!--我的收藏结束-->
    <!--更多-->
    <div class="weui-cells__title">更多</div>
    <div class="grid_block more">
        <div class="weui-grids member_collect">
            <a href="?g=app&m=member&a=invite_friends" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/yqhy@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>邀请好友</span></p>
            </a>
            <a href="?g=app&m=product&a=announce" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/ggxx@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>公告信息</span></p>
            </a>
            <a href="?g=app&m=member&a=message" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/znxx@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>站内信箱</span></p>
            </a>
            <a href="?g=app&m=member&a=opinion" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/jyfk@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>建议反馈</span></p>
            </a>
            <a href="tel:<?php echo ($list['companphone']); ?>" class="weui-grid js_grid">
                <div class="weui-grid__icon">
                    <img src="./Public/App/images/lxkf@2x.png" alt="">
                </div>
                <p class="weui-grid__label"><span>菜鸟客服</span></p>
            </a>
        </div>
    </div>
    <!--更多结束-->


    <!-- 收藏资讯、收藏产品、收藏企业、收藏求购 -->
    <!--<div class="member_collect">
        <a class="member_collect_news member_link" href="?g=app&m=member&a=news_collect">
            <p class="member_link_title"><span>收藏资讯</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
        <a class="member_collect_goods member_link" href="?g=app&m=member&a=product_collect">
            <p class="member_link_title"><span>收藏产品</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
        <a class="member_collect_company member_link" href="?g=app&m=member&a=company_collect">
            <p class="member_link_title"><span>收藏企业</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
         <a class="member_collect_need member_link" href="?g=app&m=member&a=need_collect">
            <p class="member_link_title"><span>收藏求购</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
    </div>-->
    <!-- 收藏资讯、收藏产品、收藏企业、收藏求购 -->

    <!-- 发布求购、供应 开始 -->
    <!--<div class="member_info">
        <a class="member_publish_need member_link" href="?g=app&m=member&a=need">
            <p class="member_link_title"><span>发布求购信息</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
        <a class="member_publish_supply member_link" href="?g=app&m=member&a=supply">
            <p class="member_link_title"><span>发布供应信息</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
    </div>-->
    <!-- 发布求购、供应 结束 -->
    <!-- 企业、站内信箱 -->
    <!--<div class="member_info">
        <?php if(!empty($company_info["id"])): ?><a class="member_info_company member_link" href="?g=app&m=company&a=detail&id=<?php echo ($company_info["id"]); ?>">
                <p class="member_link_title"><span>我的企业</span></p>
                <div class="next_arrow"><div class="gt"><div></div></div></div>
            </a><?php endif; ?>
        <a class="member_contact member_link" href="?g=app&m=member&a=contact">
            <p class="member_link_title"><span>官方QQ客服</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
        <a class="member_info_merchant member_link" href="?g=app&m=more&a=merchant">
            <p class="member_link_title"><span>商家管理说明</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>-->
    <!--<a class="member_info_mailbox member_link" href="?g=app&m=member&a=message">
        <p class="member_link_title"><span>站内信箱</span></p>
        <div class="next_arrow"><div class="gt"><div></div></div></div>
    </a>-->
    <!--</div>-->
    <!-- 企业、站内信箱 -->
    <!-- 关于APP -->
    <!--<div class="member_more">
        <a class="member_more_about member_link" href="?g=app&m=more">
            <p class="member_link_title"><span>关于APP</span></p>
            <div class="next_arrow"><div class="gt"><div></div></div></div>
        </a>
    </div>-->
    <!-- 关于APP -->
</section>
<!-- 底部 开始 -->
	<?php if(($appsign) != "1"): ?><footer>
		<a href="?g=app&m=index"><i class="home <?php if(($site) == "index"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=news"><i class="news <?php if(($site) == "news"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=product"><i class="product <?php if(($site) == "product"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=company"><i class="company <?php if(($site) == "company"): ?>hover<?php endif; ?>"></i></a>
		<a href="?g=app&m=member"><i class="member <?php if(($site) == "member"): ?>hover<?php endif; ?>"></i></a>
	</footer><?php endif; ?>
<!-- 底部 结束 -->
<script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./Public/static/js/delay.js"></script>
</body>
</html>