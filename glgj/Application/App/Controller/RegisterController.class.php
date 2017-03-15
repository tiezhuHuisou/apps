<?php
namespace App\Controller;

use Think\Controller;

class RegisterController extends Controller {

    /**
     * 会员注册页面
     * @return [type] [description]
     */
//	public function index(){
//		/* 页面基本设置 */
//        $this->site_title 		= "会员注册";
//        $this->site_keywords 	= "会员注册";
//        $this->site_description = "会员注册";
//
//		$this->display();
//	}


    /**
     * 注册成为会员
     */
    public function index() {
        if (IS_POST) {
            $model = array();
            $model = D('Member');
            $memberInfo = $model->update();
            if ($memberInfo) {
                $user_auth = array('user_id' => $memberInfo['uid'],);
                session('user_auth', $user_auth);
                $this->success('登录成功', '?g=app&m=member');
            } else {
                $this->error($model->getError());
            }
        } else {
            /* 页面基本设置 */
            $this->site_title = "会员注册";
            $this->site_keywords = "会员注册";
            $this->site_description = "会员注册";
            $this->display();
        }

    }

    /**
     * 忘记密码
     */
    public function forgotten() {
        if (IS_POST) {
            $model = array();
            $model = D('Member');
            $memberInfo = $model->update();
            if ($memberInfo) {
                $user_auth = array('user_id' => $memberInfo['uid'],);
                session('user_auth', $user_auth);
                $this->success('登录成功', '?g=app&m=member');
            } else {
                $this->error('注册失败');
            }
        } else {

            /* 页面基本设置 */
            $this->site_title = "忘记密码";
            $this->site_keywords = "忘记密码";
            $this->site_description = "忘记密码";
            $this->display();
        }

    }

    /**
     * 注册协议
     * @return [type] [description]
     */
    public function agreement() {
        $map['linkid'] = array('eq', 'link-explain');
        $info = M('Page')->where($map)->find();
        $info['content'] = stripslashes($info['content']);
        $count = preg_match_all('/\{\#([\w]+)\#\}/isU', $info['content'], $match);
        $content = $info['content'];
        if ($count > 0) {
            $where_sql = implode(",", $match[1]);
            $where['name'] = array('in', $where_sql);
            $sql = M('Conf')->where($where)->select();
            foreach ($sql as $key => $val) {
                $content = preg_replace('/\{\#' . $val['name'] . '\#\}/isU', $val['value'], $content);
            }
        }
        $content = str_replace('&gt;', '>', $content);
        $content = str_replace('&lt;', '<', $content);
        $content = str_replace('&amp;', '&', $content);
        $info['content'] = $content;
        $this->assign('info', $info);
        /* 页面基本设置 */
        $this->site_title = "服务协议";
        $this->site_keywords = "服务协议";
        $this->site_description = "服务协议";

        $this->display();
    }
}