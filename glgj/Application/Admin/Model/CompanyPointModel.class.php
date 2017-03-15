<?php
namespace Admin\Model;
use Think\Model;
class CompanyPointModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('c_id', 'require', '参数错误', self::MUST_VALIDATE),
        array('p_area', 'require', '请添加标注参数', self::MUST_VALIDATE),
        array('address', 'require', '请填写地址', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
//        array('flags','get_flags',Model::MODEL_BOTH,'callback'),
//        array('issue_time','time',Model::MODEL_INSERT,'function'),
//        array('p_addtime','time',Model::MODEL_INSERT,'function'),
    );
    /**
     * 取得单个商品信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getOneInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    /**
     * 添加/编辑商品
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
        if(empty($data['p_id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
            $data['id'] = $id;
    
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
     * 删除网点
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delDate($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 封装flags字段
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_flags(){
        $flags          = I('post.flags','');
        if(!empty($flags)){
            $flags          = implode(',', $flags);
            return $flags;
        }else{
            return $flags="";
        }
    }

}