<?php
namespace Apps\Model;
use Think\Model;
class CouponModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('id', 'require', '非法操作', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkNum', '最多添加3张优惠券', self::MUST_VALIDATE, 'callback', Model::MODEL_INSERT),
        array('title', 'require', '请填写优惠券名称', self::MUST_VALIDATE),
        array('title', '1,10', '优惠券名称应为1-10个字符', self::MUST_VALIDATE, 'length'),
        array('money', 'require', '请填写优惠券面值', self::MUST_VALIDATE),
        array('money', 'money', '优惠券面值格式不正确', self::MUST_VALIDATE),
        array('money', '0', '优惠券面值应大于0', self::MUST_VALIDATE, 'gt'),
        array('condition', 'require', '请填写优惠券使用条件', self::MUST_VALIDATE),
        array('condition', 'money', '优惠券使用条件格式不正确', self::MUST_VALIDATE),
        array('issue_num', 'number', '发放数量格式不正确', self::VALUE_VALIDATE),
        // array('end_time', 'start_time', '失效时间应大于生效时间', self::VALUE_VALIDATE, 'gt'),
        // array('receive_end', 'receive_start', '领取结束时间应大于领取开始时间', self::VALUE_VALIDATE, 'gt'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
        array('coupon_type', 2, Model::MODEL_INSERT),
        // array('start_time', 'getStartTime', Model::MODEL_BOTH, 'callback'),
        // array('end_time', 'getEndTime', Model::MODEL_BOTH, 'callback'),
        // array('receive_start', 'getReceiveStartTime', Model::MODEL_BOTH, 'callback'),
        // array('receive_end', 'getReceiveEndTime', Model::MODEL_BOTH, 'callback'),
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
     * 获取使用限制开始时间
     */
    protected function getStartTime() {
        if ( $_POST['start_time'] ) {
            return strtotime($_POST['start_time']);
        }
        return 0;
    }

    /**
     * 获取使用限制结束时间
     */
    protected function getEndTime() {
        if ( $_POST['end_time'] ) {
            return strtotime($_POST['end_time']);
        }
        return 0;
    }

    /**
     * 获取领取限制开始时间
     */
    protected function getReceiveStartTime() {
        if ( $_POST['receive_start'] ) {
            return strtotime($_POST['receive_start']);
        }
        return 0;
    }

    /**
     * 获取领取限制结束时间
     */
    protected function getReceiveEndTime() {
        if ( $_POST['receive_end'] ) {
            return strtotime($_POST['receive_end']);
        }
        return 0;
    }

    /**
     * 判断优惠券数量
     */
    protected function checkNum() {
        /* 获取用户信息 */
        $where['status']      = array('eq', 1);
        $where['receive_end'] = array('egt', time());
        $where['company_id']  = array('eq', $_POST['company_id']);
        $count = $this->where($where)->count('id');
        if ( $count < 3 || !$count ) {
            return true;
        }
        return false;
    }
}