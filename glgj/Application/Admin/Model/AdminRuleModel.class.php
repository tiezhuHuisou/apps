<?php
namespace Admin\Model;
use Think\Model;
class AdminRuleModel extends Model{
	/*自动验证*/
    protected $_validate = array(
        array('title', 'require', '规则名不能为空', self::MUST_VALIDATE),
        array('title', '2,100', '规则名长度为2-50位字符', self::MUST_VALIDATE ,'length'),
        array('rule_name', 'require', '规则不能为空', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
        array('status',1,Model::MODEL_INSERT),
        array('module',MODULE_NAME,Model::MODEL_INSERT,'string'),
        array('rule_name','dealrule',Model::MODEL_INSERT,'callback'),
    );

    /**
     * 添加/编辑管理员
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

    protected function dealrule(){
        $rule_name=MODULE_NAME.'/'.I('post.rule_name');
    	return strtolower($rule_name);
    }
}