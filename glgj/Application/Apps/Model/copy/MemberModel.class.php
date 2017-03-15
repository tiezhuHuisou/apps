<?php
namespace Apps\Model;
use Think\Model;

class MemberModel extends Model {
    /* 自动验证-注册 */
    protected $_validate = array(
        array('mphone', 'require', '请填写帐号', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('mphone', 'mobile', '帐号应为手机号码', self::MUST_VALIDATE),
        array('mphone', '', '该帐号已被注册', self::MUST_VALIDATE, 'unique', self::MODEL_INSERT),
        array('mphone', 'checkMphone', '帐号不存在', self::MUST_VALIDATE, 'callback', self::MODEL_UPDATE),
        array('password', 'require', '密码不能为空', self::MUST_VALIDATE),
        array('password', '6,18', '密码长度为6-18位字符', self::VALUE_VALIDATE, 'length'),
        array('repassword', 'require', '确认密码不能为空', self::MUST_VALIDATE),
        array('repassword', '6,18', '确认密码长度为6-18位字符', self::VALUE_VALIDATE, 'length'),
        array('repassword', 'password', '两次输入的密码不一致', self::MUST_VALIDATE, 'confirm'),
        array('invite_code', 'checkCode', '邀请码不存在', self::VALUE_VALIDATE, 'callback'),
        array('captcha', 'require', '短信验证码不能为空', self::MUST_VALIDATE),
        array('captcha', 'judgecaptcha', '短信验证码不正确', self::MUST_VALIDATE, 'callback'),
    );

    /* 登陆验证 */
    protected $login_validate = array(
        array('mphone', 'require', '请输入用户名', self::MUST_VALIDATE),
        array('mphone', 'mobile', '用户名应为手机号码', self::MUST_VALIDATE),
        array('password', 'require', '请输入密码', self::MUST_VALIDATE),
        array('password', '6,18', '密码长度为6-18位字符', self::VALUE_VALIDATE, 'length')
    );

    /* 自动完成 */
    protected $_auto = array(
        array('name', 'mphone', Model::MODEL_INSERT, 'field'),
        array('lin_salt', 'createLinSalt', Model::MODEL_BOTH, 'callback'),
        array('regdate', 'time', Model::MODEL_INSERT, 'function'),
        array('addtime', 'time', Model::MODEL_INSERT, 'function'),
        array('pid', 'getPid', Model::MODEL_INSERT, 'callback'),
    );

    /**
     * 用户新增或编辑
     */
    public function update() {
        $data = I('post.');
        /* 判断是否有短信验证 */
        $smsConf = C('SMS_CONFIG');
        if ( $smsConf['isopen'] == 1 ) {
            unset($this->_validate[6], $this->_validate[7], $this->_validate[8]);
        } else {
            array_pop($this->_validate);
            array_pop($this->_validate);
        }
        $this->_validate = array_values($this->_validate);
        
        $data = $this->create($data);
        if ( !$data ) {
            return false;
        }
        /* 密码处理 */
        $data['password'] = md5(md5($data['password']) . $data['lin_salt']);
        $token = md5(md5(time()).mt_rand(100000, 999999));
        if ( !$data['uid'] ) {
            // 新增
            $datas['uuid']  = $token;
            $datas['ctime'] = time();
            $token_id = M('Token')->add($datas);
            $data['token_id'] = $token_id;
            $id = $this->add($data);
            if (!$id) {
                $this->error = '注册失败';
                return false;
            }
            $data['uid'] = $id;
            /* 创建邀请码 */
            $this->where(array('uid'=>$id))->setField('invite_code', 'vip' . date('y') . $id);
        } else {
            /* 更新token */
            $memberInfo  = M('Member')->field('uid,token_id')->where(array('mphone'=>$data['mphone']))->find();
            $memberInfo['token_id'] && M('Token')->where(array('id'=>$memberInfo['token_id']))->setField('uuid', $token);
            $data['uid'] = $memberInfo['uid'];
            $save = $this->save($data);
            if ($save === false) {
                $this->error = '修改失败';
                return false;
            }
        }
        $data['token'] = $token;
        return $data;
    }

    /**
     * 用户登陆
     */
    public function login() {
        $this->_validate = $this->login_validate;
        $this->_auto     = array();
        $data = I('post.');
        $data = $this->create($data);
        if ( !$data ) {
            return false;
        }
        $where['mphone'] = array('eq', $data['mphone']);
        $memberInfo = M('member')->field('uid,gid,name,head_pic,password,lin_salt,token_id,status')->where($where)->find();
        return $memberInfo;
    }

    /**
     * 验证手机验证码
     */
    protected function judgecaptcha() {
        $captcha = I('post.captcha');
        $mphone  = I('post.mphone');
        $map_captcha['mphone']   = array('eq', $mphone);
        $map_captcha['captcha']  = array('eq', $captcha);
        $map_captcha['end_time'] = array('egt', time());

        $captcha_info = M('Captcha')->where($map_captcha)->find();

        if (!$captcha_info) {
            return false;
        }
        return true;
    }

    /**
     * 创建安全密钥
     */
    protected function createLinSalt() {
        return strtoupper(substr(md5(mt_rand(1111111,9999999)),0,6));
    }

    /**
     * 忘记密码时检测帐号是否存在
     */
    protected function checkMphone() {
        $mphone = I('post.mphone');
    }

    /**
     * 验证邀请码是否存在
     */
    protected function checkCode() {
        $invite_code = I('post.invite_code');
        $pid = $this->where(array('invite_code'=>$invite_code))->getField('uid');
        if ( $pid ) {
            return true;
        }
        return false;
    }

    /**
     * 获取上级id
     */
    protected function getPid() {
        $invite_code = I('post.invite_code');
        if ( $invite_code ) {
            $pid = $this->where(array('invite_code'=>$invite_code))->getField('uid');
            if ( $pid ) {
                return $pid;
            }
        }
        return 0;
    }
}
