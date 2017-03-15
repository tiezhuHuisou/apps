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
	<script type="text/javascript" src="./Public/static/kindeditor/kindeditor.js"></script>
	<script type="text/javascript" src="./Public/static/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript">
    var imgUrl = "./Public/Admin/images";
    </script>
	<script type="text/javascript" src="./Public/Admin/js/news_add.js"></script>
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
				<h2>资讯管理中心</h2>
				<a href="?g=admin&m=news">资讯列表</a>
                <a class="hover" href="?g=admin&m=news&a=add">添加资讯</a>
            </div>
            <h3>添加资讯</h3>
            <!-- 主体数据 -->
            <form class="form-horizontal chkform" name="" action="" method="post" enctype="multipart/form-data">
                <table class="add_table">
                    <input  type="hidden" name="id" value="<?php echo ($detail["id"]); ?>"/>
                    <!-- 标题 -->
                    <tr>
                        <td class="w120">标题：</td>
                        <td>
                            <input class="i400" name="title" type="text" value="<?php echo ($detail["title"]); ?>" datatype="*" nullmsg="请填写标题"  />
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写标题</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 短标题 -->
                    <tr>
                        <td class="w120">主要内容：</td>
                        <td>
                            <input class="i400" name="short_title" type="text" value="<?php echo ($detail["short_title"]); ?>" datatype="*" nullmsg="请填写主要内容" />
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写主要内容</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 属性 -->
                    <tr>
                        <td class="w120">属性：</td>
                        <td class="lh22">
                            <?php if(is_array($flags)): $i = 0; $__LIST__ = $flags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fl): $mod = ($i % 2 );++$i;?><label class="mgr4">
                                    <input class="pt3" name="flags" type="radio" value="<?php echo ($fl["flags"]); ?>" <?php if(($fl["flags"]) == $detail["flags"]): ?>checked<?php endif; ?> /><?php echo ($fl["title"]); ?>
                                </label><?php endforeach; endif; else: echo "" ;endif; ?>
                        </td>
                    </tr>
                    <!-- 分类 -->
                    <tr>
                        <td class="w120">分类：</td>
                        <td>
                            <select name="categoryid" class="pl3">
                                <option value="">请选择</option>
                                <?php if(is_array($classify)): $i = 0; $__LIST__ = $classify;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cl["cid"]); ?>" <?php if(($cl["cid"]) == $detail["categoryid"]): ?>selected="selected"<?php endif; ?> ><?php echo ($cl["cname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <!-- 来源 -->
                    <tr>
                        <td class="w120">来源：</td>
                        <td>
                            <input class="i400" name="source" type="text" value="<?php echo ((isset($detail["source"]) && ($detail["source"] !== ""))?($detail["source"]):'管理员'); ?>" />
                        </td>
                    </tr>
                    <!-- 图片按钮 -->
                    <tr>
                        <td class="w120">图片：</td>
                        <td>
                            <a id="cover_btn" class="opt_btn mgl0" href="javascript:void(0);">选择文件</a>
                            <span class="pl3">建议尺寸：230*230</span>
                        </td>
                    </tr>
                    <!-- 图片预览 -->
                    <tr <?php if(empty($allpic)): ?>class="no"<?php endif; ?>>
                        <td class="w120">图片预览：</td>
                        <td id="cover_img">
                            <?php if(is_array($allpic)): $i = 0; $__LIST__ = $allpic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i;?><p class="img_wrap">
                                    <img class="delete_img" src="./Public/Admin/images/delete_img.png" width="20" height="20" />
                                    <img src="<?php echo ($pic["img"]); ?>"  width="100" height="100" />
                                    <input value="<?php echo ($pic["img"]); ?>" name="allpic[]" type="hidden" />
                                </p><?php endforeach; endif; else: echo "" ;endif; ?>
                        </td>
                    </tr>
                    <!-- 内容 -->
                    <tr>
                        <td class="w120">资讯内容：</td>
                        <td>
                            <textarea id="news_content" name="content" datatype="*"><?php echo ($detail["content"]); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">排序：</td>
                        <td>
                            <input class="i50" type="text" name="sort" value="<?php echo ((isset($detail["sort"]) && ($detail["sort"] !== ""))?($detail["sort"]):'50'); ?>" datatype="n" nullmsg="请填写数字" errormsg="请填写数字" />
                            <span class="pl3">数字越大越靠前</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写数字</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 是否显示 -->
                    <tr>
                        <td class="w120">是否显示：</td>
                        <td>
                            <label class="mgr">
                                <input class="pt3" type="radio" name="is_display" value="1" checked />
                                <span>显示</span>
                            </label>
                            <label>
                                <input class="pt3" type="radio" name="is_display" value="0" <?php if(($detail["is_display"]) == "0"): ?>checked<?php endif; ?> />
                                <span>不显示</span>
                            </label>
                        </td>
                    </tr>
                    <!-- 操作 -->
                    <tr class="add_opt">
                        <td></td>
                        <td>
                            <input class="opt_btn mgl0 ajax-post" target-form="form-horizontal" type="submit" value="确认提交" />
                            <input class="opt_btn" type="reset" value="重置表单" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!-- 主体 结束 -->
</body>
</html>