<?php
namespace Admin\Model;
use Think\Model;
class ProductSaleCategoryModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('name', 'require', '分类名称不能为空', self::MUST_VALIDATE),
        array('name', '2,48', '分类名称长度为2-48位字符', self::MUST_VALIDATE ,'length'),
        array('seo_keywords', '2,200', '关键字长度为2-200位字符', self::VALUE_VALIDATE,'length'),
        array('seo_description', '2,500', '分类名称长度为2-500位字符', self::VALUE_VALIDATE,'length'),
        array('sort', 'require', '排序不能为空', self::MUST_VALIDATE),
        array('sort', '1,99', '排序范围在1~99之间', self::MUST_VALIDATE,'between'),
    );
    /*自动完成*/
    protected $_auto = array (
    );
    /**
     * 取得单个分类信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getProductSaleCategoryInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    /**
     * 添加/编辑分类
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
     * 删除分类
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delProductSaleCategory($condition){
        return $this->where($condition)->delete();
    }
}