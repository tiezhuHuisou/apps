<?php
namespace Admin\Model;
use Think\Model;
class PageModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('title', '2,40', '标题长度为2-40位字符', self::MUST_VALIDATE ,'length'),
        array('url', 'url', '请输入正确的url地址', self::VALUE_VALIDATE),
        array('linkid', 'require', '标示符不能为空', self::MUST_VALIDATE),
        array('linkid', '2,40', '标示符长度为2-40位字符', self::MUST_VALIDATE,'length'),
        array('content', 'require', '内容不能为空', self::EXISTS_VALIDATE),
        array('seo_keywords', '2,100', 'keywords长度为2-100位字符', self::VALUE_VALIDATE,'length'),
        array('seo_description', '2,100', 'description长度为2-200位字符', self::VALUE_VALIDATE,'length'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('inputtime','time',Model::MODEL_BOTH,'function'),
    );
    /**
     * 取得单个页面信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getPageInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑页面
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
        if(empty($data['id'])){ //新增
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
     * 删除页面
     *
     * @param int $id 记录ID
     * @param array $condition 删除条件
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delPage($condition){
        return $this->where($condition)->delete();
    }
}