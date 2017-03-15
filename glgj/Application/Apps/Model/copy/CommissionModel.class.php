<?php
namespace Apps\Model;
use Think\Model;
class CommissionModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'checkid', '账户不存在', self::VALUE_VALIDATE, 'callback'),
        array('commission', 'require', '请填写可提现金额', self::MUST_VALIDATE),
        array('commission', 'money', '可提现金额格式不正确', self::MUST_VALIDATE),
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_BOTH, 'callback'),
        array('source_uid', 'getUid', Model::MODEL_BOTH, 'callback'),
        array('withdrawal_type', 'getType', Model::MODEL_BOTH, 'callback'),
        array('ctime', 'time', Model::MODEL_BOTH, 'function'),
        array('status', 2, Model::MODEL_BOTH),
        array('remark', '用户申请提现', Model::MODEL_BOTH)
    );

    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update($commission_rest){
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        /* 验证提现金额是否大于可提现金额 */
        if ( $datas['commission'] > $commission_rest ) {
            $this->error = '最多可提现' . $commission_rest . '元';
            return false;
        }
        /* 提现账户id */
        $datas['account_id'] = $datas['id'] ? intval($datas['id']) : 0;
        unset($datas['id']);
        /* 新增 */
        $id = $this->add($datas);
        if ( !$id ) {
            $this->error = '提现申请失败';
            return false;
        }
        $datas['id'] = $id;
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
     * 检测提现账户是否存在
     */
    protected function checkid() {
        $id  = I('post.id', 0, 'intval');
        $uid = $this->getUid();
        $where['uid'] = array('eq', $uid);
        $where['id']  = array('eq', $id);
        $result = M('WithdrawalsAccount')->where($where)->getField('id');
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
     * 获取提现方式
     * 提现方式：0未提现，1微信，2支付宝，3转余额，4银行卡
     */
    protected function getType() {
        $id = I('post.id');
        if ( $id ) {
            /* 获取提现账户类型：1支付宝账户；2银行卡账户 */
            $uid = $this->getUid();
            $where['uid'] = array('eq', $uid);
            $where['id']  = array('eq', $id);
            $type =  M('WithdrawalsAccount')->where($where)->getField('type');
            return $type == 1 ? 2 : 4;
        } else {
            return 3;
        }
    }
}