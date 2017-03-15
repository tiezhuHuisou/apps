<?php
namespace Admin\Model;
use Think\Model;
class UserMessageModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('to_user', 'checkUser', '用户名不能为空', self::MUST_VALIDATE , 'callback'),
        array('to_user', 'mobile', '请填写正确的用户名', self::VALUE_VALIDATE ),
        array('to_user', 'existUser', '该用户不存在', self::VALUE_VALIDATE , 'callback'),
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '标题范围1-50个字符', self::MUST_VALIDATE, 'length'),
        array('message', 'require', '内容不能为空', self::MUST_VALIDATE),
        array('message', '1,1000', '内容范围1-1000个字符', self::MUST_VALIDATE, 'length'),
    );
    /*自动完成*/
    protected $_auto = array (
        array('from_user', -1, Model::MODEL_INSERT),
        array('to_user', 'getUserId', Model::MODEL_BOTH, 'callback'),
        array('addtime', 'time', Model::MODEL_INSERT, 'function'),
        array('mobile', 'getMobile', Model::MODEL_BOTH, 'callback')
    );
    /**
     * 取得单个管理员信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getUserMessageInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    
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
        if(empty($data['user_id'])){ //新增
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
     * 删除管理员
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delUserMessage($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 验证用户名
     */
    protected function checkUser() {
        $type   = I('post.to_type', 0, 'intval');
        $mphone = I('post.to_user');
        if ( $type == 2 && empty($mphone) ) {
            return false;
        }
        return true;
    }

    /**
     * 检测所填用户是否存在
     */
    protected function existUser() {
        $type   = I('post.to_type', 0, 'intval');
        $mphone = I('post.to_user');
        if ( $type == 2 ) {
            $uid = M('Member')->where(array('mphone'=>$mphone))->getField('uid');
            if ( !$uid ) {
                return false;
            }
        }
        return true;
    }

    /**
     * 获取收信人id
     * @return string
     * @author 83961014@qq.com
     */
    protected function getUserId(){
        $type   = I('post.to_type', 0, 'intval');
        $mphone = I('post.to_user');
        return $type == 1 ? 0 : M('Member')->where(array('mphone'=>$mphone))->getField('uid');
    }

    /**
     * 获取用户名
     * 发送给全体会员显示全体会员，发送给个人显示帐号(mphone)
     */
    protected function getMobile() {
        $type   = I('post.to_type', 0, 'intval');
        $mphone = I('post.to_user');
        return $type == 1 ? '全体会员' : $mphone;
    }
}