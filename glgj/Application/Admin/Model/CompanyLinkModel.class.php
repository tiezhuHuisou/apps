<?php
namespace Admin\Model;
use Think\Model;
class CompanyLinkModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('company_id', 'require', '操作失败', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
        array('modify_time', 'time', Model::MODEL_BOTH, 'function'),
    );
    /**
     * 取得单个公司信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getCompanyLinkInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑公司信息
     * @author 83961014@qq.com
     */
    public function update($company_id) {
        /* 获取数据对象 */
        $data = $_POST['companylink'];
        $data['company_id'] = $company_id;
        $area = explode(',', $data['p_area']);
        unset($data['p_area']);
        $data['lng'] = $area[0];
        $data['lat'] = $area[1];
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '操作失败！';
                return false;
            }
        } else { //更新
            $status = $this->save($data);
            if(false === $status){
                $this->error = '操作失败！';
                return false;
            }
        }
        return $data;
    }
    /**
     * 删除公司信息
     *
     * @param int $id 记录ID
     * @param array $condition 删除条件
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delCompanyLink($condition){
        return $this->where($condition)->delete();
    }
}