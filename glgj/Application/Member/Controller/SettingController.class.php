<?php
namespace Member\Controller;

use Think\Controller;

class SettingController extends MemberController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->assign('site', 'setting');
    }

    /**
     * 个人资料
     * @return [type] [description]
     */
    public function index()
    {
        if (IS_POST) {
            $status = D("Member")->update();
            if ($status) {
                $this->success('保存个人资料成功');
            } else {
                $errorInfo = D("Member")->getError();
                $this->error($errorInfo);
            }
        }
        /* 个人资料 */
        $model = D('Member');
        $condition['uid'] = array('eq', UID);
        $info = $model->getMemberInfo($condition);
        $this->assign('info', $info);

        /*页面基本设置*/
        $this->site_title = '个人资料';
        $this->assign('header', 'index');
        $this->display();
    }

    /**
     * 修改密码
     * @return [type] [description]
     */
    public function resetpwd()
    {
        if (IS_POST) {
            $password = I('pass');
            $password1 = I('password');
            $password2 = I('password1');
            if (!$password || !$password1 || !$password2) {
                $this->error('密码不能为空');
            }
            if ($password1 != $password2) {
                $this->error('两次密码不一致');
            }
            if ($password1 == $password) {
                $this->error('新密码与旧密码相同');
            }
            $map['uid'] = array('eq', UID);
            $user_info = M('Member')->where($map)->find();
            if (md5(md5($password) . $user_info['lin_salt']) != $user_info['password']) {
                $this->error('原始密码不正确');
            }
            $data['password'] = md5(md5($password1) . $user_info['lin_salt']);
            $status = M("Member")->where($map)->save($data);
            if ($status) {
                $this->success('修改成功', '?g=member&m=login&a=logout');
            } else {
                $errorInfo = D("Member")->getError();
                $this->error($errorInfo);
            }
        } else {

            /*页面基本设置*/
            $this->site_title = '修改密码';
            $this->assign('header', 'resetpwd');
            $this->display();
        }
    }
}