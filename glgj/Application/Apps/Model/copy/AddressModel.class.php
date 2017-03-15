<?php
namespace Apps\Model;
use Think\Model;
class AddressModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('id', 'checkUid', '参数异常', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('id', 'checkCount', '最多添加5个收货地址', self::MUST_VALIDATE, 'callback', Model::MODEL_INSERT),
        array('name', 'require', '请填写收货人姓名', self::MUST_VALIDATE),
        array('name', 'truename', '收货人姓名格式不正确', self::MUST_VALIDATE),
        array('phone', 'require', '请填写手机号码', self::MUST_VALIDATE),
        array('phone', 'mobile', '手机号码格式不正确', self::MUST_VALIDATE),
        array('region', 'require', '请选择省市区', self::MUST_VALIDATE),
        array('address', 'require', '请填写详细地址', self::MUST_VALIDATE),
        array('address', '1,50', '详细地址长度应为1-50位字符', self::MUST_VALIDATE , 'length')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('owner', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('type', 'judgeType', Model::MODEL_INSERT, 'callback')
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
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }

        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '编辑失败';
                return false;
            }
            $datas['opt'] = '编辑';
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '新增失败';
                return false;
            }
            $datas['id']  = $id;
            $datas['opt'] = '新增';
        }
        return $datas;
    }

    /**
     * 删除数据
     * @param  array $condition 删除条件
     * @author 406764368@qq.com
     * @return bool 布尔类型的返回结果
     */
    public function del($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 检测用户id，判断收货地址是否为当前用户的收货地址
     */
    protected function checkUid() {
        $id             = I('post.id', 0, 'intval');
        $owner          = $this->getUid();
        $where['id']    = array('eq', $id);
        $where['owner'] = array('eq', $owner);
        $address_id     = $this->where($where)->getField('id');
        if ( !$address_id ) {
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
     * 第一次添加收货地址，自动设置为默认地址
     */
    protected function judgeType() {
        $uid = $this->getUid();
        $address_id = $this->where(array('owner'=>$uid))->getField('id');
        if ( $address_id ) {
            return 0;
        }
        return 1;
    }

    /**
     * 最多5个收货地址
     */
    public function checkCount() {
        $uid = $this->getUid();
        $count = $this->where(array('owner'=>$uid))->count('id');
        if ( $count >= 5 ) {
            return false;
        }
        return true;
    }
}