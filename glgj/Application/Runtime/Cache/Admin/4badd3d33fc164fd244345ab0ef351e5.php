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
        <input  type="hidden" id="abouts1" value="<?php echo ((isset($abouts) && ($abouts !== ""))?($abouts):0); ?>" />
        <input  type="hidden" id="type1" value="<?php echo ($type); ?>"/>
        <div class="wrapper">
            <!-- 主体头部 -->
            <div class="header">
                <h2>推送消息管理中心</h2>
                <a href="?g=admin&m=push">推送消息列表</a>
                <a class="hover" href="?g=admin&m=push&a=add">添加推送消息</a>
            </div>
            <h3>添加推送消息</h3>
            <!-- 添加消息表单 -->
            <form class="form-horizontal chkform" name="" action="" method="post">
                <input type="hidden" name="id" value="<?php echo ($detail["id"]); ?>" />
                <table class="add_table">
                    <tr>
                        <td class="w120">关联页面类型：</td>
                        <td>
                            <select name="type">
                                <option class="pl3" value="1">资讯</option>

                                <!--<option class="pl3" value="2">产品</option>-->
                                <option class="pl3" value="3">企业</option>
                                <!--<option class="pl3" value="4">求购</option>-->
                                <!-- <option class="pl3" value="5">行业圈</option> -->
                                <option class="pl3" value="6">公告</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">关联页面：</td>
                        <td>
                            <select name="page">
                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option class="pl3" value="<?php echo ($vo['id']); ?>" data-content="<?php echo ($vo['content']); ?>"><?php echo ($vo['title']); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">推送标题：</td>
                        <td>
                            <input class="i400" type="text" name="title" value="<?php echo ((isset($detail['title']) && ($detail['title'] !== ""))?($detail['title']):$list[0]['title']); ?>" datatype="title" nullmsg="请填写推送标题" errormsg="标题应为2-50个字符" />
                        </td>
                        <!-- 表单验证提示信息 -->
                        <td>
                            <div class="info">
                                <span class="Validform_checktip">请填写推送标题</span>
                                <span class="dec">
                                    <s class="dec1">&#9670;</s>
                                    <s class="dec2">&#9670;</s>
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="w120">推送内容：</td>
                        <td>
                        <textarea class="t400" name="content"><?php echo ((isset($detail["content"]) && ($detail["content"] !== ""))?($detail["content"]):$list[0]['content']); ?></textarea>
                        </td>
                    </tr>
                    <!-- 提交按钮 -->
                    <tr class="add_opt">
                        <td></td>
                        <td>
                            <input class="opt_btn ajax-post mgl0 chksubmit" target-form="form-horizontal" type="submit" value="确定提交" />
                            <span class="pl3 ">建议不要频繁推送消息</span>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <!-- 主体 结束 -->
    <script type="text/javascript">
        $(function() {
            /* 推送页面类型 */
            $('select[name="type"]').change(function() {
                var type = $(this).val();
                $.post('?g=admin&m=push&a=getlist', {type: type}, function(data) {
                    if ( data ) {
                        var _html = '';
                        for (var i = 0; i < data.length; i++) {
                            _html += '<option class="pl3" value="'+data[i]['id']+'" data-content="'+data[i]['content']+'">'+data[i]['title']+'</option>';
                        }
                        $('select[name="page"]').html(_html);
                        $('input[name="title"]').val(data[0]['title']);
                        $('textarea[name="content"]').val(data[0]['content']);
                    }
                });
            });

            /* 推送页面 */
            $('select[name="page"]').change(function() {
                var selectedObj = $(this).find('option:selected');
                $('input[name="title"]').val(selectedObj.text());
                $('textarea[name="content"]').val(selectedObj.data('content'));
            });
        });
    </script>
</body>
</html>