<?php
return array(
	/* 文件上传相关配置 */
	'DOWNLOAD_UPLOAD' => array(
		'mimes' => '', //允许上传的文件MiMe类型
		'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
		'exts' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
		'autoSub' => true, //自动子目录保存文件
		'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/Download/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt' => '', //文件保存后缀，空则使用原后缀
		'replace' => false, //存在同名是否覆盖
		'hash' => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //下载模型上传配置（文件上传类配置）

	/* 图片上传相关配置 */
	'PICTURE_UPLOAD' => array(
		'mimes' => '', //允许上传的文件MiMe类型
		'maxSize' => 2 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
		'exts' => 'jpg,gif,png,jpeg', //允许上传的文件后缀
		'autoSub' => true, //自动子目录保存文件
		'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
		'rootPath' => './Uploads/Picture/', //保存根路径
		'savePath' => '', //保存路径
		'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
		'saveExt' => '', //文件保存后缀，空则使用原后缀
		'replace' => false, //存在同名是否覆盖
		'hash' => true, //是否生成hash编码
		'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
	), //图片上传相关配置（文件上传类配置）

	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
		'__STATIC__' => './Public/static',
		'__IMG__' => './Public/' . MODULE_NAME . '/images',
		'__CSS__' => './Public/' . MODULE_NAME . '/css',
		'__JS__' => './Public/' . MODULE_NAME . '/js',
	),

	/* SESSION 和 COOKIE 配置 */
	'SESSION_PREFIX' => 'apps', //session前缀
	'COOKIE_PREFIX' => 'apps_', // Cookie前缀 避免冲突
	'VAR_SESSION_ID' => 'session_id', //修复uploadify插件无法传递session_id的bug

	'ht' => 'admin',
	'APP_NAME' => '医疗咨询',
	'app_codename' => 'ylzxw',
	'app_color' => 'olive',
	'MEMBER_AUTH_KEY' => 'qt_6b68484e05bff62b8098e32a67aa839c',
	'ADMIN_AUTH_KEY' => 'ht_rere484e05fshjytr7874582a67aa839b',
	/*邮箱配置*/
	'SMTPSERVER' => 'smtp.263.net', //SMTP服务器
	'SMTPSERVERPORT' => 25, //SMTP服务器端口
	'COMPANY_EMAIL' => 'app@huisoumail.com', //SMTP服务器的用户邮箱
	'SMTPUSER' => 'app@huisoumail.com', //SMTP服务器的用户帐号
	'SMTPPASS' => 'huisou123', //SMTP服务器的用户密码
	'MAILTYPE' => 'HTML', //邮件格式（HTML/TXT）,TXT为文本邮件

	'LIST_ROW' => 10, //控制分页数据的长度
	'HTTP_DOMIN' => 'http://wyc.m.huisou.com',
);
