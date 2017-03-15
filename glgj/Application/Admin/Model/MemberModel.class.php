<?php
namespace Admin\Model;
use Think\Model;
class MemberModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('name', 'require', '姓名不能为空', self::MUST_VALIDATE),
        array('name', '2,50', '姓名为2-50位字符', self::MUST_VALIDATE,'length'),
        // array('nickname', 'require', '昵称不能为空', self::MUST_VALIDATE),
        // array('nickname', '2,40', '昵称长度为2-30位字符', self::MUST_VALIDATE ,'length'),
        // array('email', 'require', '邮箱不能为空', self::MUST_VALIDATE),
        // array('email', 'email', '请输入正确的邮箱格式', self::MUST_VALIDATE),
        array('mphone', 'require', '手机不能为空', self::MUST_VALIDATE),
        array('mphone', 'mobile', '手机号码格式不正确', self::MUST_VALIDATE),
        array('password', 'require', '密码不能为空', self::MUST_VALIDATE),
        array('password', '6,40', '密码长度为6-40位字符', self::MUST_VALIDATE,'length'),
        
    );
    /*自动完成*/
    protected $_auto = array (
        array('lin_salt','get_lin_salt',Model::MODEL_INSERT,'callback'),
        array('password','get_password',Model::MODEL_BOTH,'callback'),
        array('regdate','time',Model::MODEL_INSERT,'function'),
        array('addtime','time',Model::MODEL_INSERT,'function'),
    );
    /**
     * 取得单个会员信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getMemberInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加/编辑会员
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
        if(empty($_POST['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
    
        } else { //更新
            unset($data['lin_salt']);
			$status = $this->where(array('mphone'=>$data['mphone']))->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }
        return $data;
    }
    /**
     * 删除会员
     *
     * @param int $id 记录ID
     * @param array $condition 删除条件
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delMember($condition){
        $tokens = $this->where($condition)->getField('token_id', true);
        $where['id'] = array('in', $tokens);
        M('Token')->where($where)->delete();
        return $this->where($condition)->delete();
    }
    /**
     * 创建管理员时生成salt值
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_lin_salt(){
        $pw_length = 6;
        $randpwd ='';
        for ($i = 0; $i < $pw_length; $i++)
        {
            $randpwd .= chr(mt_rand(33, 126));
        }
        $_SESSION["tmp"] = $randpwd;
        return $randpwd;
    }
    /**
     * 加密密码
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_password(){
        $password = $_POST['password'];
        $mphone = $_POST['mphone'];
        $user = M('Member')->where('mphone='.$mphone)->find();
        if($user != '' || $user != false){
            $salt = $user['lin_salt'];
        }else{
            $salt = $_SESSION["tmp"];
        }
        if($password != $user['password']){
           $password = md5(md5($password).$salt);
        }
        unset($_SESSION["tmp"]);
        return $password;
    }
}