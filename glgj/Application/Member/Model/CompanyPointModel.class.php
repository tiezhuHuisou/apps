<?php
namespace Member\Model;

use Think\Model;

class CompanyPointModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('c_id', 'require', '参数错误', self::MUST_VALIDATE),

        array('p_title', 'require', '店名不能为空', self::MUST_VALIDATE),
        array('p_title', '2,48', '店名长度为2-64位字符', self::MUST_VALIDATE, 'length'),

        array('p_name', 'require', '联系人不能为空', self::MUST_VALIDATE),
        array('p_name', '2,48', '联系人长度为2-64位字符', self::MUST_VALIDATE, 'length'),

        array('p_mobile', 'require', '请填写手机号', self::MUST_VALIDATE),
        array('p_mobile', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '手机号格式不正确', self::MUST_VALIDATE, 'regex'),

        array('province_id', 'require', '请选择网点所在省', self::MUST_VALIDATE),

        array('city_id', 'require', '请选择所在市', self::MUST_VALIDATE),

        array('address', 'require', '请填写地址', self::MUST_VALIDATE),
        array('address', '2,48', '请填写地址应长度为2-64位字符', self::MUST_VALIDATE, 'length'),
    );
    /*自动完成*/
    protected $_auto = array(
        array('p_addtime', 'time', Model::MODEL_INSERT, 'function'),
        array('p_area', 'getArea', Model::MODEL_BOTH, 'function'),
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
        $data = $_POST;
        $data = $this->create($data);
        if (empty($data)) {
            return false;
        }
        /* 添加或更新 */
        if (empty($data['p_id'])) { //新增
            $id = $this->add($data);
            if (!$id) {
                $this->error = '新增出错！';
                return false;
            }
            $data['p_id'] = $id;
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
    public function delInfo($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 拼接area字段
     * @return string
     * @author 83961014@qq.com
     */
    protected function getArea() {
        $post = I('post', '', 'strval');
        if (empty($post['lng']) || empty($post['lat'])) {
            $this->error = '请在地图上标注位置信息';
            return false;
        }
        $area = $post['lng'] . ',' . $post['lat'];
        return $area;
    }

}