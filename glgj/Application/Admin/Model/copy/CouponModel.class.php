<?php
namespace Admin\Model;
use Think\Model;
class CouponModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('id', 'require', '非法操作', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('title', 'require', '请填写优惠券名称', self::MUST_VALIDATE),
        array('title', '1,10', '优惠券名称应为1-10个字符', self::MUST_VALIDATE, 'length'),
        array('coupon_type', 'require', '请选择优惠券类型', self::MUST_VALIDATE),
        array('company_id', 'checkCompanyId', '请选择指定商家', self::MUST_VALIDATE, 'callback'),
        array('product_category_id', 'checkProductCategoryId', '请选择指定分类', self::MUST_VALIDATE, 'callback'),
        array('money', 'require', '请填写优惠券面值', self::MUST_VALIDATE),
        array('money', 'money', '优惠券面值格式不正确', self::MUST_VALIDATE),
        array('money', '0', '优惠券面值应大于0', self::MUST_VALIDATE, 'gt'),
        array('condition', 'require', '请填写优惠券使用条件', self::MUST_VALIDATE),
        array('condition', 'money', '优惠券使用条件格式不正确', self::MUST_VALIDATE),
        // array('effect_time', 'require', '请填写优惠券使用期限', self::MUST_VALIDATE),
        // array('effect_time', 'money', '优惠券使用期限格式不正确', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
        array('start_time', 'getStartTime', Model::MODEL_BOTH, 'callback'),
        array('end_time', 'getEndTime', Model::MODEL_BOTH, 'callback'),
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
     * 验证指定商家
     */
    protected function checkCompanyId(){
        $company_id  = I('post.company_id', 0, 'intval');
        $coupon_type = I('post.coupon_type', 0, 'intval');
        if ( $coupon_type == 2 && !$company_id ) {
            return false;
        }
        return true;
    }

    /**
     * 验证指定商家
     */
    protected function checkProductCategoryId(){
        $product_category_id  = I('post.product_category_id', 0, 'intval');
        $coupon_type          = I('post.coupon_type', 0, 'intval');
        if ( $coupon_type == 3 && !$product_category_id ) {
            return false;
        }
        return true;
    }

    /**
     * 获取使用限制开始时间
     */
    protected function getStartTime() {
        if ( I('post.start_time') ) {
            return strtotime(I('post.start_time'));
        }
        return 0;
    }

    /**
     * 获取使用限制结束时间
     */
    protected function getEndTime() {
        if ( I('post.end_time') ) {
            return strtotime(I('post.end_time'));
        }
        return 0;
    }
}