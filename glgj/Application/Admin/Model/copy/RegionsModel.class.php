<?php
namespace Admin\Model;
use Think\Model;
class RegionsModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', self::MODEL_UPDATE),
        array('parent', 'require', '请选择上级区域', self::MUST_VALIDATE),
        array('name', 'require', '名称不能为空', self::MUST_VALIDATE),
        array('name', '1,50', '名称长度应为1-50位字符', self::MUST_VALIDATE , 'length'),
    );

    /* 自动完成 */
    protected $_auto = array (
        array('grade', 'getGrade', Model::MODEL_BOTH, 'callback')
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
     * 获取等级
     */
    protected function getGrade() {
        $parent = I('post.parent', 0, 'intval');
        $grade  = $this->where(array('id'=>$parent))->getField('grade');
        $grade++;
        return $grade;
    }
}