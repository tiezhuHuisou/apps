<?php
namespace Member\Model;
use Think\Model;
class ProductBuyModel extends Model{
    /*自动验证*/
    protected $_validate = array(
        array('title', 'require', '求购标题不能为空', self::MUST_VALIDATE),
        array('title', '2,50', '求购标题长度为2-50位字符', self::MUST_VALIDATE ,'length'),
        array('short_title', 'require', '求购副标题不能为空', self::MUST_VALIDATE),
        array('short_title', '2,100', '求购副标题长度为2-100位字符', self::MUST_VALIDATE ,'length'),
        array('buy_category_id', 'require', '分类不能为空', self::MUST_VALIDATE),
        array('company_id', 'require', '请先登录', self::MUST_VALIDATE),
        array('num', 'require', '数量不能为空', self::MUST_VALIDATE),
        array('num', 'number', '请填写有效数字', self::MUST_VALIDATE),
        // array('unit', '1,4', '单位应为1-4个字符', self::VALUE_VALIDATE, 'length'),
        array('days', 'require', '有效天数不能为空', self::MUST_VALIDATE),
        array('days', 'number', '请填写有效数字', self::MUST_VALIDATE),
        array('days', '0,90', '有效天数范围为0~90天', self::MUST_VALIDATE,'between'),
        array('price', 'require','价格不能为空', self::MUST_VALIDATE),
        array('price', 'checkPrice', '价格格式不正确', self::MUST_VALIDATE,'callback')
    );
    /*自动完成*/
    protected $_auto = array (
        array('flags','get_flags',Model::MODEL_BOTH,'callback'),
        array('issue_time','time',Model::MODEL_INSERT,'function'),
        array('modify_time','time',Model::MODEL_BOTH,'function')
    );
    /**
     * 取得单个商品信息
     *
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @author 83961014@qq.com
     */
    public function getProductBuyInfo($condition = array(),$field="*") {
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
        $imgAll = $_POST['allpic'];
        if ( is_array($imgAll) && count($imgAll) ) {
            $count = count($imgAll);
            $data['img'] = $imgAll[0];
        } else {
            $this->error = '请上传求购详情轮播图';
            return false;
        }
        /* 添加或更新 */
        if(empty($data['id'])){ //新增
            $id = $this->add($data);
            if(!$id){
                $this->error = '新增出错！';
                return false;
            }
            $data['id'] = $id;
    
        } else { //更新
            unset($data['proid']);
            $status = $this->save($data);
            if(false === $status){
                $this->error = '更新出错！';
                return false;
            }
            M('NeedPicture')->where(array('need_id'=>$data['id']))->delete();
        }

        for($i=0;$i<$count;$i++){
            $picdata[$i]['need_id'] = $data['id'];
            $picdata[$i]['pic_url'] = $imgAll[$i];
            $picdata[$i]['addtime'] = time();
        }
        M('NeedPicture')->addAll($picdata);
        return $data;
    }
    
    /**
     * 删除商品
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     * @author 83961014@qq.com
     */
    public function delProductBuy($condition){
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
     * 验证价格
     */
    protected function checkPrice() {
        $price = I('post.price');
        $price = explode(',', $price);
        $count = count($price);
        switch ($count) {
            case '1':
                if ( !preg_match('/^\d+(\.\d{1,2})?$/', $price[0]) ) {
                    return false;
                }
                break;
            case '2':
                foreach ($price as $value) {
                    if ( !preg_match('/^\d+(\.\d{1,2})?$/', $value) ) {
                        return false;
                    }
                }
                break;
            default:
                return false;
                break;
        }
        return true;
    }
}