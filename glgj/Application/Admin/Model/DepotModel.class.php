<?php
namespace Admin\Model;

use Think\Model;

class DepotModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('mphone', 'checkUid', '帐号不存在', self::MUST_VALIDATE, 'callback'),
        array('province_id', 'require', '请选择所在地', self::MUST_VALIDATE),
        array('city_id', 'require', '请选择所在地', self::MUST_VALIDATE),
        array('address', 'require', '填写详细地址', self::MUST_VALIDATE),
        array('category_id', 'require', '请选择仓储类型', self::MUST_VALIDATE),
        array('height', 'require', '请填写仓内高度', self::MUST_VALIDATE),
        array('price', 'require', '请填写单价', self::MUST_VALIDATE),
        array('area', 'require', '请填写总面积', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array(
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),

//        array('flags', 'get_flags', Model::MODEL_BOTH, 'callback'),
//        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
//        array('p_addtime', 'time', Model::MODEL_INSERT, 'function'),
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

}