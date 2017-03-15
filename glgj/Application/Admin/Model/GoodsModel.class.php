<?php
namespace Admin\Model;

use Think\Model;

class GoodsModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('mphone', 'checkUid', '帐号不存在', self::MUST_VALIDATE, 'callback'),
        array('provincen', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('cityn', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('provincen2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('cityn2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('name', 'require', '请现写货物名称', self::MUST_VALIDATE),
        array('transport_id', 'require', '请选择运输方式', self::MUST_VALIDATE),
        array('category_id', 'require', '货物类型', self::MUST_VALIDATE),
        array('deliver_time', 'require', '请选择发货日期', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array(
        //array('flags', 'get_flags', Model::MODEL_BOTH, 'callback'),
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
        array('deliver_time', 'getDeliverTime', Model::MODEL_BOTH, 'callback'),
        array('end_time', 'getEndTime', Model::MODEL_BOTH, 'callback'),
        array('uid', 'getUid', Model::MODEL_BOTH, 'callback'),
    );

    /**
     * 取得单个商品信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getOneInfo($condition = array(), $field = "*") {
        return $this->where($condition)->field($field)->find();
    }

    /**
     * 添加/编辑商品
     * @author 83961014@qq.com
     */
    public function update() {
        /* 获取数据对象 */
        $data = array();

        $data = $this->create($_POST);

        if (empty($data)) {
            return false;
        }
        /* 添加或更新 */
        if (empty($data['id'])) { //新增
            $id = $this->add($data);
            if (!$id) {
                $this->error = '新增出错！';
                return false;
            }
            $data['id'] = $id;
        } else { //更新

            $status = $this->save($data);
            if (false === $status) {
                $this->error = '更新出错！';
                return false;
            }
        }

        return $data;
    }

    /**
     * 删除网点
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delDate($condition) {
        return $this->where($condition)->delete();
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


    /**
     * 封装flags字段
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_flags() {
        $flags = I('post.flags', '');
        if (!empty($flags)) {
            $flags = implode(',', $flags);
            return $flags;
        } else {
            return $flags = "";
        }
    }

    /**
     * 将时间改为unix时间戳
     */
    protected function getDeliverTime() {
        return strtotime($_POST['deliver_time']);
    }

    /**
     * 将时间改为unix时间戳
     */
    protected function getEndTime() {
        if (empty($_POST['end_date'])) {
            return 0;
        } else {
            return strtotime($_POST['end_date']);
        }

    }

}