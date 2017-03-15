<?php
namespace Member\Model;
use Think\Model;
class ProductSupplyModel extends Model {
    /* 自动验证 */
    protected $_validate = array(
        array('title', 'require', '请填写标题', self::MUST_VALIDATE),
        array('title', '2,128', '标题应为2-128个字符', self::MUST_VALIDATE, 'length'),
        array('price', 'require', '请填写供应价', self::MUST_VALIDATE),
        array('price', '/^\d+(\.\d{1,2})?$/', '请填写有效的供应价', self::MUST_VALIDATE),
        array('num', 'require', '请填写数量', self::MUST_VALIDATE),
        array('num', 'number', '请填写有效的数量', self::MUST_VALIDATE),
        array('unit', 'require', '请选择单位', self::MUST_VALIDATE),
        array('days', 'require', '请填写有效天数', self::MUST_VALIDATE),
        array('days', 'number', '请填写有效的有效天数', self::MUST_VALIDATE),
        array('days', array(0,90), '有效天数范围为0~90天', self::MUST_VALIDATE, 'between'),
        array('supply_category_id', 'require', '请选择分类', self::MUST_VALIDATE),
        array('summary', 'require', '请输入详情', self::MUST_VALIDATE)
    );

    /* 自动完成 */
    protected $_auto = array (
        array('issue_type', 'getIssueType', Model::MODEL_BOTH, 'callback'),
        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
        array('modify_time', 'time', Model::MODEL_BOTH, 'function')
    );

    /**
     * 查询单条记录
     * @param array $condition 查询条件
     * @param array $field 查询字段
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 添加、编辑
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        /* 添加或更新 */
        if ( $datas['id'] > 0 ) {
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
        }
        return $datas;
    }

    /**
     * 删除数据
     * @param array $condition 删除条件
     */
    public function del($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 获取发布人类型 1->个人会员 2->企业会员
     * @return [type] [description]
     */
    protected function getIssueType() {
        $uid = I('post.company_id', 0, 'intval');
        $gid = M('Member')->where(array('uid'=>$uid))->getField('gid');
        return $gid;
    }
}