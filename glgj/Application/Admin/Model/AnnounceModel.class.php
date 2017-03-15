<?php
namespace Admin\Model;
use Think\Model;
class AnnounceModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('title', '2,40', '标题长度为2-60位字符', self::MUST_VALIDATE ,'length'),
        array('content', 'require', '内容不能为空', self::MUST_VALIDATE),
        array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', '1,99', '排序范围在1~99之间', self::MUST_VALIDATE,'between'),
        array('seo_keywords', '2,100', 'keywords长度为2-100位字符', self::VALUE_VALIDATE,'length'),
        array('seo_description', '2,100', 'description长度为2-200位字符', self::VALUE_VALIDATE,'length'),
        
    );
   /*自动完成*/
    protected $_auto = array (
        array('flags','get_flags',Model::MODEL_BOTH,'callback'),
        array('addtime','time',Model::MODEL_INSERT,'function'),
        array('uptime','time',Model::MODEL_BOTH,'function'),
    );
    /**
     * 取得单个分类信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getOneInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    /**
     * 添加/编辑公告
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
     * 删除公告
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delArtcategory($condition){
        return $this->where($condition)->delete();
    }
    /**
     * 封装flags字段
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_flags(){
        $flags 			= I('post.flags','');
        if(!empty($flags)){
            $flags 		    = implode(',', $flags);
            return $flags;
        }
    }
}