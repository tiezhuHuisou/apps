<?php
namespace Admin\Model;
use Think\Model;
class MemberGroupModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('gname', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('gname', '2,40', '标题长度为2-40位字符', self::MUST_VALIDATE ,'length'),
        array('desc', 'require', 'url不能为空', self::EXISTS_VALIDATE),
        array('desc', '2,200', '描述长度为2-200位字符', self::EXISTS_VALIDATE,'length'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('addtime','time',Model::MODEL_INSERT,'function'),
    );
    /**
     * 取得单个分组信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getGroupInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑分组
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data=$_POST;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['gid'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
    
        } else { //更新
    
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }
        return $data;
    }
    /**
     * 删除分组
     *
     * @param int $id 记录ID
     * @param array $condition 删除条件
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delGroup($condition){
        return $this->where($condition)->delete();
    }
}