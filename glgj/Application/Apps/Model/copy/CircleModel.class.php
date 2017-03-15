<?php
namespace Apps\Model;
use Think\Model;
class CircleModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUid', '行业圈发布者与当前用户不符', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('content', 'checkContent', '内容和图片不能都为空', self::MUST_VALIDATE, 'callback'),
        array('content', '1,1000', '内容长度应为1-1000位字符', self::VALUE_VALIDATE , 'length'),
        array('cid', 'require', '请选择分类', self::MUST_VALIDATE),
        array('cid', 'checkCid', '您选择的分类不存在', self::MUST_VALIDATE, 'callback'),
        array('share_model', 'checkShareModel', '分享类型不存在', self::VALUE_VALIDATE , 'callback'),
        array('data_id', 'checkDataId', '分享数据id异常', self::MUST_VALIDATE, 'callback')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('gid', 'getGid', Model::MODEL_INSERT, 'callback'),
        array('ctime', 'time', Model::MODEL_INSERT, 'function')
    );

    /**
     * 取得单条记录
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 406764368@qq.com
     * @return 返回一条满足条件的记录
     */
    public function getOneInfo($condition = array(), $field='*') {
        return $this->field($field)->where($condition)->find();
    }
    
    /**
     * 发布、编辑
     * @author 406764368@qq.com
     * @return 提交的数据
     */
    public function update(){
        /* 获取数据对象 */
        $datas  = $_POST;
        $datas  = $this->create($datas);
        if ( empty($datas) ) {
            return false;
        }

        /* 上传图片 */
        if ( !empty($_POST['img']) ) {
            $img    = explode(',', $_POST['img']);
            $suffix = explode(',', $_POST['suffix']);
            if ( count($img) > 9 ) {
                $this->error = '最多只允许上传9张图片';
                return false;
            }
            foreach ($img as $key => $value) {
                $result = R('Apps/General/uploadImgs', array($value, $suffix[$key]));
                if ( empty($result['img']) ) {
                    $this->error = $result['info'];
                    return false;
                } else {
                    $imgDatas[]['img'] = $result['img'];
                    /* 取第一张设为封面图 */
                    $key == 0 && $datas['img'] = $result['img'];
                }
            }
        }
        
        /* 发布或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
        } else {
            /* 发布 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '发布失败';
                return false;
            }
            $datas['id'] = $id;
        }

        if ( !empty($_POST['img']) ) {
            /* 保存图片 */
            foreach ($imgDatas as $k => $v) {
                $imgDatas[$k]['pid'] = $datas['id'];
            }
            M('CirclePicture')->addAll($imgDatas);
        }

        return $datas;
    }

    /**
     * 删除数据
     * @param  array $condition 删除条件
     * @author 406764368@qq.com
     * @return bool 布尔类型的返回结果
     */
    public function del($condition) {
        $checkToken = $this->checkToken();
        if ( !$checkToken ) {
            return false;
        }
        $uid = $this->getUid();
        $commentUid = $this->where($condition)->getField('uid');
        if ( $uid != $commentUid ) {
            return false;
        }
        $condition['uid'] = array('eq', $uid);
        return $this->where($condition)->delete();
    }

    /**
     * 检测token信息，判断用户登陆信息是否过期
     */
    protected function checkToken() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        if ( !$memberInfo ) {
            return false;
        }
        return ture;
    }

    /**
     * 检测标题和图片不能同时为空
     */
    protected function checkContent() {
        $content = I('post.content');
        if ( empty($content) && empty($_POST['img']) ) {
            return false;
        }
        return true;
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
     * 获取gid
     */
    protected function getGid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['gid'];
    }

    /**
     * 编辑时检测行业圈发布者与当前用户是否一致
     */
    protected function checkUid() {
        $id   = I('post.id', 0, 'intval');
        $uid  = $this->getUid();
        $cuid = $this->where(array('id'=>$id))->getField('uid');
        if ( $uid == $cuid ) {
            return true;
        }
        return false;
    }

    /**
     * 检测分类类型是否合法
     */
    protected function checkShareModel() {
        $share_model = I('post.share_model');
        $access_list = array('news_detail', 'product_detail', 'need_detail', 'company_home', 'company_album');
        if ( empty($share_model) ) {
            return true;
        } elseif ( in_array($share_model, $access_list) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检测数据id是否正常
     */
    protected function checkDataId() {
        $share_model = I('post.share_model');
        /* 不是分享不需要处理 */
        if ( empty($share_model) ) {
            return true;
        }
        $data_id = I('post.data_id', 0, 'intval');
        if ( !$data_id ) {
            return false;
        }
        switch ($share_model) {
            case 'news_detail':
                $model               = M('Article');
                $where['is_display'] = array('eq', 1);
                $where['id']         = array('eq', $data_id);
                break;
            case 'product_detail':
                $model           = M('ProductSale');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                break;
            case 'need_detail':
                $model           = M('ProductBuy');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                break;
            case 'company_home':
                $model           = M('Company');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                break;
            case 'company_album':
                $model           = M('Company');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                break;
            default:
                break;
        }
        $result = $model->where($where)->find();
        if ( !$result ) {
            return false;
        }
        return true;
    }

    /**
     * 检测分类是否存在
     */
    protected function checkCid() {
        $cid = I('post.cid', 0, 'intval');
        $res = M('CircleCategory')->where(array('id'=>$cid))->find();
        if ( !$res ) {
            return false;
        }
        return true;
    }
}