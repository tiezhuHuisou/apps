<?php
namespace Admin\Model;
use Think\Model;
class ArtcategoryModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('cname', 'require', '分类名称不能为空', self::MUST_VALIDATE),
        array('cname', '1,40', '分类名称长度为1-40位字符', self::MUST_VALIDATE ,'length'),
        array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', '0,99', '排序范围0-99', self::MUST_VALIDATE ,'between')
    );
    /*自动完成*/
    protected $_auto = array (
        array('addtime','time',Model::MODEL_INSERT,'function'),
    );
    /**
     * 取得单个分类信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getArtcategoryInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    /**
     * 添加/编辑分类
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data = $_POST;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['cid'])){ //新增
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
     * 删除资讯分类
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delArtcategory($condition){
        return $this->where($condition)->delete();
    }
}