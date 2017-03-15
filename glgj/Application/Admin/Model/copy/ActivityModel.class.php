<?php
namespace Admin\Model;
use Think\Model;
class ActivityModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('activity_type', 'require', '参数错误', self::MUST_VALIDATE),
        array('title', 'require', '活动名称不能为空', self::MUST_VALIDATE),
        array('title', '1,10', '活动名称长度应为1-10位字符', self::MUST_VALIDATE , 'length'),
        array('img', 'require', '请上传活动图片', self::MUST_VALIDATE),
        array('start_time', 'require', '请设置活动开始时间', self::MUST_VALIDATE),
        array('end_time', 'require', '请设置活动结束时间', self::MUST_VALIDATE),
        array('end_time', 'checkTime', '活动结束时间不能小于活动开始时间', self::MUST_VALIDATE, 'callback'),
        array('end_time', 'limitTime', '活动时间不能超过100天', self::MUST_VALIDATE, 'callback'),
    );

    /* 自动完成 */
    protected $_auto = array (
        array('start_time', 'strtotime', Model::MODEL_BOTH, 'function'),
        array('end_time', 'strtotime', Model::MODEL_BOTH, 'function')
    );
    
    /**
     * 添加、编辑
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
        /* 添加或更新 */
        $save = $this->where(array('activity_type'=>I('post.activity_type', 0, 'intval')))->save($datas);
        if ( $save === false ) {
            $this->error = '设置失败';
            return false;
        }
        return $datas;
    }

    /**
     * 活动结束时间不能小于活动开始时间
     */
    protected function checkTime() {
        $s = strtotime(I('post.start_time'));
        $e = strtotime(I('post.end_time'));
        if ( $e < $s ) {
            return false;
        }
        return true;
    }

    /**
     * 活动时间不能超过100天
     */
    protected function limitTime() {
        $s = strtotime(I('post.start_time'));
        $e = strtotime(I('post.end_time'));
        /* 活动时间不能超过100天  100 * 86400 */
        if ( $e - $s >= 8640000 ) {
            return false;
        }
        return true;
    }
}