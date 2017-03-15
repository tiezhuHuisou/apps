<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title><?php echo ($site_title); ?></title>
<link rel="stylesheet" type="text/css" href="./Public/Admin/css/base.css" />
<link rel="stylesheet" type="text/css" href="./Public/static/check/check.css">
<script type="text/javascript" src="./Public/static/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="./Public/Admin/js/base.js"></script>
<script type="text/javascript" src="./Public/static/layer/layer.js"></script>
<script type="text/javascript" src="./Public/static/js/common.js"></script>
<script type="text/javascript" src="./Public/static/check/check.js"></script>
<script type="text/javascript" src="./Public/static/js/check.js"></script>
    <link rel="stylesheet" type="text/css" href="./Public/static/kindeditor/themes/default/default.css" />
</head>
<body>
    <!-- 顶部 开始 -->
    <div class="top">
	<table>
		<tr>
			<td width="200" rowspan="2" align="right" valign="middle">
				<div class="logo">
					<img src="./Public/Admin/images/logo.png" onerror="javascript:this.src='http://fe.huisouimg.com/appmanage/applogo/default/logo2.png'" width="160" height="auto" />
				</div>
			</td>
			<td class="system">
				<span class="yellow">[<span><?php echo (session('webname')); ?></span>管理平台]</span>
				<span class="welcome">欢迎：<strong class="username"><?php echo (session('name')); ?></strong>登陆</span>
				<a class="exit ajax-get" href="javascript:void(0);">[退出]</a>
				<a href="?g=admin&m=index">网站首页</a>
				<span class="spe">|</span>
				<a href="http://unionapp.org/" target="_blank">技术支持</a>
				<span class="spe">|</span>
				<a href="?g=admin&m=index&a=downfile">下载说明文档</a>
			</td>
		</tr>
		<tr>
			<td class="nav">
				<a <?php if(($site) == "home"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index">核心管理</a>
				<a <?php if(($site) == "news"): ?>class="hover"<?php endif; ?> href="?g=admin&m=news">资讯管理</a>
				<!--<a <?php if(($site) == "news"): ?>class="hover"<?php endif; ?> href="?g=admin&m=news">发布</a>-->
				<!--<a <?php if(($site) == "product"): ?>class="hover"<?php endif; ?> href="?g=admin&m=product">产品管理</a>-->
				<a <?php if(($site) == "product"): ?>class="hover"<?php endif; ?> href="?g=admin&m=product&a=truck">网站产品</a>
				<?php if((C("FLASHFLAG")) == "1"): ?><a <?php if(($site) == "order"): ?>class="hover"<?php endif; ?> href="?g=admin&m=order">订单管理</a>
					<a <?php if(($site) == "activity"): ?>class="hover"<?php endif; ?> href="?g=admin&m=activity">活动管理</a><?php endif; ?>
				<?php if(($distribution_flag) == "1"): ?><a <?php if(($site) == "distribution"): ?>class="hover"<?php endif; ?> href="?g=admin&m=distribution">分销管理</a><?php endif; ?>
				<a <?php if(($site) == "more"): ?>class="hover"<?php endif; ?> href="?g=admin&m=more">更多管理</a>
				<a <?php if(($site) == "member"): ?>class="hover"<?php endif; ?> href="?g=admin&m=member">会员管理</a>
				<a <?php if(($site) == "user"): ?>class="hover"<?php endif; ?> href="?g=admin&m=user">管理员管理</a>
				<div class="clear"></div>
			</td>
		</tr>
	</table>
</div>
    <!-- 顶部 结束 -->
    <!-- 左侧 开始 -->
    <div class="left">
	<ul>
		<?php if(($site) == "home"): ?><!-- 核心管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index">管理首页</a></li>
			<li <?php if(($left) == "core"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=core">核心设置</a></li>
			<?php if((C("FLASHFLAG")) == "1"): ?><li <?php if(($left) == "freight"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=freight">运费模板管理</a></li><?php endif; ?>
			<li <?php if(($left) == "regions"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=index&a=regions">省市区管理</a></li>
			<li><a  class="exit ajax-get" href="javascript:void(0);">退出登陆</a></li><?php endif; ?>
		<?php if(($site) == "news"): ?><!-- 资讯管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news">资讯列表</a></li>
			<li <?php if(($left) == "classify"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=classify">资讯分类</a></li>
			<!--<li <?php if(($left) == "circle"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=circle">行业圈管理</a></li>-->
			<!--<li <?php if(($left) == "circlecategory"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=circlecategory">行业圈分类</a></li>-->
			<li <?php if(($left) == "notice"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=news&a=notice">公告</a></li><?php endif; ?>
		<?php if(($site) == "product"): ?><!-- 产品管理-左侧 -->
			<!--<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product">产品管理</a></li>-->
			<!--<li <?php if(($left) == "classify"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=classify">产品分类</a></li>-->
			<!--<li <?php if(($left) == "need"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=need">求购管理</a></li>-->
			<li <?php if(($left) == "truck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=truck">货车管理</a></li>
			<li <?php if(($left) == "depot"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=depot">仓储管理</a></li>
			<li <?php if(($left) == "goods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=product&a=goods">货物管理</a></li><?php endif; ?>
		<?php if(($site) == "activity"): if((C("FLASHFLAG")) == "1"): ?><!-- 活动管理-左侧 -->
				<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity">优惠券设置</a></li>
				<li <?php if(($left) == "coupon"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=coupon">优惠券管理</a></li>
				<li <?php if(($left) == "recharge"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=recharge">充值活动设置</a></li>
				<li <?php if(($left) == "rechargerule"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=rechargerule">充值规则设置</a></li>
				<li <?php if(($left) == "flash"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=activity&a=flash">限时抢购设置</a></li><?php endif; endif; ?>
		<?php if(($distribution_flag) == "1"): if(($site) == "distribution"): ?><!-- 产品管理-左侧 -->
				<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=distribution">分销设置</a></li>
				<li <?php if(($left) == "financialmanagement"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=distribution&a=financialmanagement">佣金提现管理</a></li><?php endif; endif; ?>
		<?php if(($site) == "order"): ?><!-- 订单管理-左侧 -->
			<li <?php if(($left) == "order"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order">订单列表</a></li>
			<li <?php if(($left) == "recharge"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order&a=recharge">充值订单</a></li>
			<?php if((C("FLASHFLAG")) == "1"): ?><li <?php if(($left) == "withdrawals"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=order&a=withdrawals">商家提款申请</a></li><?php endif; endif; ?>
		<?php if(($site) == "more"): ?><!-- 更多管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=more">更多管理</a></li>
			<li <?php if(($left) == "comment"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=comment">留言管理</a></li>
			<li <?php if(($left) == "banner"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=banner">焦点图管理</a></li>
			<li <?php if(($left) == "push"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=push">推送消息管理</a></li><?php endif; ?>
		<?php if(($site) == "member"): ?><!-- 会员管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member">会员列表</a></li>
			<li <?php if(($left) == "company"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=company">企业列表</a></li>
			<li <?php if(($left) == "company_group"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=company_group">企业分组</a></li>
			<li <?php if(($left) == "apply"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=apply">企业申请</a></li>
			<li <?php if(($left) == "applyDepot"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyDepot">仓储主</a></li>
			<li <?php if(($left) == "applyPerTruck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyPerTruck">个体车主</a></li>
			<li <?php if(($left) == "applyComTruck"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyComTruck">公司车主</a></li>
			<li <?php if(($left) == "applyPerGoods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyPerGoods">个体货主</a></li>
			<li <?php if(($left) == "applyComGoods"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=applyComGoods">公司货主</a></li>
			<li <?php if(($left) == "message"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=member&a=message">系统消息</a></li><?php endif; ?>
		<?php if(($site) == "user"): ?><!-- 管理员管理-左侧 -->
			<li <?php if(($left) == "index"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=user">管理员列表</a></li>
			<li <?php if(($left) == "group"): ?>class="hover"<?php endif; ?>><a href="?g=admin&m=user&a=group">管理员分组</a></li><?php endif; ?>
	</ul>
</div>
    <!-- 左侧 结束 -->
    <!-- 主体 开始 -->
    <div class="main">
        <div class="wrapper">
            <!-- 主体头部 -->
            <div class="header">
                <h2>核心设置</h2>
                <!--     <a <?php if(($corenav) == "core"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index&a=core">基本信息</a>
    <a <?php if(($corenav) == "core_company"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index&a=core_company">企业信息</a>
    <a <?php if(($corenav) == "core_linkman"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index&a=core_linkman">联系人信息</a>
    <a <?php if(($corenav) == "core_alipay"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index&a=core_alipay">支付宝账号设置</a>
    <a <?php if(($corenav) == "core_weixin"): ?>class="hover"<?php endif; ?> href="?g=admin&m=index&a=core_weixin">微信账号设置</a> -->
            </div>
            <!-- <h3>基本信息</h3> -->
            <!-- 主体数据 -->
            <form class="form-horizontal chkform" name="" action="" method="post">
                <table class="add_table">
                    <!-- 应用全称输入框 -->
                    <tr>
                        <td class="w120">应用全称：</td>
                        <td>
                            <input class="i400" name="webname" type="text" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="title" nullmsg="请填写应用全称" errormsg="请填写正确的应用全称" />
                            <span class="pl3">可用于关于我们页面</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写应用全称</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 应用简称输入框 -->
                    <tr>
                        <td class="w120">应用简称：</td>
                        <td>
                            <input class="i400" name="simplename" type="text" value="<?php echo ((isset($info_arr['simplename']) && ($info_arr['simplename'] !== ""))?($info_arr['simplename']):''); ?>" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="title" nullmsg="请填写应用简称" errormsg="请填写正确的应用简称" />
                            <span class="pl3">可用于关于我们页面</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写应用简称</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 域名输入框 -->
                    <tr>
                        <td class="w120">域名：</td>
                        <td>
                            <input class="i400" name="url" type="text" value="<?php echo ((isset($info_arr['url']) && ($info_arr['url'] !== ""))?($info_arr['url']):''); ?>" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="patrn" nullmsg="请填写域名" errormsg="请填写正确的域名" />
                            <span class="pl3">可用于关于我们页面（开头请勿添加"http://",结尾请勿添加"/"）</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写域名</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 网站底部其他说明输入框 -->
                    <tr>
                        <td class="w120">网站底部其他说明：</td>
                        <td>
                            <input class="i400" name="other" type="text" value="<?php echo ((isset($info_arr['other']) && ($info_arr['other'] !== ""))?($info_arr['other']):''); ?>" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="*" nullmsg="请填写网站底部其他说明" />
                            <span class="pl3">用于关于APP页面底部</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写网站底部其他说明</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 网站备案号（ICP）输入框 -->
                    <!-- <tr>
                        <td class="w120">网站备案号（ICP）：</td>
                        <td>
                            <input class="i400" name="icp" type="text" value="<?php echo ((isset($info_arr['icp']) && ($info_arr['icp'] !== ""))?($info_arr['icp']):''); ?>" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="*" nullmsg="请填写网站备案号（ICP）" />
                        </td> -->
                        <!-- 表单验证提示信息 -->
                        <!-- <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写网站备案号（ICP）</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr> -->
                    <!-- 行业分类输入框 -->
                    <tr>
                        <td class="w120">行业分类：</td>
                        <td>
                            <input class="i400" name="industry" type="text" value="<?php echo ((isset($info_arr['industry']) && ($info_arr['industry'] !== ""))?($info_arr['industry']):''); ?>" value="<?php echo ((isset($info_arr['webname']) && ($info_arr['webname'] !== ""))?($info_arr['webname']):''); ?>" datatype="title" nullmsg="请填写行业分类" />
                            <span class="pl3">可用于关于我们页面</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写分类名称</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 平台官方客服电话输入框 -->
                    <tr>
                        <td class="w120">平台官方客服电话：</td>
                        <td>
                            <input class="i400" name="companphone" type="text" value="<?php echo ((isset($info_arr['companphone']) && ($info_arr['companphone'] !== ""))?($info_arr['companphone']):''); ?>" />
                            <span class="pl3">用于关于APP页面和右上角三个点里的客服</span>
                        </td>
                    </tr>
                    <?php if((C("FLASHFLAG")) == "1"): ?><!-- 满包邮输入框 -->
                        <tr>
                            <td class="w120">满包邮：</td>
                            <td>
                                <input class="i400" name="freight_free" type="text" value="<?php echo ((isset($info_arr['freight_free']) && ($info_arr['freight_free'] !== ""))?($info_arr['freight_free']):''); ?>" />
                                <span class="pl3">订单金额满多少可免掉邮费，填0或不填代表订单满多少都不包邮</span>
                            </td>
                        </tr><?php endif; ?>
                    <!-- <tr>
                        <td class="w120">开启会员机制：</td>
                        <td>
                            <input type="checkbox" name="member_type" <?php if(($info_arr['member_type']) == "1"): ?>checked<?php endif; ?> value="1">
                        </td>
                        表单验证提示信息
                        <td>
                            <div class="info">
                                <span class="Validform_checktip"></span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr> -->
                    <tr>
                        <td class="w120">开启企业审核：</td>
                        <td>
                            <input type="checkbox" name="company_type" <?php if(($info_arr['company_type']) == "1"): ?>checked<?php endif; ?> value="1">
                        </td>
                        <td>
                            <div class="info">
                                <span class="Validform_checktip"></span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">开启启动广告图：</td>
                        <td>
                            <input type="checkbox" name="start_flag" <?php if(($info_arr['start_flag']) == "1"): ?>checked<?php endif; ?> value="1">
                        </td>
                    </tr> 
                    <!-- 广告图按钮 -->
                    <tr>
                        <td class="w120">启动广告图：</td>
                        <td>
                            <a id="logobtn" class="opt_btn mgl0" href="javascript:void(0);">选择文件</a>
                            <input id="logourl" name="start_img" type="hidden" value="<?php echo ($info_arr["start_img"]); ?>">
                            <span class="pl3">建议尺寸：750*1334</span>
                        </td>
                    </tr>
                    <!-- 广告图预览 -->
                    <tr <?php if(empty($info_arr["start_img"])): ?>class="no"<?php endif; ?>>
                        <td class="w120">启动广告图预览：</td>
                        <td>
                            <img id="logoId" src="<?php echo ($info_arr["start_img"]); ?>" width="112" height="200" />
                        </td>
                    </tr>
                    <!-- 广告图时间输入框 -->
                    <tr>
                        <td class="w120">广告图自动跳过时间：</td>
                        <td>
                            <input class="i400" name="start_time" type="text" value="<?php echo ($info_arr['start_time']); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">链接类型：</td>
                        <td>
                            <label class="mgr">
                                <input id="outside" class="pt3" type="radio" name="href_type" value="1" checked />
                                <span>外链</span>
                            </label>
                            <label>
                                <input id="inside" class="pt3" type="radio" name="href_type" value="2" <?php if(($info_arr["href_type"]) == "2"): ?>checked<?php endif; ?> />
                                <span>内链</span>
                            </label>
                        </td>
                    </tr>
                    <tr <?php if(($info_arr["href_type"]) != "2"): ?>class="no"<?php endif; ?>>
                        <td class="w120">关联模块：</td>
                        <td>
                            <select id="href_model" name="href_model">
                                <option class="pl3" value="product_detail" selected>产品</option>
                                <option class="pl3" value="news_detail" <?php if(($info_arr["href_model"]) == "news_detail"): ?>selected<?php endif; ?>>资讯</option>
                                <option class="pl3" value="need_detail" <?php if(($info_arr["href_model"]) == "need_detail"): ?>selected<?php endif; ?>>求购</option>
                                <option class="pl3" value="company_home" <?php if(($info_arr["href_model"]) == "company_home"): ?>selected<?php endif; ?>>企业</option>
                            </select>
                        </td>
                    </tr>
                    <tr <?php if(($info_arr["href_type"]) != "2"): ?>class="no"<?php endif; ?>>
                        <td class="w120">关联页面：</td>
                        <td>
                            <select id="data_id" name="data_id">
                                <?php if(is_array($dataList)): $i = 0; $__LIST__ = $dataList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><option class="pl3" value="<?php echo ($li["id"]); ?>" <?php if(($li["id"]) == $info_arr["data_id"]): ?>selected<?php endif; ?>><?php echo ($li["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr id="url" <?php if(($info_arr["href_type"]) == "2"): ?>class="no"<?php endif; ?>>
                        <td class="w120">链接地址url：</td>
                        <td>
                            <input class="i400" type="text" name="start_url" value="<?php if(($info_arr["href_type"]) == "1"): echo ($info_arr["start_url"]); endif; ?>" />
                            <span class="pl3">例如：http://www.huisou.com</span>
                        </td>
                    </tr>
                    <!-- 操作 -->
                    <tr class="add_opt">
                        <td></td>
                        <td>
                            <input class="opt_btn mgl0 ajax-post chksubmit" type="submit" target-form="form-horizontal" value="确认提交" />
                            <input class="opt_btn" type="reset" value="重置表单" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!-- 主体 结束 -->
    <script type="text/javascript" src="./Public/static/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" src="./Public/static/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript" src="./Public/Admin/js/core.js"></script>
</body>
</html>