<?php
namespace Member\Model;
use Think\Model;
class ProductSaleModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('title', 'require', '产品标题不能为空', self::MUST_VALIDATE),
        array('title', '1,50', '产品标题长度为1-50位字符', self::MUST_VALIDATE ,'length'),
        array('short_title', 'require', '产品副标题不能为空', self::MUST_VALIDATE),
        array('short_title', '1,100', '产品副标题长度为1-100位字符', self::MUST_VALIDATE ,'length'),
        array('num', 'require', '库存不能为空', self::MUST_VALIDATE),
        array('num', 'number', '库存只能为数字', self::MUST_VALIDATE),
        array('price', 'require','价格不能为空', self::MUST_VALIDATE),
        array('price', 'money','价格格式不正确', self::MUST_VALIDATE),
        array('activity_price', 'checkActivityPrice','活动价格不能为空', self::MUST_VALIDATE, 'callback'),
        array('activity_price', 'money','活动价格格式不正确', self::VALUE_VALIDATE),
        array('num', 'require', '库存不能为空', self::MUST_VALIDATE),
        array('num', 'number', '库存应为数字', self::MUST_VALIDATE),
        array('buymin', 'require', '最小购买数量不能为空', self::MUST_VALIDATE),
        array('buymin', 'number', '最小购买数量应为数字', self::MUST_VALIDATE),
        array('buymin', '0', '最小购买数量不能小于1', self::MUST_VALIDATE, 'gt'),
        array('summary', 'require', '商品详情不能为空', self::MUST_VALIDATE),
    );
    /*自动完成*/
    protected $_auto = array (
        array('flags','get_flags',Model::MODEL_BOTH,'callback'),
        array('issue_time','time',Model::MODEL_INSERT,'function'),
        array('modify_time','time',Model::MODEL_BOTH,'function'),
        array('company_id','get_company',Model::MODEL_BOTH,'callback'),
        
    );
    /**
     * 取得单个商品信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getProductSaleInfo($condition = array(),$field="*") {
        return $this->where($condition)->field($field)->find();
    }
    /**
     * 添加/编辑商品
     * @author 83961014@qq.com
     */
    public function update(){
        /* 获取数据对象 */
        $data=$_POST;
        $data = $this->create($data);
        if(empty($data)){
            return false;
        }
        /* 分类验证、处理 */
        $category = $_POST['sale_category_id'];
        if ( is_array($category) && count($category) ) {
            if ( count($category) > 5 ) {
                $this->error = '最多选择5个分类';
                return false;
            }
            $data['sale_category_id'] = implode(',', $category);
        } else {
            $this->error = '请选择分类';
            return false;
        }
        /* 轮播图验证、处理 */
        $imgAll   = $_POST['allpic'];
        $picModel = M('ProdcutPicture');
        if ( is_array($imgAll) && count($imgAll) ) {
            $count = count($imgAll);
            $data['img'] = $imgAll[0];
        } else {
            $this->error = '请上传产品详情轮播图';
            return false;
        }
        /* 添加或更新 */
        $specModel = M('ProductSpec');
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
            $data['id'] = $id;
        } else { //更新
            $status = $this->save($data);
            $picModel->where(array('product_id'=>$data['id']))->delete();
            $specModel->where(array('product_id'=>$data['id']))->delete();
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
        }
        /* 轮播图添加 */
        for($i=0;$i<$count;$i++){
            $picdata[$i]['product_id'] = $data['id'];
            $picdata[$i]['pic_url'] = $imgAll[$i];
            $picdata[$i]['addtime'] = time();
        }
        $picModel->addAll($picdata);
        /* 规格处理 */
        $is_spec   = I('post.is_spec', 0, 'intval');
        $spec      = $_POST['spec'];
        /*规格处理*/
        if ( $is_spec == 1 ) {
            $specData['product_id']         = $data['id'];
            $specData['title1']             = $spec['title1'];
            $specData['title2']             = $spec['title2'];
            for ($j     =0; $j < count($spec['spec1']); $j++) {
                $specData['spec1']          = $spec['spec1'][$j];
                $specData['spec2']          = $spec['spec2'][$j];
                $specData['price']          = $spec['price'][$j];
                $specData['oprice']         = $spec['oprice'][$j];
                $specData['activity_price'] = $spec['activity_price'][$j];
                $specData['price']          = $spec['price'][$j];
                $specData['weight']         = floatval( $spec['weight'][$j] );
                $specData['buymin']         = intval( $spec['buymin'][$j] );
                $specData['sort']           = intval( $spec['sort'][$j] );
                $specData['stock']          = intval( $spec['stock'][$j] );
                $specData['img']            = $spec['img'][$j];
                $specData['ctime']          = time();
                $specModel->add($specData);
            }
        }

        return $data;
    }
    
    /**
     * 删除商品
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delProductSale($condition){
        return $this->where($condition)->delete();
    }

    /**
     * 封装flags字段
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_flags(){
        $flags          = I('post.flags','');
        if(!empty($flags)){
            $flags          = implode(',', $flags);
            return $flags;
        }else{
            return $flags="";
        }
    }

    /**
     * 验证活动价格
     */
    protected function checkActivityPrice() {
        $activity_price = I('post.activity_price');
        $activity_type  = I('post.activity_type', 0, 'intval');
        if ( $activity_type != 0 && !preg_match('/^\d+(\.\d{1,2})?$/', $activity_price) ) {
            return false;
        }
        return true;
    }
    /**
     * 获取当前公司id
     * @return string
     * @author 83961014@qq.com
     */
    protected function get_company(){
        $map['user_id']=array('eq',UID);
        return (M('Company')->where($map)->getField('id'));
    }
}