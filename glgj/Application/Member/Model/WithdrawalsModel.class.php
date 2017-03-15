<?php
namespace Member\Model;
use Think\Model;
class WithdrawalsModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('cid', 'require', '参数错误', self::MUST_VALIDATE),
        array('cname', 'require', '参数错误', self::MUST_VALIDATE),
        array('balance', 'require', '参数错误', self::MUST_VALIDATE),
        array('balance', '0', '参数错误', self::MUST_VALIDATE, 'egt'),
        array('withdrawals', 'require', '请填写提现金额', self::MUST_VALIDATE),
        array('withdrawals', '0', '提现金额格式不正确', self::MUST_VALIDATE, 'gt'),
        array('withdrawals', 'money', '提现金额格式不正确', self::MUST_VALIDATE),
        array('withdrawals', 'checkWithdrawals', '可提现金额不足', self::MUST_VALIDATE, 'callback'),
        array('type', '1,3', '提现方式异常', self::MUST_VALIDATE, 'between'),
        array('task', 'checkTaskRequire', '请填写提现账号', self::MUST_VALIDATE, 'callback'),
        array('task', '1,30', '提现账号最多可填写30位字符', self::VALUE_VALIDATE, 'length'),
        array('payee', 'checkPayeeRequire', '请填写收款人姓名', self::MUST_VALIDATE, 'callback'),
        array('payee', 'truename', '收款人姓名格式不正确', self::VALUE_VALIDATE)
    );

    /* 自动完成 */
    protected $_auto = array (
        array('ctime', 'time', Model::MODEL_INSERT, 'function'),
        array('etime', 'time', Model::MODEL_BOTH, 'function')
    );

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
     * 添加、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        /* 添加或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 添加 */
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
     * 删除数据
     * @param  array $condition 删除条件
     * @author 406764368@qq.com
     * @return bool 布尔类型的返回结果
     */
    public function del($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 验证申请提现金额是否超出可提现金额
     */
    protected function checkWithdrawals() {
        if ( I('post.withdrawals') > I('post.balance') ) {
            return false;
        }
        return true;
    }

    /**
     * 验证收款帐号，转入余额时可不填
     */
    protected function checkTaskRequire() {
        $type = I('post.type');
        $task = I('post.task');
        if ( $type != 3 && empty($task) ) {
            return false;
        }
        return true;
    }

    /**
     * 验证收款人姓名，转入余额时可不填
     */
    protected function checkPayeeRequire() {
        $type  = I('post.type');
        $payee = I('post.payee');
        if ( $type != 3 && empty($payee) ) {
            return false;
        }
        return true;
    }
}