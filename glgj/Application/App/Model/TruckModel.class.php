<?php
namespace App\Model;

use Think\Model;

class TruckModel extends Model {
    /* 自动验证 */
    protected $_validate = array(
        array('uid', 'require', '请先登录', self::MUST_VALIDATE),
        array('mphone', 'checkUid', '帐号不存在', self::MUST_VALIDATE, 'callback'),
        array('departure_time', 'require', '发车时间不能为空', self::MUST_VALIDATE),
        array('end_date', 'require', '有效日期不能为空', self::MUST_VALIDATE),
        array('truck_type', 'require', '请选择车辆类型', self::MUST_VALIDATE),
        array('truck_num', 'require', '请选择车辆数量', self::MUST_VALIDATE),
        array('truck_length', 'require', '请选择车辆长度', self::MUST_VALIDATE),
        array('source_type', 'require', '请选择车源类型', self::MUST_VALIDATE),
        array('provincen', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('cityn', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('provincen2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('cityn2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('driver', 'require', '请现写随车司机', self::MUST_VALIDATE),
        array('driver_phone', 'require', '请现写随车号码', self::MUST_VALIDATE),
//        array('driver_phone', 'require', '请现写随车号码', self::MUST_VALIDATE),
        array('truck_no', 'require', '请现写车牌号码', self::MUST_VALIDATE),
//        array('p_area', 'require', '请添加标注参数', self::MUST_VALIDATE),
//        array('address', 'require', '请填写地址', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array(
        //array('flags', 'get_flags', Model::MODEL_BOTH, 'callback'),
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
        array('departure_time', 'getDepartureTime', Model::MODEL_BOTH, 'callback'),
        array('end_date', 'getEndTime', Model::MODEL_BOTH, 'callback'),
        array('uid', 'getUid', Model::MODEL_BOTH, 'callback'),
    );

    /**
     * 查询单条记录
     * @param array $condition 查询条件
     * @param array $field 查询字段
     */
    public function getOneInfo($condition = array(), $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 添加、编辑
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if (empty($datas)) {
            return false;
        }
        /* 添加或更新 */
        if ($datas['id'] > 0) {
            /* 修改 */
            $save = $this->save($datas);
            if ($save === false) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 添加 */
            $id = $this->add($datas);
            if (!$id) {
                $this->error = '添加失败';
                return false;
            }
        }
        return $datas;
    }

    /**
     * 删除数据
     * @param array $condition 删除条件
     */
    public function del($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 获取发布人类型 1->个人会员 2->企业会员
     * @return [type] [description]
     */
    protected function getIssueType() {
        $uid = I('post.company_id', 0, 'intval');
        $gid = M('Member')->where(array('uid' => $uid))->getField('gid');
        return $gid;
    }

    /**
     * 检查用户uid
     */
    protected function checkUid() {
        $mphone = trim($_POST['mphone']);

        $uid = M('Member')->where(array('mphone' => $mphone))->getField('uid');
        if ($uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将时间改为unix时间戳
     */
    protected function getDepartureTime() {
        return strtotime($_POST['departure_time']);
    }

    /**
     * 将时间改为unix时间戳
     */
    protected function getEndTime() {
        return strtotime($_POST['end_date']);
    }

    /**
     * 通过手机号寻找账户id
     */
    protected function getUid() {
        $mphone = trim($_POST['mphone']);
        $uid = M('Member')->where(array('mphone' => $mphone))->getField('uid');
        if ($uid) {
            return $uid;
        } else {
            return false;
        }
    }
}