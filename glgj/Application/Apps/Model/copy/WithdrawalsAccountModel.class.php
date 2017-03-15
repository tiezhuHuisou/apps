<?php
namespace Apps\Model;
use Think\Model;
class WithdrawalsAccountModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('type', '1,2', '参数错误', self::MUST_VALIDATE, 'between'),
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkid', '要修改的账户不存在', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('account', 'require', '请填写帐号', self::MUST_VALIDATE),
        array('account', '1,30', '帐号最多30位数', self::MUST_VALIDATE, 'length'),
        array('truename', 'require', '请填写姓名', self::MUST_VALIDATE),
        array('truename', 'truename', '姓名格式不正确', self::MUST_VALIDATE)
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('img', 'getImg', Model::MODEL_INSERT, 'callback'),
    );

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas = $_POST;
        if ( $_POST['type'] == 2 ) {
            $this->_validate[] = array('bank_card', 'require', '请选择银行卡类型', self::MUST_VALIDATE);
            $this->_validate[] = array('bank_address', 'require', '请填写银行卡开户行地址', self::MUST_VALIDATE);
            $this->_validate[] = array('bank_address', '1,200', '银行卡开户行地址最多填写200个字符', self::MUST_VALIDATE, 'length');
        }
        /* 获取云通讯配置参数 */
        $smsConfig = C('SMS_CONFIG');
        /* 短信功能标识 */
        if ( $smsConfig['isopen'] == 1 ) {
            $this->_validate[] = array('captcha', 'require', '请填写短信验证码', self::MUST_VALIDATE);
            $this->_validate[] = array('captcha', 'checkCaptcha', '短信验证码错误', self::MUST_VALIDATE, 'callback');
        }
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '添加失败';
                return false;
            }
            $datas['id'] = $id;
        }
        return $datas;
    }

    /**
     * 取得单条记录
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 406764368@qq.com
     * @return 返回一条满足条件的记录
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 删除数据
     * @param  array $condition 删除条件
     * @author 406764368@qq.com
     * @return bool 布尔类型的返回结果
     */
    public function del($condition) {
        $uid = $this->getUid();
        $condition['uid'] = array('eq', $uid);
        return $this->where($condition)->delete();
    }

    /**
     * 检测要点赞资讯是否存在
     */
    protected function checkid() {
        $id = I('post.id', 0, 'intval');
        $result = $this->where(array('id'=>$id))->getField('id');
        if ( !$result ) {
            return false;
        }
        return true;
    }

    /**
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }

    /**
     * 获取图片
     */
    protected function getImg() {
        $type      = I('post.type');
        $bank_card = I('post.bank_card');
        $bankImgList = array(
            '中国银行' => C('HTTP_APPS_IMG') . 'zhongguoyinhang.png',
            '工商银行' => C('HTTP_APPS_IMG') . 'gongshangyinhang.png',
            '农业银行' => C('HTTP_APPS_IMG') . 'nongyeyinhang.png',
            '交通银行' => C('HTTP_APPS_IMG') . 'jiaotongyinhang.png',
            '招商银行' => C('HTTP_APPS_IMG') . 'zhaoshangyinhang.png',
            '建设银行' => C('HTTP_APPS_IMG') . 'jiansheyinhang.png',
            '邮政储蓄' => C('HTTP_APPS_IMG') . 'youzhengyinhang.png',
            '浦发银行' => C('HTTP_APPS_IMG') . 'pufayinghang.png',
            '广发银行' => C('HTTP_APPS_IMG') . 'guangfayinhang.png',
            '民生银行' => C('HTTP_APPS_IMG') . 'mingshengyinhang.png',
            '平安银行' => C('HTTP_APPS_IMG') . 'pinganyinhang.png',
            '光大银行' => C('HTTP_APPS_IMG') . 'guangdayinghang.png',
            '兴业银行' => C('HTTP_APPS_IMG') . 'xingyeyinghang.png',
            '中信银行' => C('HTTP_APPS_IMG') . 'zhongxinyinghang.png',
            '上海银行' => C('HTTP_APPS_IMG') . 'shanghaiyinghang.png',
            '宁波银行' => C('HTTP_APPS_IMG') . 'ningboyinghang.png',
            '江苏银行' => C('HTTP_APPS_IMG') . 'jiangsuyinghang.png',
            '浙江农信' => C('HTTP_APPS_IMG') . 'zhejiangnongxin.png',
            '浙商银行' => C('HTTP_APPS_IMG') . 'zheshangyinghang.png',
        );
        if ( $type == 2 ) {
            return $bankImgList[$bank_card];
        } else {
            return C('HTTP_APPS_IMG') . 'zhihubao.png';
        }
    }

    /**
     * 验证短信验证码
     */
    protected function checkCaptcha() {
        $mobile  = I('post.mobile');
        $captcha = I('post.captcha');
        $map_captcha['mphone']   = array('eq', $mobile);
        $map_captcha['captcha']  = array('eq', $captcha);
        $map_captcha['end_time'] = array('egt', time());
        $captcha_info = M('Captcha')->where($map_captcha)->find();
        if ( !$captcha_info ) {
            return false;
        }
        return true;
    }
}