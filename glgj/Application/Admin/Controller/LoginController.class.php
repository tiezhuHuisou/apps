<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller{
	/**
	 * 登陆界面
	 * @return [type] [description]
	 */
	public function index() {
	    $conf = M('conf')->where(array('name'=>'webname'))->find();
	    session('webname',$conf['value']);
	    if(IS_POST){
	        $username = I('post.username');
	        $password = trim(I('post.password'));
	        $admin = D('AdminUser');
	        $group = D('AdminGroup');
	        $user =  $admin->getAdminUserInfo(array('user_name'=>$username));
	        $group = $group->getAdminGroupInfo(array('gid'=>$user['gid']));
	        if($group['gname'] != ''){
	           session('uname',$group['gname']);
	        }else{
	            session('uname','admin');
	        }
	        if($user['password'] != md5(md5($password).$user['lin_salt'])){
	            $this->error('账号密码错误');
	        }else{
	            session('name',$username);
	            session('user_id',$user['user_id']);
	            $data['last_ip'] = $_SERVER["REMOTE_ADDR"];
	            $data['last_login'] = time();
	            $admin->where(array('user_name'=>$username))->save($data);
	            $this->success('登录成功','?g=admin&m=index');
	        }
	    }
	    /*页面基本设置*/
	    $this->site_title="登录";
		$this->display();
	}
	/**
	 * 退出
	 * @return [type] [description]
	 */
    public function out(){
		global $Linphp;
		session('name',null);
		session('uname',null);
		session('webname',null);
		session("user_id",null);
		redirect('?g=admin&m=login');
		//$this->success('退出成功','?g=admin&m=login');
	}
}