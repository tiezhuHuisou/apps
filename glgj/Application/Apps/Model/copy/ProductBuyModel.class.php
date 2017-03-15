<?php
namespace Apps\Model;
use Think\Model;
class ProductBuyModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUid', '求购发布者与当前用户不符', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('token', 'checkGid', '只有企业会员才能发布求购', self::MUST_VALIDATE, 'callback', Model::MODEL_INSERT),
        array('carousel', 'require', '请上传求购轮播图', self::MUST_VALIDATE),
        array('title', 'require', '求购标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '求购标题长度应为1-50位字符', self::MUST_VALIDATE , 'length'),
        array('short_title', 'require', '求购副标题不能为空', self::MUST_VALIDATE),
        array('short_title', '1,100', '求购副标题长度应为1-100位字符', self::MUST_VALIDATE , 'length'),
        // array('price', 'require', '求购价格不能为空', self::MUST_VALIDATE),
        array('price', 'checkPrice', '求购价格格式不正确', self::VALUE_VALIDATE , 'callback'),
        // array('days', 'require', '求购有效天数不能为空', self::MUST_VALIDATE),
        array('days', '0,90', '求购有效天数范围为0-90天', self::VALUE_VALIDATE, 'between'),
        array('num', 'number', '求购数量只能为数字', self::VALUE_VALIDATE),
        array('buy_category_id', 'require', '请选择分类', self::MUST_VALIDATE),
        array('buy_category_id', 'checkCategory', '所选分类不存在', self::MUST_VALIDATE, 'callback')
    );

    /* 自动完成 */
    protected $_auto = array (
        array('company_id', 'getCompanyId', Model::MODEL_INSERT, 'callback'),
        array('issue_time', 'time', Model::MODEL_INSERT, 'function'),
        array('modify_time', 'time', Model::MODEL_BOTH, 'function')
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
        // dump($_POST);exit();
        $datas  = $this->create($datas);
        if ( !$datas ) {
            return false;
        }

        /* 取求购轮播图第一张作为求购主图 */
        $carousel = $_POST['carousel'];
        $carousel = explode(',', $carousel);
        $datas['img'] = $carousel[0];
        
        /* 发布或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
            M('NeedPicture')->where(array('need_id'=>$datas['id']))->delete();
        } else {
            /* 发布 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '发布失败';
                return false;
            }
            $datas['id'] = $id;
        }

        /* 保存求购轮播图 */
        foreach ($carousel as $k => $v) {
            $imgDatas[$k]['need_id'] = $datas['id'];
            $imgDatas[$k]['pic_url'] = $v;
            $imgDatas[$k]['addtime'] = time();
        }
        M('NeedPicture')->addAll($imgDatas);

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
        $uid         = $this->getUid();
        $map['l.id'] = $condition['id'];
        $user_id     = $this
                     ->alias('l')
                     ->join(C('DB_PREFIX').'company r ON l.company_id = r.id', 'LEFT')
                     ->where($map)
                     ->getField('r.user_id');
        if ( $uid != $user_id ) {
            return false;
        }
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
     * 获取用户企业id
     */
    protected function getCompanyId() {
        $uid = $this->getUid();
        return M('Company')->where(array('user_id'=>$uid))->getField('id');
    }

    /**
     * 编辑时检测行业圈发布者与当前用户是否一致
     */
    protected function checkUid() {
        $id   = I('post.id', 0, 'intval');
        $uid  = $this->getUid();
        $company_id = $this->where(array('id'=>$id))->getField('company_id');
        $cuid = M('Company')->where(array('id'=>$company_id))->getField('user_id');
        if ( $uid == $cuid ) {
            return true;
        }
        return false;
    }

    /**
     * 检测会员分组
     */
    protected function checkGid() {
        $token = I('post.token');
        $memberInfo = D('Token')->getMemberInfo($token);
        if ( $memberInfo['gid'] == 2 ) {
            return true;
        }
        return false;
    }

    /**
     * 检测求购价格格式
     */
    protected function checkPrice() {
        $price = I('post.price');
        $price = explode(',', $price);
        if ( count($price) > 2 ) {
            return false;
        }
        foreach ($price as $value) {
            if ( !preg_match('/^\d+(\.\d{1,2})?$/', $value) ) {
                return false;
            }
        }
        return true;
    }

    /**
     * 检测分类是否存在
     */
    protected function checkCategory() {
        $categoryId = I('post.buy_category_id');
        $result = M('ProductSaleCategory')->where(array('status'=>1, 'parent_id'=>0, 'id'=>$categoryId))->getField('id');
        if ( $result ) {
            return true;
        }
        return false;
    }
}