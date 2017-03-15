<?php
namespace Apps\Model;
use Think\Model;
class ProductCommentModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE),
        array('id', 'checkPid', '被回复的评论不存在', self::MUST_VALIDATE, 'callback'),
        array('content', 'require', '请填写评论内容', self::MUST_VALIDATE),
        array('content', '1,300', '评论内容长度应为1-300位字符', self::MUST_VALIDATE , 'length')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_BOTH, 'callback'),
        array('type', 2, Model::MODEL_BOTH),
        array('ctime', 'time', Model::MODEL_BOTH, 'function')
    );
    
    /**
     * 新增、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }

        /* 获取其他信息 */
        $info = $this->field('order_id,pid')->where(array('id'=>$datas['id']))->find();
        $datas['cid']      = $datas['id'];
        $datas['order_id'] = $info['order_id'];
        $datas['pid']      = $info['pid'];
        unset($datas['id']);


        /* 新增或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 新增 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '评论失败';
                return false;
            }
            $datas['id'] = $id;
        }
        return $datas;
    }

    /**
     * 检测评论id，判断评论是否存在
     */
    protected function checkPid() {
        $id = I('post.id', 0, 'intval');
        $result = $this->where(array('id'=>$id))->getField('id');
        if ( $result ) {
            return true;
        }
        return false;
    }

    /**
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }
}