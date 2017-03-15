<?php
namespace Apps\Controller;

use Think\Controller;

class LoginController extends ApiController {
    /**
     * 用户登录接口
     * @param mphone
     * @param password
     */
    public function login() {
        if (IS_POST) {
            $post = I('post.');
            $memberModel = D('Member');
            $memberInfo = $memberModel->login();
            /* 不合法的数据 */
            if ($memberInfo === false) {
                $this->ajaxJson('70000', $memberModel->getError());
            }
            /* 用户不存在 */
            if (!$memberInfo) {
                $this->ajaxJson('40001', '用户不存在');
            }
            /* 密码错误 */
            if ($memberInfo['password'] != md5(md5($post['password']) . $memberInfo['lin_salt'])) {
                $this->ajaxJson('40002', '密码错误');
            }
            /* 帐号异常 */
            if ($memberInfo['status'] != 1) {
                $this->ajaxJson('40003', '帐号异常');
            }
            /* 生成token */
            $token = md5(md5(time() . mt_rand(100000, 999999)) . $memberInfo['lin_salt']);
            if ($memberInfo['token_id']) {
                $data_token['uuid'] = $token;
                $data_token['ctime'] = time();
                $tokenStatus = M('Token')->where(array('id' => $memberInfo['token_id']))->find();
                if (!$tokenStatus) {
                    $data_token['id'] = $memberInfo['token_id'];
                    $id = M('Token')->add($data_token);
                } else {
                    M('Token')->where(array('id' => $memberInfo['token_id']))->save($data_token);
                }

            } else {
                $data_new['uuid'] = $token;
                $data_new['ctime'] = time();
                $id = M('Token')->add($data_new);
                M('member')->where(array('uid' => $memberInfo['uid']))->setField('token_id', $id);
            }
            $list['token'] = $token;

            if (C('RONGCLOUDAPPSECRET') && C('RONGCLOUDAPPKEY')) {
                /* 获取融云token */
                if ($memberInfo['gid'] == 2) {
                    $companyInfo = M('Company')->field('id,name,logo')->where(array('user_id' => $memberInfo['uid'], 'status' => 1))->find();
                    if ($companyInfo) {
                        $memberInfo['uid'] = 'c_' . $companyInfo['id'];
                        $memberInfo['name'] = $companyInfo['name'];
                        $memberInfo['head_pic'] = $companyInfo['logo'];
                    } else {
                        $memberInfo['uid'] = 'm_' . $memberInfo['uid'];
                    }
                } else {
                    $memberInfo['uid'] = 'm_' . $memberInfo['uid'];
                }
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

            $this->returnJson($list, '40000', '登陆成功');
        }
        $this->ajaxJson('70001');
    }
}