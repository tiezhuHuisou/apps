<?php
namespace App\Model;
use Think\Model;
class GoodsModel extends Model {
    /* 自动验证 */
    protected $_validate = array(
        array('uid', 'require', '请先登录', self::MUST_VALIDATE),

        array('mphone', 'checkUid', '帐号不存在', self::MUST_VALIDATE, 'callback'),
        array('provincen', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('cityn', 'require', '请选择始发地', self::MUST_VALIDATE),
        array('provincen2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('cityn2', 'require', '请选择目的地', self::MUST_VALIDATE),
        array('name', 'require', '请现写货物名称', self::MUST_VALIDATE),
        array('transport_id', 'require', '请选择运输方式', self::MUST_VALIDATE),
        array('category_id', 'require', '货物类型', self::MUST_VALIDATE),
        array('deliver_time', 'require', '请选择发货日期', self::MUST_VALIDATE),

    );
    /*自动完成*/
    protected $_auto = array(
        array('create_time', 'time', Model::MODEL_INSERT, 'function'),
        array('update_time', 'time', Model::MODEL_BOTH, 'function'),
        array('deliver_time', 'getDeliverTime', Model::MODEL_BOTH, 'callback'),
        array('end_time', 'getEndTime', Model::MODEL_BOTH, 'callback'),
        array('uid', 'getUid', Model::MODEL_BOTH, 'callback'),
    );

    /**
     * 查询单条记录
     * @param array $condition 查询条件
     * @param array $field 查询字段
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 添加、编辑
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }
        $imgAll   = $_POST['allpic'];
        $picModel = M('DepotPicture');
        if ( is_array($imgAll) && count($imgAll) ) {
            $count = count($imgAll);
            $data['img'] = $imgAll[0];
        } else {
            $this->error = '请上传仓储图片';
            return false;
        }

        /* 添加或更新 */
        if ( $datas['id'] > 0 ) {
            /* 修改 */
            $picModel->where(array('product_id'=>$data['id']))->delete();
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 添加 */
            $datas['id'] = $this->add($datas);

            if ( !$datas['id'] ) {
                $this->error = '添加失败';
                return false;
            }
        }
        /* 轮播图添加 */
        for($i=0;$i<$count;$i++){
            $picdata[$i]['product_id'] = $datas['id'];
            $picdata[$i]['pic_url'] = $imgAll[$i];
            $picdata[$i]['addtime'] = time();
        }
        $picModel->addAll($picdata);
        return $datas;
    }

    /**
     * 删除数据
     * @param array $condition 删除条件
     */
    public function del($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 获取发布人类型 1->个人会员 2->企业会员
     * @return [type] [description]
     */
    protected function getIssueType() {
        $uid = I('post.company_id', 0, 'intval');
        $gid = M('Member')->where(array('uid'=>$uid))->getField('gid');
        return $gid;
    }

    /**
     * 检查用户uid
     */
    protected function checkUid() {
        $mphone = trim($_POST['mphone']);

        $uid = M('Member')->where(array('mphone' => $mphone))->getField('uid');
        if ($uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将时间改为unix时间戳
     */
    protected function getDepartureTime(){
        return strtotime($_POST['departure_time']);
    }
    /**
     * 将时间改为unix时间戳
     */
    protected function getEndTime(){
        return strtotime($_POST['end_date']);
    }

    /**
     * 通过手机号寻找账户id
     */
    protected function getUid() {
        $mphone = trim($_POST['mphone']);
        $uid = M('Member')->where(array('mphone' => $mphone))->getField('uid');
        if ($uid) {
            return $uid;
        } else {
            return false;
        }
    }
}