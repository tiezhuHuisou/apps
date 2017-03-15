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
    <link rel="stylesheet" type="text/css" href="./Public/Admin/css/product_add.css" />
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
				<h2>产品管理中心</h2>
				<a href="?g=admin&m=product">产品列表</a>
                <a class="hover" href="?g=admin&m=product&a=add">添加产品</a>
            </div>
            <h3>产品管理</h3>
            <!-- 主体数据 -->
            <form class="form-horizontal chkform" name="" action="" method="post">
                <input type="hidden" name="id" value="<?php echo ($detail["id"]); ?>"/>
                <table class="add_table">
                    <!-- 产品名称输入框 -->
                    <tr>
                        <td class="w120">产品标题：</td>
                        <td>
                            <input class="i200" type="text" name="title" value="<?php echo ($detail["title"]); ?>" datatype="title" nullmsg="请填写产品名称" errormsg="请填写2-50位字符" />
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写产品名称</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 产品简介输入框 -->
                    <tr>
                        <td class="w120">产品副标题：</td>
                        <td>
                            <input class="i400" type="text" name="short_title" value="<?php echo ($detail["short_title"]); ?>" datatype="title" nullmsg="请填写产品简介" errormsg="请填写2-50位字符" />
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写产品简介</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 属性复选框 -->
                    <tr>
                        <td class="w120">属性：</td>
                        <td class="lh22">
                        	<?php if(is_array($flags)): $i = 0; $__LIST__ = $flags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fl): $mod = ($i % 2 );++$i;?><label class="mgr4">
                                    <input class="pt3" name="flags[]" type="radio" value="<?php echo ($fl["att"]); ?>" <?php if(in_array(($fl["att"]), is_array($detail["flags"])?$detail["flags"]:explode(',',$detail["flags"]))): ?>checked<?php endif; ?> /><?php echo ($fl["attname"]); ?>
                                </label><?php endforeach; endif; else: echo "" ;endif; ?>
                        </td>
                    </tr>
                    <?php if(($flashFlag) == "1"): ?><!-- 活动设置 -->
                        <tr>
                            <td class="w120">活动设置：</td>
                            <td>
                                <label class="mgr">
                                    <input class="pt3" type="radio" name="activity_type" value="0" checked />
                                    <span>无</span>
                                </label>
                                <label>
                                    <input class="pt3" type="radio" name="activity_type" value="1" <?php if(($detail["activity_type"]) == "1"): ?>checked<?php endif; ?> />
                                    <span>限时抢购</span>
                                </label>
                            </td>
                        </tr><?php endif; ?>
                    <?php if(($distribution_flag) == "1"): ?><!-- 是否分销 -->
                        <tr>
                            <td class="w120">是否分销：</td>
                            <td>
                                <label class="mgr">
                                    <input class="pt3" type="radio" name="distribution" value="1" checked />
                                    <span>参与</span>
                                </label>
                                <label>
                                    <input class="pt3" type="radio" name="distribution" value="0" <?php if(($detail["distribution"]) == "0"): ?>checked<?php endif; ?> />
                                    <span>不参与</span>
                                </label>
                            </td>
                        </tr><?php endif; ?>
                    <!-- 所属分类 -->
                    <tr>
                        <td class="w120">所属分类：</td>
                        <td>
                            <div class="category_area">
                                <a class="category_btn" href="javascript:void(0);">选择分类<span class="btn_down"></span></a>
                                <ul class="category_wrap">
                                    <li class="category_list">
                                        <?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cl): $mod = ($i % 2 );++$i;?><label class="one_list"><input type="checkbox" name="sale_category_id[]" value="<?php echo ($cl["id"]); ?>" <?php if(in_array(($cl["id"]), is_array($detail["sale_category_id"])?$detail["sale_category_id"]:explode(',',$detail["sale_category_id"]))): ?>checked<?php endif; ?> /><?php echo ($cl["sty"]); echo ($cl["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <!-- 发布企业下拉框 -->
                    <tr>
                        <td class="w120">发布企业：</td>
                        <td>
                            <select class="pl3" name="company_id">
                                <option value="">请选择</option>
                                <?php if(is_array($company)): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><option value="<?php echo ($co["id"]); ?>" <?php if(($co["id"]) == $detail["company_id"]): ?>selected<?php endif; ?>><?php echo ($co["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <!-- 产品详情轮播图按钮 -->
                    <tr>
                        <td class="w120">产品详情轮播图：</td>
                        <td>
                            <a id="carousel_btn" class="opt_btn mgl0" href="javascript:void(0);">选择文件</a>
                            <span class="pl3">建议尺寸：640*640</span>
                        </td>
                    </tr>
                    <!-- 轮播图预览 -->
                    <tr <?php if(empty($allpic)): ?>class="no"<?php endif; ?>>
                        <td class="w120">产品详情轮播图预览：</td>
                        <td id="carousel_img">
                            <?php if(is_array($allpic)): $i = 0; $__LIST__ = $allpic;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pic): $mod = ($i % 2 );++$i;?><p class="img_wrap">
                                    <img class="delete_img" src="./Public/Admin/images/delete_img.png" width="20" height="20" />
                                    <img src="<?php echo ($pic["pic_url"]); ?>"  width="100" height="100" />
                                    <input value="<?php echo ($pic["pic_url"]); ?>"  name="allpic[]" type="hidden" />
                                </p><?php endforeach; endif; else: echo "" ;endif; ?>
                        </td>
                    </tr>
                    <!-- 销售价 -->
                    <tr>
                        <td class="w120">销售价：</td>
                        <td>
                            <input id="price" class="i100" type="text" name="price" value="<?php echo ($detail['price']); ?>" />
                            <span class="pl3">元</span>
                        </td>
                    </tr>
                    <!-- 市场价 -->
                    <tr>
                        <td class="w120">市场价：</td>
                        <td>
                            <input id="oprice" class="i100" type="text" name="oprice" value="<?php echo ($detail['oprice']); ?>" />
                            <span class="pl3">元</span>
                        </td>
                    </tr>
                    <?php if(($flashFlag) == "1"): ?><!-- 活动价 -->
                        <tr>
                            <td class="w120">活动价：</td>
                            <td>
                                <input id="activity_price" class="i100" type="text" name="activity_price" value="<?php echo ($detail['activity_price']); ?>" />
                                <span class="pl3">元</span>
                            </td>
                        </tr><?php endif; ?>
                    <!-- 重量 -->
                    <tr>
                        <td class="w120">重量：</td>
                        <td>
                            <input id="weight" class="i100" type="text" name="weight" value="<?php echo ($detail['weight']); ?>" />
                            <span class="pl3">千克</span>
                        </td>
                    </tr>
                    <!-- 库存 -->
                    <tr>
                        <td class="w120">库存：</td>
                        <td>
                            <input class="i100" type="text" name="num" value="<?php echo ((isset($detail['num']) && ($detail['num'] !== ""))?($detail['num']):9999); ?>" />
                            <span class="pl3">件</span>
                        </td>
                    </tr>
                    <!-- 最小购买数量 -->
                    <tr>
                        <td class="w120">最小购买数量：</td>
                        <td>
                            <input class="i100" type="text" name="buymin" value="<?php echo ((isset($detail['buymin']) && ($detail['buymin'] !== ""))?($detail['buymin']):1); ?>" />
                            <span class="pl3">件</span>
                        </td>
                    </tr>
                    <!-- 优惠券最多可抵 -->
                    <tr>
                        <td class="w120">优惠券最多可抵：</td>
                        <td>
                            <input class="i100" type="text" name="preferential" value="<?php echo ((isset($detail['preferential']) && ($detail['preferential'] !== ""))?($detail['preferential']):1); ?>" />
                            <span class="pl3">元<span style="color: red;">(不填或填0 代表该商品使用优惠券可抵金额无上限 填-1代表该商品不可以使用优惠券)</span></span>
                        </td>
                    </tr>
                    <?php if(($distribution_flag) == "1"): ?><!-- 本级佣金 -->
                        <tr>
                            <td class="w120">本级佣金：</td>
                            <td>
                                <input class="i100" type="text" name="commission1" value="<?php echo ($detail['commission1']); ?>" />
                                <span class="pl3">元</span>
                            </td>
                        </tr>
                        <!-- 上级佣金 -->
                        <tr>
                            <td class="w120">上级佣金：</td>
                            <td>
                                <input class="i100" type="text" name="commission2" value="<?php echo ($detail['commission2']); ?>" />
                                <span class="pl3">元</span>
                            </td>
                        </tr>
                        <!-- 上上级佣金 -->
                        <tr>
                            <td class="w120">上上级佣金：</td>
                            <td>
                                <input class="i100" type="text" name="commission3" value="<?php echo ($detail['commission3']); ?>" />
                                <span class="pl3">元</span>
                            </td>
                        </tr><?php endif; ?>
                    <!-- 规格 -->
                    <input id="is_spec" type="hidden" name="is_spec" value="<?php echo ((isset($detail["is_spec"]) && ($detail["is_spec"] !== ""))?($detail["is_spec"]):0); ?>" />
                    <tr>
                        <td class="w120">规格：</td>
                        <td>
                            <a id="spec_on" class="opt_btn mgl0" href="javascript:void(0);">开启规格</a>
                            <a id="spec_toggle" class="opt_btn <?php if(($detail['is_spec']) == "0"): ?>no<?php endif; ?> <?php if(empty($detail['is_spec'])): ?>no<?php endif; ?>" href="javascript:void(0);"><?php if(($detail['is_spec']) == "0"): ?>展开<?php else: ?>收起<?php endif; ?>规格</a>
                        </td>
                    </tr>
                    <tr id="spec_wrap" <?php if(($detail['is_spec']) == "0"): ?>class="no"<?php endif; ?> <?php if(empty($detail['is_spec'])): ?>class="no"<?php endif; ?>>
                        <td class="w120"></td>
                        <td>
                            <!-- 规格表格 开始 -->
                            <div class="spec_table">
                                <!-- 规格表格头部 开始 -->
                                <ul>
                                    <li><input class="i70 spec_title1" type="text" name="spec[title1]" value="<?php echo ($specTitle1); ?>" placeholder="例：颜色" /></li>
                                    <li><input class="i70 spec_title2" type="text" name="spec[title2]" value="<?php echo ($specTitle2); ?>" placeholder="例：尺寸" /></li>
                                    <li>销售价</li>
                                    <li>市场价</li>
                                    <?php if(($flashFlag) == "1"): ?><li>活动价</li><?php endif; ?>
                                    <li>重量</li>
                                    <li>库存</li>
                                    <li class="w80">最小购买数量</li>
                                    <li>排序</li>
                                    <li class="img_wrap">规格图片<span>(建议尺寸：300*300)</span></li>
                                    <li>操作</li>
                                </ul>
                                <!-- 规格表格头部 结束 -->
                                <!-- 规格表格列表 开始 -->
                                <?php if(!empty($specList)): if(is_array($specList)): $i = 0; $__LIST__ = $specList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul>
                                            <li><input class="i70 spec_spec1" type="text" name="spec[spec1][]" value="<?php echo ($vo['spec1']); ?>" placeholder="例：红色" /></li>
                                            <li><input class="i70 spec_spec2" type="text" name="spec[spec2][]" value="<?php echo ($vo['spec2']); ?>" placeholder="例：均码" /></li>
                                            <li><input class="i50 spec_price" type="text" name="spec[price][]" value="<?php echo ($vo['price']); ?>" /></li>
                                            <li><input class="i50" type="text" name="spec[oprice][]" value="<?php echo ($vo['oprice']); ?>" /></li>
                                            <?php if(($flashFlag) == "1"): ?><li><input class="i50" type="text" name="spec[activity_price][]" value="<?php echo ($vo['activity_price']); ?>" /></li><?php endif; ?>
                                            <li><input class="i50" type="text" name="spec[weight][]" value="<?php echo ($vo['weight']); ?>" /></li>
                                            <li><input class="i50 spec_stock" type="text" name="spec[stock][]" value="<?php echo ($vo['stock']); ?>" /></li>
                                            <li class="w80"><input class="i50 spec_buymin" type="text" name="spec[buymin][]" value="<?php echo ($vo['buymin']); ?>" /></li>
                                            <li><input class="i50 spec_sort" type="text" name="spec[sort][]" value="<?php echo ($vo['sort']); ?>" /></li>
                                            <li class="img_wrap">
                                                <img class="spec_img" src="<?php echo ((isset($vo['img']) && ($vo['img'] !== ""))?($vo['img']):'./Public/static/images/default_icon.jpg'); ?>" width="50" height="50" />
                                                <input type="hidden" name="spec[img][]" value="<?php echo ((isset($vo['img']) && ($vo['img'] !== ""))?($vo['img']):'./Public/static/images/default_icon.jpg'); ?>">
                                            </li>
                                            <li>
                                                <a class="spec_up" href="javascript:void(0);">使用上图</a>
                                                <a class="spec_copy" href="javascript:void(0);">复制</a>
                                                <a class="spec_del" href="javascript:void(0);">删除</a>
                                            </li>
                                        </ul><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <?php else: ?>
                                    <ul>
                                        <li><input class="i70 spec_spec1" type="text" name="spec[spec1][]" value="" placeholder="例：红色" /></li>
                                        <li><input class="i70 spec_spec2" type="text" name="spec[spec2][]" value="" placeholder="例：均码" /></li>
                                        <li><input class="i50 spec_price" type="text" name="spec[price][]" value="" /></li>
                                        <li><input class="i50" type="text" name="spec[oprice][]" value="" /></li>
                                        <?php if(($flashFlag) == "1"): ?><li><input class="i50" type="text" name="spec[activity_price][]" value="" /></li><?php endif; ?>
                                        <li><input class="i50" type="text" name="spec[weight][]" value="" /></li>
                                        <li><input class="i50 spec_stock" type="text" name="spec[stock][]" value="9999" /></li>
                                        <li class="w80"><input class="i50 spec_buymin" type="text" name="spec[buymin][]" value="1" /></li>
                                        <li><input class="i50 spec_sort" type="text" name="spec[sort][]" value="0" /></li>
                                        <li class="img_wrap">
                                            <img class="spec_img" src="./Public/static/images/default_icon.jpg" width="50" height="50" />
                                            <input type="hidden" name="spec[img][]" value="./Public/static/images/default_icon.jpg">
                                        </li>
                                        <li>
                                            <a class="spec_up" href="javascript:void(0);">使用上图</a>
                                            <a class="spec_copy" href="javascript:void(0);">复制</a>
                                            <a class="spec_del" href="javascript:void(0);">删除</a>
                                        </li>
                                    </ul><?php endif; ?>
                                <!-- 规格表格列表 结束 -->
                                <ul id="last_ul">
                                    <a id="spec_add" class="opt_btn mgl0" href="javascript:void(0);">添加一行</a>
                                    <a id="spec_off" class="opt_btn" href="javascript:void(0);">关闭规格</a>
                                </ul>
                            </div>
                            <!-- 规格表格 结束 -->
                        </td>
                    </tr>
                    <!-- 商品详情 -->
                    <tr>
                        <td class="w120">商品详情：</td>
                        <td>
                            <textarea id="supply_content" name="summary" datatype="*" nullmsg="请填写商品详情"><?php echo ($detail["summary"]); ?></textarea>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写商品详情</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <!-- 排序 -->
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
                                <input class="pt3" type="radio" name="status" value="1" checked />
                                <span>显示</span>
                            </label>
                            <label>
                                <input class="pt3" type="radio" name="status" value="0" <?php if(($detail["status"]) == "0"): ?>checked<?php endif; ?> />
                                <span>不显示</span>
                            </label>
                        </td>
                    </tr>
                    <!-- 操作 -->
                    <tr class="add_opt">
                        <td></td>
                        <td>
                            <input class="opt_btn ajax-post mgl0 chksubmit" target-form="form-horizontal" type="submit" value="确认提交" />
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
    <script type="text/javascript">
        var imgUrl     = "./Public/Admin/images";
        var staticPath = "./Public/static";
        var flashFlag = "<?php echo ((isset($flashFlag) && ($flashFlag !== ""))?($flashFlag):0); ?>";
    </script>
    <script type="text/javascript" src="./Public/static/layer/layer.js"></script>
    <script type="text/javascript" src="./Public/Admin/js/product_add.js"></script>
</body>
</html>