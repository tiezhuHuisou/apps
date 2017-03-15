<?php
namespace Member\Controller;
use Think\Controller;
class RegisterController extends MemberController {
	public function _initialize(){
		// $this->error('用户后台已关闭，所有操作都可以在手机上进行');
	}

	/**
	 * 注册页面
	 * @return [type] [description]
	 */
	public function index(){
	    if(IS_POST){
    	    $post=I('post.');
    	    if(!$post['mphone']){
    	        $this->error('手机号码不能为空');
    	    }
    	    if(!checkMobile($post['mphone'])){
    	        $this->error('手机号码格式不正确');
    	    }
//         	if(!$post['captcha']){
//         	   $this->error('验证码不能为空');
//         	}
//             if(session('captcha')!=$post['captcha']){
//         		$this->error('请输入正确的验证码');
//         	}
        	if(!$post['password']){
        	    $this->error('密码不能为空');
        	}
        	    
        	if(!preg_match('/^\w{6,18}$/', $post['password'])){
        	    $this->error('密码只能为6-18位数字字母下划线组成');
        	}
        	if(!$post['password'] != !$post['password1']){
        	    $this->error('两次密码不一致');
        	 }
    	    /*判断手机号码是否已注册*/
    	    $map['mphone']=array('eq',$post['mphone']);
    	    $info=M('Member')->where($map)->find();
    	    if($info){
    	        $this->error('手机号码已注册');
    	    }
    	    
    	    $salt    = strtoupper(substr(md5(mt_rand(1111111,9999999)),0,6));
    	    
    	    $data['mphone']=$post['mphone'];
    	    $data['lin_salt']=$salt;
    	    $data['utoken']=md5(time().rand(1111,9999));
    	    $data['password']=md5(md5($post['password']).$salt);
    	    $data['regdate']=time();
    	    
    	    $status=M('Member')->add($data);
    	    if($status){
    	        $user_auth=array(
    	            'user_id'=>$status,
    	            'utoken'=>$data['utoken']
    	        );
    	        session('user_auth',$user_auth);
    	        $this->success('注册成功','?g=member&m=login');
    	    }else{
    	        $this->error('注册失败');
    	    }
	    }
		$this->site_title = '注册';
		$this->display();
	}

	/**
	 * 忘记密码
	 * @return [type] [description]
	 */
	public function forget(){
	    if(IS_POST){
	        vendor('Email.email');
	        $name = I('name');
	        $map['mphone']=array('eq',$name);
	        $info=M('Member')->where($map)->find();
	        if(empty($info)){
	            $this->error('不存在该手机号');
	        }
	        if(empty($info['email'])){
	            $this->error('该账号不存在邮箱');
	        }
	        /*配置文件邮箱*/
	        $smtpserver = C('SMTPSERVER');//SMTP服务器
	        $smtpserverport =C('SMTPSERVERPORT');//SMTP服务器端口
	        $smtpusermail = C('COMPANY_EMAIL');//SMTP服务器的用户邮箱
	        $smtpuser = C('SMTPUSER');//SMTP服务器的用户帐号
	        $smtppass = C('SMTPPASS');//SMTP服务器的用户密码
	        $mailtype = C('MAILTYPE');//邮件格式（HTML/TXT）,TXT为文本邮件
	        $smtpemailto = $info['email'];//发送给谁
	        $mailtitle = '找回密码';//邮件主题
	        $captch_code=rand(111111,999999);
	        $_SESSION['captch_code']=$captch_code;
	        $_SESSION['phone']=$name;
	        $mailcontent = "<h3>您的验证码是：".$captch_code."</h3>";//邮件内容
	        $smtp = new \smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
	        $smtp->debug = true;//是否显示发送的调试信息
	        $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
	        if($state){
	            $this->error('发送失败,请联系管理员检查该账号的邮箱是否正确');
	        }
	        $this->success('发送成功','?g=member&m=register&a=code');
	    }
		$this->site_title = '忘记密码';
		$this->display();
	}

	/**
	 * 填写验证码
	 * @return [type] [description]
	 */
	public function code(){
		$this->site_title = '填写验证码';
		$this->display();
	}

	/**
	 * 重置密码
	 * @return [type] [description]
	 */
	public function resetpwd(){
		$this->site_title = '重置密码';
		$this->display();
	}
	/**
	 * 联系管理员
	 */
	public function contact() {
		// 公司邮箱
		$email = M('conf')->where(array('name'=>'companymail'))->getfield('value');
		$this->assign('email', $email);
		$this->site_title = '联系管理员';
		$this->display();
	}
}