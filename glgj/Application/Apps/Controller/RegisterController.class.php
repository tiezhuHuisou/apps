<?php
namespace Apps\Controller;

use Think\Controller;

class RegisterController extends ApiController {
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 用户注册接口
     * @param mphone
     * @param password
     * @param repassword
     * @param captcha  选填
     */
    public function register() {
        if (IS_POST) {
            $memberModel = D('member');
            $memberInfo = $memberModel->update();
            if (!$memberInfo) {
                $this->ajaxJson('70000', $memberModel->getError());
            } else {
                $list['token'] = $memberInfo['token'];

                if (C('RONGCLOUDAPPSECRET') && C('RONGCLOUDAPPKEY')) {
                    /* 获取融云token */
                    $memberInfo['uid'] = 'm_' . $memberInfo['uid'];
                    $memberInfo['head_pic'] = $this->getAbsolutePath($memberInfo['head_pic'], '', C('HTTP_APPS_IMG') . 'member_default.png');

                    $post = 'userId=' . $memberInfo['uid'] . '&name=' . $memberInfo['name'] . '&portraitUri=' . $memberInfo['head_pic'];

                    /* 重置随机数种子 */
                    srand((double)microtime() * 1000000);

                    /* 开发者平台分配的 App Secret */
                    $appSecret = C('RONGCLOUDAPPSECRET');
                    $nonce = rand();
                    $timestamp = time();
                    $signature = sha1($appSecret . $nonce . $timestamp);

                    /* 请求融云 */
                    $curl = curl_init('http://api.cn.ronghub.com/user/getToken.json');
                    curl_setopt($curl, CURLOPT_HEADER, 0);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    $header = array();
                    $header[] = 'App-Key: ' . C('RONGCLOUDAPPKEY');
                    $header[] = 'Nonce: ' . $nonce;
                    $header[] = 'Timestamp: ' . $timestamp;
                    $header[] = 'Signature: ' . $signature;
                    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                    $result = curl_exec($curl);
                    curl_close($curl);
                    $result = json_decode($result, true);
                    if ($result['code'] != 200) {
                        $result = false;
                    }
                }

                $list['rtoken'] = $result ? $result['token'] : '';
                $list['ruid'] = $result ? $result['userId'] : '';
                $this->returnJson($list, '40000', '注册成功');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 发送短信验证码
     * @param mphone
     */
    public function smsVerify() {
        if (IS_POST) {
            /* 获取云通讯配置参数 */
            $smsConfig = C('SMS_CONFIG');
            /* 判断是否开通短信功能 */
            $smsConfig['isopen'] != 1 && $this->ajaxJson('70000', '短信功能未开通');
            /* 接收参数并验证 */
            $mobile = I('post.mphone');
            if (empty($mobile)) {
                $this->ajaxJson('70000', '手机号码不能为空');
            }
            if (!checkMobile($mobile)) {
                $this->ajaxJson('70000', '手机号码格式不正确');
            }
            /* 随机验证码 */
            $captcha = mt_rand(100000, 999999);
            /* 验证码有效时间 */
            $limitTime = 300;
            /* 保存验证码 */
            $map['mphone'] = array('eq', $mobile);
            $res = M('captcha')->where($map)->find();
            $data['add_time'] = time();
            $data['end_time'] = time() + $limitTime;
            $data['captcha'] = $captcha;
            if ($res) {
                $condition['id'] = array('eq', $res['id']);
                M('Captcha')->where($condition)->save($data);
            } else {
                $data['mphone'] = $mobile;
                M('Captcha')->add($data);
            }
            /* 发送数据 */
            $sendDatas = array($captcha, $limitTime);
            /* 发送短信模版 */
            $result = $this->smsSend($mobile, $sendDatas, 'smsTemplateId');
            /* 处理返回信息 */
            if (empty($result)) {
                $this->ajaxJson('70000', '发送失败');
            } elseif ($result->statusCode != 0) {
                $result = (array)$result;
                $this->ajaxJson('70000', $result['statusMsg']);
            } else {
                $this->ajaxJson('40000', '发送成功');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 忘记密码
     * @param uid  恒为1
     * @param mphone
     * @param password
     * @param captcha
     */
    public function forgetPassword() {
        if (IS_POST) {
            $memberModel = D('member');
            $memberInfo = $memberModel->update();
            if (!$memberInfo) {
                $this->ajaxJson('70000', $memberModel->getError());
            } else {
                $list['token'] = $memberInfo['token'];
                $this->ajaxJson('40000', '修改成功');
            }
        }
        $conf = M('Conf')->getField('name,value', true);
        $list['companymail'] = $conf['companymail'];
        $list['companphone'] = $conf['companphone'];
        $this->returnJson($list);
    }

}