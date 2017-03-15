<?php
namespace App\Controller;

use Think\Controller;

class LoginController extends Controller {
    /**
     * 会员登陆页面
     * @return [type] [description]
     */
    public function index() {
        if (IS_POST) {
            $model = array();
            $model = D('Member');
            $memberInfo = $model->login();
            if ($memberInfo) {
                $user_auth = array('user_id' => $memberInfo['uid'],);
                session('user_auth', $user_auth);
                $this->success('登录成功', '?g=app&m=member');
            } else {
                $this->error('登录失败');
            }
        } else {
            /* 页面基本设置 */
            $this->site_title = "会员登陆";
            $this->site_keywords = "会员登陆";
            $this->site_description = "会员登陆";
            $this->display();
        }
    }

    /**
     * 退出登陆
     * @return [type] [description]
     */
    public function logout() {
        session('user_auth', null);
        redirect('?g=app&m=login');
        /* 页面基本设置 */
        $this->site_title = "退出登陆";
        $this->site_keywords = "退出登陆";
        $this->site_description = "退出登陆";
        $this->display();
    }
}