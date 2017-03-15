<?php
namespace Member\Controller;
use Think\Controller;
class LoginController extends Controller {
	/**
	 * 登陆页面
	 * @return [type] [description]
	 */
	public function index(){
		// $this->error('用户后台已关闭，所有操作都可以在手机上进行');
	    if(IS_POST){
	        $post=I('post.');

	        if(!$post['mphone']){
	            $this->error('手机号码不能为空');
	        }
	        if(!$post['password']){
	            $this->error('密码不能为空');
	        }
	        $map['mphone']=array('eq',$post['mphone']);
	        $user_info=M('Member')->where($map)->find();
	        if(!$user_info){
	            $this->error('账号不存在');
	        }
	        if(md5(md5($post['password']).$user_info['lin_salt'])!=$user_info['password']){
	            $this->error('密码不正确');
	        }
	    
	        $data['utoken']=md5(time().rand(1111,9999));
	        $status=M('Member')->where(array('uid'=>$user_info['uid']))->save($data);

	        if($status){
	            $user_auth=array(
	                'user_id'=>$user_info['uid'],
	                'utoken'=>$data['utoken']
	            );
	            session('user_name',$user_info['nickname']);
	            session('user_auth',$user_auth); //网站头部用户昵称
	            if(!empty($post['remember'])){     //如果用户选择了，记录登录状态就把用户名和加了密的密码放到cookie里面
	                setcookie("username", $post['mphone'], time()+3600*24*365);
	                setcookie("password", $post['password'], time()+3600*24*365);
	            }
	            $this->success('登录成功','?g=member');
	        }else{
	            $this->error('登录失败');
	        }
	        	
	        	
	    }else{
	    
		$this->site_title = '登陆';
		$this->display();
	    }
	}
	/**
	 * 退出登陆
	 * @return [type] [description]
	 */
	public function logout(){
	    session('user_auth',null);
	    session('user_name',null);
	    if(!empty($_COOKIE['username']) || !empty($_COOKIE['password'])){
	        setcookie("username", null, time()-3600*24*365);
	        setcookie("password", null, time()-3600*24*365);
	    }
	    redirect('?g=member&m=login');
	}
}