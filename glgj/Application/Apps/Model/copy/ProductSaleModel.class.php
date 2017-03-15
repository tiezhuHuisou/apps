<?php
namespace Apps\Model;
use Think\Model;
class ProductSaleModel extends Model{
    /* 自动验证 */
    protected $_validate = array(
        array('id', 'require', '参数错误', self::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('token', 'require', '请先登陆', self::MUST_VALIDATE),
        array('token', 'checkToken', '登陆信息已过期', self::MUST_VALIDATE, 'callback'),
        array('token', 'checkUid', '产品发布者与当前用户不符', self::MUST_VALIDATE, 'callback', Model::MODEL_UPDATE),
        array('token', 'checkGid', '只有企业会员才能发布产品', self::MUST_VALIDATE, 'callback', Model::MODEL_INSERT),
        array('title', 'require', '产品标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '产品标题长度应为1-50位字符', self::MUST_VALIDATE , 'length'),
        array('short_title', 'require', '产品副标题不能为空', self::MUST_VALIDATE),
        array('short_title', '1,100', '产品副标题长度应为1-100位字符', self::MUST_VALIDATE , 'length'),
        array('price', 'require', '销售价格不能为空', self::MUST_VALIDATE),
        array('price', 'money', '销售价格格式不正确', self::MUST_VALIDATE),
        // array('summary', '1,1000', '产品详情长度应为1-1000位字符', self::VALUE_VALIDATE , 'length'),
        array('sale_category_id', 'require', '请选择分类', self::MUST_VALIDATE),
        array('sale_category_id', 'checkCategory', '最多只能选择5个分类', self::MUST_VALIDATE, 'callback'),
        array('sale_category_id', 'existCategory', '所选分类不存在', self::MUST_VALIDATE, 'callback'),
        array('num', 'require', '库存不能为空', self::MUST_VALIDATE),
        array('num', 'number', '库存只能为数字', self::MUST_VALIDATE),
        array('activity_price', 'money','活动价格格式不正确', self::VALUE_VALIDATE),
        array('oprice', 'require', '市场价格不能为空', self::VALUE_VALIDATE),
        array('oprice', 'money', '市场价格格式不正确', self::VALUE_VALIDATE),
        array('buymin', 'number', '最小购买数量应为数字', self::VALUE_VALIDATE),
        array('buymin', '0', '最小购买数量不能小于1', self::VALUE_VALIDATE, 'gt'),
    );

    /* 规格自动验证 */
    protected $spec_validate = array(
        array('title1', 'require', '规格一标题不能为空', self::MUST_VALIDATE),
        array('title1', '1,50', '规格一标题长度应为1-50位字符', self::MUST_VALIDATE, 'length'),
        array('title2', '1,50', '规格二标题长度应为1-50位字符', self::VALUE_VALIDATE, 'length'),
        array('spec1', 'require', '规格一内容不能为空', self::MUST_VALIDATE),
        array('spec1', '1,50', '规格一内容长度应为1-50位字符', self::MUST_VALIDATE, 'length'),
        array('spec2', '1,50', '规格二内容长度应为1-50位字符', self::VALUE_VALIDATE, 'length'),
        array('price', 'require', '规格销售价格不能为空', self::MUST_VALIDATE),
        array('price', 'money', '规格销售价格格式不正确', self::MUST_VALIDATE),
        array('stock', 'require', '规格库存不能为空', self::MUST_VALIDATE),
        array('stock', 'number', '规格库存只能为数字', self::MUST_VALIDATE),
        array('activity_price', 'money','规格活动价格格式不正确', self::VALUE_VALIDATE),
        array('oprice', 'money', '规格市场价格格式不正确', self::VALUE_VALIDATE),
        array('buymin', 'require', '规格最小购买数量不能为空', self::MUST_VALIDATE),
        array('buymin', 'number', '规格最小购买数量应为数字', self::MUST_VALIDATE),
        array('buymin', '0', '规格最小购买数量不能小于1', self::MUST_VALIDATE, 'gt'),
        array('sort', 'require', '规格排序不能为空', self::MUST_VALIDATE),
        array('sort', 'number', '规格排序应为数字', self::MUST_VALIDATE),
        array('sort', '0,99', '规格排序范围0-99', self::MUST_VALIDATE, 'between'),
        array('weight', 'money', '规格重量格式不正确', self::VALUE_VALIDATE),
    );

    /* 自动完成 */
    protected $_auto = array (
        array('company_id', 'getCompanyId', Model::MODEL_INSERT, 'callback'),
        array('group_id', 'getGroupId', Model::MODEL_BOTH, 'callback'),
        array('oprice', 'getOprice', Model::MODEL_BOTH, 'callback'),
        array('activity_price', 'getActivityPrice', Model::MODEL_BOTH, 'callback'),
        array('activity_type', 'getActivityType', Model::MODEL_BOTH, 'callback'),
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
    public function update( $postDatas ){
        /* 获取数据对象 */
        $datas  = $_POST;

        /* 轮播图验证 */
        $carousel = $_POST['carousel'];
        if ( !is_array($carousel) || !$carousel ) {
            $this->error = '请上传产品轮播图';
            return false;
        }
        $datas  = $this->create($datas);
        if ( !$datas ) {
            return false;
        }


        $specModel = M('ProductSpec');
        $picModel  = M('ProdcutPicture');

        /* 规格处理 */
        if ( $_POST['is_spec'] == '1' ) {
            $specCount = $_POST['spec_id'] ? count($_POST['spec_id']) : 0;
            if ( $specCount ) {
                $this->_validate = $this->spec_validate;
                for ($i=0; $i < $specCount; $i++) { 
                    $specDatas[$i]['title1']         = $_POST['title1'];
                    $specDatas[$i]['title2']         = $_POST['title2'];
                    $specDatas[$i]['spec1']          = $_POST['spec1'][$i];
                    $specDatas[$i]['spec2']          = $_POST['spec2'][$i];
                    $specDatas[$i]['price']          = $_POST['spec_price'][$i];
                    $specDatas[$i]['stock']          = $_POST['spec_stock'][$i];
                    $specDatas[$i]['buymin']         = $_POST['spec_buymin'][$i];
                    $specDatas[$i]['sort']           = $_POST['spec_sort'][$i];
                    $specDatas[$i]['img']            = $_POST['spec_img'][$i] ? $_POST['spec_img'][$i] : C('HTTP_APPS_IMG') . 'product_default.png';
                    $specDatas[$i]['ctime']          = time();
                    /* 市场价格默认为销售价格的1.2倍 */
                    $specDatas[$i]['oprice']         = $_POST['spec_oprice'][$i] ? $_POST['spec_oprice'][$i] : $specDatas[$i]['price'] * 1.2;
                    /* 活动价格默认为销售价格的0.9倍 */
                    $specDatas[$i]['activity_price'] = $_POST['spec_activity_price'][$i] ? $_POST['spec_activity_price'][$i] : $specDatas[$i]['price'] * 0.9;
                    $specDatas[$i]['weight']         = $_POST['spec_weight'][$i];
                    /* 填写了规格标题二，必须填写规格内容二 */
                    if ( !empty($specDatas[$i]['title2']) && empty($specDatas[$i]['spec2']) ) {
                        $this->error = '规格二内容不能为空';
                        return false;
                    }
                    /* 自动验证 */
                    $specDatasResult = $this->create($specDatas[$i]);
                    if ( !$specDatasResult ) {
                        return false;
                    }
                }
            }
        }

        /* 取产品轮播图第一张作为产品主图 */
        $datas['img'] = $carousel[0];
        
        /* 发布或更新 */
        if ( $datas['id'] ) {
            /* 修改 */
            $save = $this->save($datas);
            if ( $save === false ) {
                $this->error = '修改失败';
                return false;
            }
            $picModel->where(array('product_id'=>$datas['id']))->delete();
            $specModel->where(array('product_id'=>$datas['id']))->delete();
        } else {
            /* 发布 */
            $id = $this->add($datas);
            if ( !$id ) {
                $this->error = '发布失败';
                return false;
            }
            $datas['id'] = $id;
        }

        /* 保存产品轮播图 */
        foreach ($carousel as $k => $v) {
            $imgDatas[$k]['product_id'] = $datas['id'];
            $imgDatas[$k]['pic_url'] = $v;
            $imgDatas[$k]['addtime'] = time();
        }
        $picModel->addAll($imgDatas);

        /* 保存规格数据 */
        if ( $specDatas ) {
            foreach ($specDatas as $sk => $sv) {
                $specDatas[$sk]['product_id'] = $datas['id'];
            }
            $specModel->addAll($specDatas);
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
        $token = $_POST['token'];
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
        $token = $_POST['token'];
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
     * 编辑时检测产品发布者与当前用户是否一致
     */
    protected function checkUid() {
        $id   = $_POST['id'];
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
        $token = $_POST['token'];
        $memberInfo = D('Token')->getMemberInfo($token);
        if ( $memberInfo['gid'] == 2 ) {
            return true;
        }
        return false;
    }

    /**
     * 检测分类是否存在
     */
    protected function existCategory() {
        $category = $_POST['sale_category_id'];
        $category = explode(',', $category);
        $where['status'] = array('eq', 1);
        $where['id']     = array('in', $category);
        $categoryList = M('ProductSaleCategory')->where($where)->getField('id', true);
        if ( count($category) == count($categoryList) ) {
            return true;
        }
        return false;
    }

    /**
     * 检测分类数量
     */
    protected function checkCategory() {
        $category = $_POST['sale_category_id'];
        $category = explode(',', $category);
        if ( count($category) > 5 ) {
            return false;
        }
        return true;
    }

    /**
     * 获取分组id
     */
    protected function getGroupId() {
        $group_id = $_POST['group_id'];
        if ( $group_id ) {
            foreach ($group_id as $key => $value) {
                $value = explode(',', $value);
                if ( $value[1] == 1 ) {
                    return $value[0];
                }
            }
        }
        return 0;
    }

    /**
     * 获取活动类型id
     */
    protected function getActivityType() {
        $activity_type = $_POST['activity_type'];
        if ( $activity_type ) {
            foreach ($activity_type as $key => $value) {
                $value = explode(',', $value);
                if ( $value[1] == 1 ) {
                    return $value[0];
                }
            }
        }
        return 0;
    }

    /**
     * 市场价格默认为销售价格的1.2倍
     * @author 406764368@qq.com
     * @version 2016年11月14日 23:41:09
     */
    protected function getOprice() {
        $oprice = $_POST['oprice'];
        if ( !$oprice ) {
            $price = $_POST['price'];
            return $price * 1.2;
        }
        return $oprice;
    }

    /**
     * 活动价格默认为销售价格的0.9倍
     * @author 406764368@qq.com
     * @version 2016年11月14日 23:43:19
     */
    protected function getActivityPrice() {
        $activity_price = $_POST['activity_price'];
        if ( !$activity_price ) {
            $price = $_POST['price'];
            return $price * 0.9;
        }
        return $activity_price;
    }
}