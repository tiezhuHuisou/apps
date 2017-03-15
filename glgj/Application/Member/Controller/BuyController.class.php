<?php
namespace Member\Controller;
use Think\Controller;
class BuyController extends MemberController {
	/* 订单状态 */
	private $statusList = array(
		'0' => '待付款',
		'1' => '待发货',
		'2' => '待收货',
		'3' => '待评价',
		'4' => '退款中',
		'5' => '交易关闭',
		'6' => '申请退款',
		'7' => '退款被拒',
		'8' => '交易完成',
		'9' => '已退款'
	);
	public function _initialize(){
		parent::_initialize();
		$this->assign('site', 'buy');
	}

	/**
	 * 订单管理
	 * @return [type] [description]
	 */
	public function index(){
	    $status = I('get.state');
	    $where['uid'] = UID;
		$where['state'] = $status;
	    $order = 'etime desc';
	    $list = $this->lists('order',$where,$order);
		$order_sub = M('Order_sub');
	    $field="title,img,price";
	    foreach ($list as &$val){
	        $condition['orderid'] = $val['id'];
			$val['productList'] = $order_sub->where($condition)->field('goodname,gpic,nums,unitprice,totalprice,seller')->select();
	    }
	    $this->assign('list',$list);
	    $this->assign('state',$status);
		$this->assign('statusList',$this->statusList);
		$this->site_title = '订单管理';
		$this->assign('header', 'index');
		$this->display();
	}

	/**
	 * 订单详情
	 * @return [type] [description]
	 */
	public function detail(){
	    $orderid = I('id');
	    empty($orderid) && $this->error('参数错误');
	    $order = M('Order');
	    $ordersub = M('OrderSub');
	    $address = M('Address');
	    $regions = M('Regions');
	    $order = $order->where(array('id' =>$orderid))->find();
	    $address = $address->where(array('id'=>$order['address_id']))->find();
	    $tmp = trim($address['region'],',');
	    $where['id'] = array('in',$tmp);
	    $regions = $regions->where($where)->select();
	    $ordersub = $ordersub->where(array('orderid'=>$orderid))->select();
	    $product = D('ProductSale');
	    foreach ($ordersub as $key=>&$val){//检查该商品是否还存在
	        $tmp = $product->getProductSaleInfo(array('id'=>$val['goodid']));
	        if($tmp !='' && $tmp != false){
	            $val['tmp'] = 1;
	        }
	    }
	    $this->assign('order',$order);
	    $this->assign('address',$address);
	    $this->assign('regions',$regions);
	    $this->assign('ordersub',$ordersub);
		$this->site_title = '订单详情';
		$this->assign('header', 'index');
		$this->display();
	}
    
	/**
	 * 删除订单
	 */
	public function delOrder(){
		$id=I('get.id');
		empty($id) && $this->error('参数错误');
		$map_order['id']=array('eq',$id);
		$map_order['uid']=array('eq',UID);
		$map_data['state']=-1;
		$status=M('Order')->where($map_order)->save($map_data);
		if($status){
			$map_clog_data['action']=6;
			$map_clog_data['order_id'] = $id;
			$map_clog_data['uid'] = UID;
			$map_clog_data['remark'] = '买家删除订单';
			$map_clog_data['addtime'] = time();
			$statusEnd = M('Order_clog')->add($map_clog_data);
			if($statusEnd){
				$this->success('删除订单成功');
			}else{
				$this->error('删除订单失败');
			}
		}else{
			$this->error('未知的错误删除失败');
		}
	}

	/**
	 * 申请退款
	 */
	public function refund(){
	    $orderid=I('post.orderid',0,'intval');
	    empty($orderid) && $this->error('参数错误');
	    $map['orderid']=array('eq',$orderid);
	    $map['userid']=array('eq',UID);
	    $data['state']=4;
	    $status=M('Order')->where($map)->save($data);
	    if($status){
	        $this->success('申请退款成功');
	    }else{
	        $this->error('申请退款失败');
	    }
	}
	
	/**
	 * 完成退款
	 */
	public function surerefund(){
	    $orderid=I('post.orderid',0,'intval');
	    empty($orderid) && $this->error('参数错误');
	    $map['orderid']=array('eq',$orderid);
	    $map['userid']=array('eq',UID);
	    $data['state']=5;
	    $status=M('Order')->where($map)->save($data);
	    if($status){
	        $this->success('申请退款成功');
	    }else{
	        $this->error('申请退款失败');
	    }
	}
	
	/**
	 * 取消退款
	 */
	public function cancelrefund(){
	    $orderid=I('post.orderid',0,'intval');
	    empty($orderid) && $this->error('参数错误');
	    $map['orderid']=array('eq',$orderid);
	    $map['userid']=array('eq',UID);
	    $data['state']=1;
	    $status=M('Order')->where($map)->save($data);
	    if($status){
	        $this->success('取消退款成功');
	    }else{
	        $this->error('取消退款失败');
	    }
	}
	
	/**
	 * 确认收货
	 */
	public function delivery(){
	    $orderid=I('post.orderid',0,'intval');
	    empty($orderid) && $this->error('参数错误');
	    $map['orderid']=array('eq',$orderid);
	    $map['userid']=array('eq',UID);
	    $data['state']=3;
	    $status=M('Order')->where($map)->save($data);
	    if($status){
	        $this->success('确认收货成功');
	    }else{
	        $this->error('确认收货失败');
	    }
	}
	/**
	 * 物流跟踪
	 * @return [type] [description]
	 */
	public function logistics(){
	    $orderid = I('id');
	    empty($orderid) && $this->error('参数错误');
	    $order = M('Order');
	    $ordersub = M('OrderSub');
	    $order = $order->where(array('id' =>$orderid))->find();
	    $this->assign('order',$order);
	    /*选中物流公司名称*/
	    $express_name =  M('Freight')->where(array('id'=>$order['freight_id']))->getField('title');
	    $this->assign('express_name',$express_name);
	    /*物流信息*/
	    $url 	= "http://www.kuaidi100.com/query?&type=".$order['express_company']."&postid=".$order['express_number'];
	    $exress_info = json_decode($this->http_get($url),true);
	    //var_dump($exress_info);exit;
	    $this->assign('exress_info',$exress_info);
		$this->site_title = '物流跟踪';
		$this->assign('header', 'index');
		$this->display();
	}
	/**
	 * curl get 方法
	 * @param $url 请求地址
	 */
	private function http_get($url){
	    $oCurl = curl_init();
	    if(stripos($url,"https://")!==FALSE){
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
	        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    }
	    curl_setopt($oCurl, CURLOPT_URL, $url);
	    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	    $sContent = curl_exec($oCurl);
	    $aStatus = curl_getinfo($oCurl);
	    curl_close($oCurl);
	    if(intval($aStatus["http_code"])==200){
	        return $sContent;
	    }else{
	        return false;
	    }
	}
	/**
	 * 给卖家的评价
	 * @return [type] [description]
	 */
	public function comment(){
	    $order = 'addtime desc';
	    $condition['type'] = 1;
	    //$condition['state'] = 1;
	   // $list = M('Review')->where($condition)->order($order)->limit(0,10)->select();
	    $pro = M('ProductSale');
	    $company = M('Company');
	    foreach ($list as $key=>$val){
	        //商品信息
	        $goods = $pro->where(array('id'=>$val['goodid']))->find();
	        $list[$key]['price'] = $goods['price'];
	        $list[$key]['goods_name'] = $goods['title'];
	        //公司名称
	        $list[$key]['name'] = $company->where(array('id ='=>$goods['company_id']))->getField('name');
	    }
	    $this->assign('list',$list);
		$this->site_title = '给卖家的评价';
		$this->assign('header', 'comment');
		$this->display();
	}

	/**
	 * 卖家回复
	 * @return [type] [description]
	 */
	public function reply(){
	    $order = 'addtime desc';
	    $condition['type'] = 1;
	    //$condition['state'] = 1;
	    //$list = M('Review')->where($condition)->order($order)->limit(0,10)->select();
	    $pro = M('ProductSale');
	    $company = M('Company');
	    //$review = M('Review');
	    $reply = array();
	    foreach ($list as $key=>$val){
	        $condition['pid'] = $val['id'];
	        $condition['uid'] = array('gt',0);
	        $reply = $review->where($condition)->select();
	        foreach ($reply as $key1=>$val1){
	        //公司名称
	        $goods = $pro->where(array('id'=>$val1['goodid']))->find();
	        $reply[$key1]['name'] = $company->where(array('id ='=>$goods['company_id']))->getField('name');
	    
	        }
	    }
	    $this->assign('list',$reply);
		$this->site_title = '卖家回复';
		$this->assign('header', 'comment');
		$this->display();
	}

	/**
	 * 收货地址管理
	 * @return [type] [description]
	 */
	public function address(){
		/*省市*/
	    $region=M('Regions')->cache(true)->select();
	    foreach($region as $val){
	    	$region_arr[$val['id']]=$val['name'];
	    }
	    $map['owner']=array('eq',UID);
		$list=M('Address')->where($map)->order("type desc,id desc")->select();
		
		foreach($list as $key=>$val){
			$val['region']=explode(",", trim($val['region'],','));
			$list[$key]['address_detail']=$region_arr[$val['region'][0]].$region_arr[$val['region'][1]].$region_arr[$val['region'][2]];

		}

		$this->assign('list',$list);
		/*页面公用设置*/
		$this->site_title = '收货地址管理';
		$this->assign('header', 'address');
		$this->display();
	}

	/**
	 * 新增收货地址页面
	 * @return [type] [description]
	 */
	public function add(){
		$id=I('get.id',0,'intval');
		/*省市*/
	    $region=M('Regions')->cache(true)->select();
	    foreach($region as $val){
	    	if(!$val['parent']){
	    		$region_arr[0][]=$val;
	    	}else{
	    		$region_arr[$val['parent']][]=$val;
	    	}	    	
	    }
	    $this->assign('region_arr',$region_arr);
	    $this->assign('region_arr_json',json_encode($region_arr));

	    if($id){
	    	$map['owner']=array('eq',UID);
	    	$map['id']=array('eq',$id);
	    	$info=M('Address')->where($map)->find();
	    	if(!$info){
	    		$this->error('收货地址不存在');
	    	}
	    	$info['region']=explode(",", trim($info['region'],','));
	    	$info['region_count']=count($info['region']);
	    	$this->assign('info',$info);
	    	/* 页面基本设置 */
	    	$this->site_title 		= "编辑收货地址";
	    }else{
	    	/* 页面基本设置 */
	    	$this->site_title = '新增收货地址';
	    }
		
		$this->assign('header', 'address');
		$this->display();
	}

	/**
	 * 新增收货地址
	 */
	public function increaseaddr(){

		$map['owner']=array('eq',UID);
		$count=M('Address')->where($map)->count();
		if($count>=5){
			$this->error('用户最多可添加5个收货地址');
		}
		$address = D('Address');
		$status=$address->update();
		if($status){
			$this->success('保存收货地址成功','?g=member&m=buy&a=address');
		}else{
			$errorInfo = $address->getError();
                $this->error($errorInfo);
		}
	}

	/**
	 * 设置默认地址
	 * @return [type] [description]
	 */
	public function setaddr(){
		$id=I('get.id',0,'intval');
		empty($id) && $this->error('参数不能为空');
		$map['owner']=array('eq',UID);
		$data['type']=0;
		M('Address')->where($map)->save($data);
		$map['id']=array('eq',$id);
		$data_type['type']=1;
		$status=M('Address')->where($map)->save($data_type);

		if($status){
			$this->success('设置默认地址成功');
		}else{
			$this->error("设置默认地址失败");
		}
	}

	/**
	 * 删除收货地址
	 */
	public function deladdr(){
		$id=I('get.id',0,'intval');
		empty($id) && $this->error('参数不能为空');
		$map['id']=array('eq',$id);
		$map['owner']=array('eq',UID);
		$status=M('Address')->where($map)->delete();
		if(!$status){
			$this->error('删除失败');
		}else{
			$this->success("删除成功");
		}
	}
	
}