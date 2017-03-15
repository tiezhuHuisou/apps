<?php
namespace Apps\Model;
use Think\Model;
class ProductCommentModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('cid', 'require', '参数错误', self::MUST_VALIDATE),
        array('cid', 'checkCid', '被回复的评论不存在', self::MUST_VALIDATE, 'callback'),
        array('cid', 'checkNum', '最多回复2次', self::MUST_VALIDATE, 'callback'),
        array('content', 'require', '请填写评论内容', self::MUST_VALIDATE),
        array('content', '1,200', '评论内容长度应为1-200位字符', self::MUST_VALIDATE , 'length')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('pid', 'getPid', Model::MODEL_INSERT, 'callback'),
        array('type', 2, Model::MODEL_INSERT, 'callback'),
        array('ctime', 'time', Model::MODEL_INSERT, 'function')
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
    protected function checkCid() {
        $cid = I('post.cid', 0, 'intval');
        $result = $this->where(array('id'=>$cid))->getField('id');
        if ( $result ) {
            return true;
        }
        return false;
    }

    /**
     * 获取商品id
     */
    protected function checkCid() {
        $cid = I('post.cid', 0, 'intval');
        $pid = $this->where(array('id'=>$cid))->getField('pid');
        return $pid;
    }

    /**
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }

    /**
     * 最多回复2次
     */
    protected function checkNum() {
        $cid = I('post.cid', 0, 'intval');
        $where['cid']    = array('eq', $cid);
        $where['status'] = array('eq', 1);
        $where['type']   = array('eq', 2);
        $count = $this->where($where)->getField('id');
        if ( $count > 1 ) {
            return false;
        }
        return true;
    }
}