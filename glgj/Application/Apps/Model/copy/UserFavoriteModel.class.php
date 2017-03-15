<?php
namespace Apps\Model;
use Think\Model;
class UserFavoriteModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        // array('title', 'require', '收藏标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '收藏标题为1-50个字符', self::VALUE_VALIDATE, 'length'),
        array('favorite_category', 'require', '收藏分类不能为空', self::MUST_VALIDATE),
        array('favorite_category', '1,5', '收藏类型不存在', self::MUST_VALIDATE, 'between'),
        array('aid', 'require', '参数错误', self::MUST_VALIDATE),
        array('aid', 'checkAid', '要收藏的数据不存在', self::MUST_VALIDATE, 'callback'),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUnique', '已收藏', self::MUST_VALIDATE, 'callback')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('uid', 'getUid', Model::MODEL_INSERT, 'callback'),
        array('addtime', 'time', Model::MODEL_INSERT, 'function')
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
        unset($datas['token']);
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
                $this->error = '收藏失败';
                return false;
            }
            $datas['id'] = $id;
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
     * 获取uid
     */
    protected function getUid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        return $memberInfo['uid'];
    }

    /**
     * 判断用户是否收藏
     */
    protected function checkUnique() {
        $favorite_category = I('post.favorite_category', 0, 'intval');
        $aid = I('post.aid', 0, 'intval');
        $where['favorite_category'] = array('eq', $favorite_category);
        $where['aid'] = array('eq', $aid);
        $where['uid'] = array('eq', $this->getUid());
        $result = $this->where($where)->getField('id');
        if ( !$result ) {
            return true;
        }
        return false;
    }

    /**
     * 检测要收藏的数据是否存在
     */
    protected function checkAid() {
        $favorite_category = I('post.favorite_category', 0, 'intval');
        $aid = I('post.aid', 0, 'intval');
        switch ($favorite_category) {
            case '1':
                /* 资讯收藏 */
                $model = M('Article');
                break;
            case '2':
                /* 产品收藏 */
                $model = M('ProductSale');
                break;
            case '3':
                /* 企业收藏 */
                $model = M('Company');
                break;
            case '4':
                /* 求购收藏 */
                $model = M('ProductBuy');
                break;
            case '5':
                /* 行业圈收藏 */
                $model = M('Circle');
                break;
            default:
                return false;
                break;
        }
        $result = $model->where(array('id'=>$aid))->getField('id');
        if ( !$result ) {
            return false;
        }
        return true;
    }
}