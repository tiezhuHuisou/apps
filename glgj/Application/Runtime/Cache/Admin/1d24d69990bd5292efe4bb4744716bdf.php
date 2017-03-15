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
    <link rel="stylesheet" type="text/css" href="./Public/static/kindeditor/themes/default/default.css"/>
    <script type="text/javascript" src="./Public/static/kindeditor/kindeditor.js"></script>
    <script type="text/javascript" src="./Public/static/kindeditor/lang/zh_CN.js"></script>
    <script type="text/javascript">
        var imgUrl = "./Public/Admin/images";
    </script>
    <script type="text/javascript" src="./Public/Admin/js/company_add.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.2"></script>
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
            <h2>企业管理中心</h2>
            <a href="?g=admin&m=member&a=company">企业管理</a>
            <a class="hover" href="?g=admin&m=member&a=company_add">添加企业</a>
        </div>
        <h3>添加企业</h3>
        <!-- 添加企业表单 -->
        <form class="form-horizontal chkform" name="" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="company[id]" value="<?php echo ($detail["id"]); ?>"/>
            <!--<input type="hidden" name="companylink[id]" value="<?php echo ($detail["linkid"]); ?>"/>-->
            <input type="hidden" name="user_id" value="<?php echo ($detail["user_id"]); ?>"/>
            <!--<input type="hidden" id="lng" value="<?php echo ($detail["lng"]); ?>"/>-->
            <!--<input type="hidden" id="lat" value="<?php echo ($detail["lat"]); ?>"/>-->
            <table class="add_table">
                <?php if(empty($detail["user_id"])): ?><tr>
                        <td class="w120">登陆账号：</td>
                        <td>
                            <input class="i400" type="text" name="company[mphone]" value="<?php echo ($detail["mphone"]); ?>"
                            <?php if(!empty($detail["user_id"])): ?>readOnly="true"<?php endif; ?>
                            >
                            <span class="example">（登陆账号请填手机号码）</span>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请正确填写手机号</span>
                                <span class="dec">
                                        <s class="dec1">&#9670;</s>
                                        <s class="dec2">&#9670;</s>
                                    </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">登录密码：</td>
                        <td>
                            <input class="i400" type="password" name="company[password]" datatype="*" nullmsg="请填写登录密码"
                                   errormsg="请正确填写登录密码"/>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写登录密码</span>
                                <span class="dec">
                                        <s class="dec1">&#9670;</s>
                                        <s class="dec2">&#9670;</s>
                                    </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">确认密码：</td>
                        <td>
                            <input class="i400" type="password" name="company[password1]" recheck="password"
                                   datatype="*" nullmsg="请再输入一次密码！" errormsg="您两次输入密码不一致！"/>
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请再输入一次密码！</span>
                                <span class="dec">
                                        <s class="dec1">&#9670;</s>
                                        <s class="dec2">&#9670;</s>
                                    </span>
                            </div>
                        </td>
                    </tr><?php endif; ?>
                <tr>
                    <td class="w120">企业名称：</td>
                    <td>
                        <input class="i400 company_add_name" type="text" name="company[name]" value="<?php echo ($detail["name"]); ?>"
                               datatype="*" nullmsg="请填写企业名称" errormsg="请填写企业名称"/>
                    </td>
                    <!-- 表单验证提示信息 -->
                    <td>
                        <div class="info">
                            <span class="Validform_checktip">请填写企业名称</span>
                            <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="w120">自营：</td>
                    <td>
                        <label>
                            <input class="pt3" type="radio" name="company[proprietary]" value="0" checked />
                            <span>否</span>
                        </label>
                        <label>
                            <input class="pt3" type="radio" name="company[proprietary]" value="1" <?php if(($detail["proprietary"]) == "1"): ?>checked<?php endif; ?> />
                            <span>是</span>
                        </label>
                    </td>
                </tr> -->
                <tr>
                    <td class="w120">属性：</td>
                    <td class="lh22">
                        <?php if(is_array($flags)): $i = 0; $__LIST__ = $flags;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$fl): $mod = ($i % 2 );++$i;?><label class="mgr4"><input class="pt3" name="company[flags]" type="radio" value="<?php echo ($fl["att"]); ?>"
                                <?php if(($detail["flags"]) == $fl["att"]): ?>checked<?php endif; ?>
                                /><?php echo ($fl["attname"]); ?>[<?php echo ($fl["att"]); ?>]</label><?php endforeach; endif; else: echo "" ;endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="w120">企业分类：</td>
                    <td>
                        <select class="select_sort" name="company[company_category_id]">
                            <option value="0">请选择</option>
                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$li): $mod = ($i % 2 );++$i;?><option value="<?php echo ($li["id"]); ?>"
                                <?php if(($li["id"]) == $detail["company_category_id"]): ?>selected="selected"<?php endif; ?>
                                ><?php echo ($li["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="w120">企业logo：</td>
                    <td>
                        <a id="thumbnail" class="opt_btn mgl0">选择图片</a>
                        <span class="pl3">推荐尺寸180*180</span>
                        <input id="imgurl" type="hidden" name="company[logo]"
                               value="<?php echo ((isset($detail["logo"]) && ($detail["logo"] !== ""))?($detail["logo"]):'/Public/Apps/images/company_default.png'); ?>">
                    </td>
                </tr>
                <tr>
                    <td class="w120">企业logo预览：</td>
                    <td><img id="preview" src="<?php echo ((isset($detail["logo"]) && ($detail["logo"] !== ""))?($detail["logo"]):'/Public/Apps/images/company_default.png'); ?>"
                             style="border:none;margin-top:10px;" width="100" height="100"/></td>
                </tr>

                <!--主题图片-->
                <!--<tr>-->
                <!--<td class="w120">主题图片：</td>-->
                <!--<td>-->
                <!--<a id="themebtn" class="opt_btn mgl0" href="javascript:void(0);">选择文件</a>-->
                <!--<span class="pl3">建议尺寸：700*350</span>-->
                <!--<input id="themeurl" name="company[theme_img]" type="hidden" value="<?php echo ($detail["theme_img"]); ?>">-->
                <!--</td>-->
                <!--</tr>-->
                <!--<tr <?php if(empty($detail["theme_img"])): ?>class="no"<?php endif; ?>>-->
                <!--<td class="w120">主题图片预览：</td>-->
                <!--<td>-->
                <!--<img id="themeId" src="<?php echo ($detail["theme_img"]); ?>" width="350" height="175" />-->
                <!--</td>-->
                <!--</tr>-->
                <!--等级图标-->
                <tr>
                    <td class="w120">等级图标：</td>
                    <td>
                        <a id="introduction_bgimg_btn" class="opt_btn mgl0">选择图片</a>
                        <!--<span class="pl3">推荐尺寸750*1206</span>-->
                        <input id="introduction_bgimg_url" type="hidden" name="company[grade]"
                               value="<?php echo ((isset($detail["grade"]) && ($detail["grade"] !== ""))?($detail["grade"]):''); ?>">
                    </td>
                </tr>
                <tr>
                    <td class="w120">等级图标：</td>
                    <td><img id="introduction_bgimg_preview"
                             src="<?php echo ((isset($detail["grade"]) && ($detail["grade"] !== ""))?($detail["grade"]):'/Public/Apps/images/company_introduction_bgimg.png'); ?>"
                             style="border:none;margin-top:10px;" width="150" height="150"/></td>
                </tr>
                <!--企业主页背景图-->
                <tr>
                    <td class="w120">企业主页背景图：</td>
                    <td>
                        <a id="bgimg_btn" class="opt_btn mgl0">选择图片</a>
                        <span class="pl3">推荐尺寸750*530</span>
                        <input id="bgimg_url" type="hidden" name="company[bgimg]"
                               value="<?php echo ((isset($detail["bgimg"]) && ($detail["bgimg"] !== ""))?($detail["bgimg"]):'/Public/Apps/images/company_bgimg.png'); ?>">
                    </td>
                </tr>
                <tr>
                    <td class="w120">企业主页背景图预览：</td>
                    <td><img id="bgimg_preview" src="<?php echo ((isset($detail["bgimg"]) && ($detail["bgimg"] !== ""))?($detail["bgimg"]):'/Public/Apps/images/company_bgimg.png'); ?>"
                             style="border:none;margin-top:10px;" width="150" height="106"/></td>
                </tr>


                <!-- 企业资质按钮 -->
                <!--<tr>-->
                <!--<td class="w120">企业资质：</td>-->
                <!--<td>-->
                <!--<a id="carousel_btn" class="opt_btn mgl0" href="javascript:void(0);">选择文件</a>-->
                <!--<span class="pl3">建议尺寸：200*200</span>-->
                <!--</td>-->
                <!--</tr>-->
                <!--&lt;!&ndash; 预览 &ndash;&gt;-->
                <!--<tr <?php if(empty($cert)): ?>class="no"<?php endif; ?>>-->
                <!--<td class="w120">企业资质预览：</td>-->
                <!--<td id="carousel_img">-->
                <!--<?php if(is_array($cert)): $i = 0; $__LIST__ = $cert;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cert): $mod = ($i % 2 );++$i;?>-->
                <!--<p class="img_wrap">-->
                <!--<img class="delete_img" src="./Public/Admin/images/delete_img.png" width="20" height="20" />-->
                <!--<img src="<?php echo ($cert); ?>" width="100" height="100" />-->
                <!--<input value="<?php echo ($cert); ?>" name="cert[]" type="hidden" />-->
                <!--</p>-->
                <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                <!--</td>-->
                <!--</tr>-->

                <tr>
                    <td class="w120">主要线路：</td>
                    <td><textarea class="t400" name="company[business]" datatype="*" nullmsg="请填写主营行业"
                                  errormsg="请填写主营行业"><?php echo ($detail["business"]); ?></textarea></td>
                    <!-- 表单验证提示信息 -->
                    <td>
                        <div class="info">
                            <span class="Validform_checktip">请填写主要线路</span>
                            <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="w120">所在地：</td>
                    <td>
                        <select class="province" name="companylink[province_id]">
                            <option value="">请选择所在省</option>
                            <?php if(is_array($region_arr[0])): foreach($region_arr[0] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['province_id']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>

                        </select>
                        <select class="city" name="companylink[city_id]">
                            <option value="">请选择所在市</option>
                            <?php if(is_array($region_arr[$detail['province_id']])): foreach($region_arr[$detail['province_id']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['city_id']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>
                        <select class="towns" name="companylink[countyN]">
                            <option value="">请选择所在区/县</option>
                            <?php if(is_array($region_arr[$detail['city_id']])): foreach($region_arr[$detail['city_id']] as $key=>$val): ?><option value="<?php echo ($val['id']); ?>"
                                <?php if(($detail['countyn']) == $val['id']): ?>selected<?php endif; ?>
                                ><?php echo ($val['name']); ?></option><?php endforeach; endif; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="w120">详细地址：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[address]" value="<?php echo ($detail["address"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="w120">联系人：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[contact_user]"
                               value="<?php echo ($detail["contact_user"]); ?>"/>
                    </td>
                </tr>
                <!-- <tr>
                    <td class="w120">性别：</td>
                    <td>
                        <label>
                            <input class="pt3" type="radio" name="companylink[sex]" value="1" checked="checked"<?php if(($detail["sex"]) == "1"): ?>checked="checked"<?php endif; ?> />
                            <span>男</span>
                        </label>
                        <label>
                            <input class="company_add_radio" type="radio" name="companylink[sex]" value="2" <?php if(($detail["sex"]) == "2"): ?>checked="checked"<?php endif; ?> />
                            <span>女</span>
                        </label>
                    </td>
                </tr>
                -->
                <tr>
                    <td class="w120">职位：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[contact_office]" value="<?php echo ($detail["contact_office"]); ?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="w120">官网：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[site]" value="<?php echo ($detail["site"]); ?>"/>
                        <span class="pl3">（例如：http://www.huisou.com）</span>
                    </td>
                </tr>
                <tr>
                    <td class="w120">微信：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[weixin_num]" value="<?php echo ($detail["weixin_num"]); ?>"/>
                        <span class="pl3">（请填写微信号）</span>
                    </td>
                </tr>
                <tr>
                    <td class="w120">联系手机：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[subphone]" value="<?php echo ($detail["subphone"]); ?>"/>
                        <span class="pl3">（例如：0571-5555555或13555555555）</span>
                    </td>
                </tr>
                <tr>
                    <td class="w120">公司电话：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[telephone]" value="<?php echo ($detail["telephone"]); ?>"/>
                        <span class="pl3">（例如：0571-5555555或13555555555）</span>
                    </td>
                </tr>
                 <tr>
                    <td class="w120">传真：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[fax]" value="<?php echo ($detail["fax"]); ?>"  />
                    </td>
                </tr>
                <tr>
                    <td class="w120">联系邮箱：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[email]" value="<?php echo ($detail["email"]); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="w120">企业客服QQ：</td>
                    <td>
                        <input class="i400" type="text" name="companylink[qq]" value="<?php echo ($detail["qq"]); ?>"  />
                    </td>
                </tr>
                <tr class="company_add_introduce">
                    <td class="w120">企业简介：</td>
                    <td>
                        <textarea class="t400" name="company[summary]"><?php echo ($detail["summary"]); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="w120">排序：</td>
                    <td>
                        <input class="i50" type="text" name="company[sort]" value="<?php echo ((isset($detail["sort"]) && ($detail["sort"] !== ""))?($detail["sort"]):'50'); ?>"
                               datatype="n" nullmsg="请填写数字" errormsg="请填写数字"/>
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
                <tr>
                    <td class="w120">是否显示：</td>
                    <td>
                        <label>
                            <input class="pt3" type="radio" name="company[status]" value="1" checked/>
                            <span>显示</span>
                        </label>
                        <label>
                            <input class="pt3" type="radio" name="company[status]" value="0"
                            <?php if(($detail["status"]) == "0"): ?>checked<?php endif; ?>
                            />
                            <span>不显示</span>
                        </label>
                    </td>
                </tr>
                <!-- 提交按钮 -->
                <tr class="add_opt">
                    <td></td>
                    <td>
                        <input class="opt_btn ajax-post mgl0 chksubmit" target-form="form-horizontal" type="submit"
                               value="确定提交">
                        <input class="opt_btn" type="reset" value="重置表单">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<!-- 主体 结束 -->
<script type="text/javascript">
    var data = <?php echo ($region_arr_json); ?>;
</script>

</body>
</html>