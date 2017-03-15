<?php
namespace Member\Model;
use Think\Model;
/**
 * 会员模型
 * @author 122837594@qq.com
 */
class MemberModel extends Model {
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::MUST_VALIDATE),
        array('name', '2,20', '姓名长度为2-20个字符', self::MUST_VALIDATE,"length"),
//        array('nickname', '2,100', '昵称长度为2-100个字符', self::VALUE_VALIDATE,"length"),
//        array('email', 'require', '邮箱不能为空', self::MUST_VALIDATE),
//        array('email', 'email', '请输入有效邮箱', self::MUST_VALIDATE,),
       );
    /*自动完成*/
    protected $_auto = array ( 
        array('status','1',Model::MODEL_INSERT),
        array('regdate','time',Model::MODEL_INSERT,'function'),
        array('addtime','time',Model::MODEL_BOTH,'function'),
        array('birthday','dealBirth',Model::MODEL_BOTH,'callback'),
    );

    /**
     * 取得会员信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getMemberInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }


    /**
     * 添加/编辑部门 
     * @author 122837594@qq.com
     */
    public function update(){
         /* 获取数据对象 */
        $data = I('post.','','htmlspecialchars');
        
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }  
              
         /* 添加或更新 */
        if(empty($data['uid'])){ //新增
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
     * 把生日处理成时间戳
     */
    protected function dealBirth(){
        $birthday=I('post.birthday');
        return strtotime($birthday);
    }
}