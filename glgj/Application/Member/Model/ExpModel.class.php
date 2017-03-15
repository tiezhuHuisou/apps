<?php
namespace Member\Model;
use Think\Model;
/**
 * 部门设置模型
 * @author 122837594@qq.com
 */
class ExpModel extends Model {
    /*自动验证*/
    protected $_validate = array(
        array('delivery_id', 'require', '请选择快递ID', self::MUST_VALIDATE),
        array('delivery_id', 'judgeNum', '请选择正确的快递', self::MUST_VALIDATE,'callback'),
        array('first_price', 'require', '首件运费不能为空', self::MUST_VALIDATE),
       );
    /*自动完成*/
    protected $_auto = array ( 
        array('status','1',Model::MODEL_INSERT),
    );

    /**
     * 添加/编辑部门 
     * @param $company_id 公司ID
     * @author 122837594@qq.com
     */
    public function update($company_id){
         /* 获取数据对象 */
        $data = I('post.','','htmlspecialchars');
        
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }  
        $data['company_id']=array('eq',$company_id);            
         /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
        }else{
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }
        
        return $data;
    }

    /**
     * 查询所有数据
     * @author 122837594@qq.com
     */
    protected function judgeNum(){
        $delivery_id=I('post.delivery_id',0,'intval');
        $map['id']=array('eq',$delivery_id);
        $res=M('delivery')->where($map)->find();
        if(!$res){
            return false;
        }
        
    }
}