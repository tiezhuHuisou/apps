<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
	 /**
     * 后台控制器初始化
     */
    protected function _initialize(){
        $user_id=session("user_id");
        define(UID,$user_id);
        if(!UID){
            redirect("?g=admin&m=login");
        }
        /* 查询分销功能是否开启 */
        $this->distribution_flag = M('Conf')->where(array('name'=>'distribution'))->getField('value');

        $map['user_id']=array('eq',UID);
        $user_info=M('AdminUser')->where($map)->find();
        if(C("USER_ADMINISTRATOR")!=UID){
            if($user_info['role_id'] != 1){//用户不是超级管理员
               $access =   $this->accessControl();
                if(false === $access ){
                    $this->error('403:禁止访问');
                }else if(null === $access){
                    //检测访问权限
					if(strstr(ACTION_NAME,'del')){
						$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.'index');
					}else{
						$rule  = strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
					}
                    $map_rule['rule_name']=array('eq',$rule);  
                    $rule_id=M('AdminRule')->where($map_rule)->getField('id');

                    /*组权限*/
                    $map_group['gid']=array('eq',$user_info['gid']);
                    $group_rule=M('AdminGroup')->where($map_group)->getField('rule');
                    if($group_rule){
                        $group_rule=explode(",", $group_rule);
                        if(!in_array($rule_id,$group_rule)){
                            $this->error('403:禁止访问');
                        }
                    }else{
                        $this->error('403:禁止访问');
                    }
                } 
            }else{
                $this->user_auth_sign=1;
            }
        }
        /* 订单自动确认收货 */
//        $this->orderAutoConfirmReceive();
    }

//    /**
//     * 
//     * 订单自动确认收货
//     * 
//     * @author 406764368@qq.com 黄东
//     * @version 2017年2月4日 13:38:14
//     * 
//     */
//    public function orderAutoConfirmReceive() {
//        $nowTime    = time();
//        $orderModel = M('Order');
//        $autoDays   = C('AUTO_CONFIRM_RECEIVE_DAYS');
//
//        /* 查询到期的订单 */
//        $where['state']     = array('eq', 2);
//        $where['send_time'] = array('elt', $nowTime - ($autoDays * 86400));
//        $orderList = M('Order')->field('id,uid,company_id,send_time')->where($where)->select();
//
//        /* 更新数据 */
//        if ( $orderList ) {
//            $orderClogModel   = M('OrderClog');
//            $withdrawalsModel = M('Withdrawals');
//            $commissionModel  = M('Commission');
//            $companyModel     = M('Company');
//
//            foreach ($orderList as $value) {
//                /* 更新订单数据 */
//                $orderModel->where(array('id'=>$value['id']))->setField('state', 3);
//
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $value['id'];
//                $clogDatas['action']   = 15;
//                $clogDatas['uid']      = $value['uid'];
//                $clogDatas['remark']   = '系统自动确认收货';
//                $clogDatas['addtime']  = $value['send_time'] + ( $autoDays * 86400 );
//                $orderClogModel->add($clogDatas);
//
//                /* 更新商家利润数据 */
//                $withdrawalsDatas['status'] = -2;
//                $withdrawalsDatas['etime']  = $nowTime;
//                $withdrawalsModel->where(array('order_id' => $value['id']))->save($withdrawalsDatas);
//
//                /* 扣除商家销售利润（发放佣金） */
//                $withdrawals = $commissionModel->where(array('order_id' => $value['id'], 'status' => 0))->sum('commission');
//                if ($withdrawals) {
//                    $deductCommissionDatas['order_id']    = $value['id'];
//                    $deductCommissionDatas['withdrawals'] = $withdrawals;
//                    $deductCommissionDatas['cname']       = $companyModel->where(array('id' => $value['company_id']))->getField('name');
//                    $deductCommissionDatas['cid']         = $value['company_id'];
//                    $deductCommissionDatas['etime']       = $nowTime;
//                    $deductCommissionDatas['ctime']       = $nowTime;
//                    $deductCommissionDatas['status']      = 4;
//                    M('Withdrawals')->add($deductCommissionDatas);
//
//                    /* 更新佣金数据 */
//                    $commissionModel->where(array('order_id' => $value['id']))->setField('status', 1);
//                }
//            }
//        }
//    }
//
//    /**
//     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
//     *
//     * @return boolean|null  返回值必须使用 `===` 进行判断
//     *
//     *   返回 **false**, 不允许任何人访问(超管除外)
//     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
//     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
//     * @author 122837594@qq.com
//     */
//    protected function accessControl(){
//        $allow = C('ALLOW_VISIT');
//        $deny  = C('DENY_VISIT');
//        $check = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
//
//        if ( !empty($deny)  && in_array_case($check,$deny) ) {
//            return false;//非超管禁止访问deny中的方法
//        }
//        if ( !empty($allow) && in_array_case($check,$allow) ) {
//            return true;
//        }
//
//        return null;//需要检测节点权限
//    }
//
//
//    /**
//     * 对数据表中的单行或多行记录执行修改 GET参数id为数字或逗号分隔的数字
//     *
//     * @param string $model 模型名称,供M函数使用的参数
//     * @param array  $data  修改的数据
//     * @param array  $where 查询时的where()方法的参数
//     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
//     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
//     *
//     * @author 122837594@qq.com
//     */
//    final protected function editRow ( $model ,$data, $where , $msg ){
//        $id    = array_unique((array)I('id',0));
//        $id    = is_array($id) ? implode(',',$id) : $id;
//        //如存在id字段，则加入该条件
//        $fields = M($model)->getDbFields();
//        if(in_array('id',$fields) && !empty($id)){
//            $where = array_merge( array('id' => array('in', $id )) ,(array)$where );
//        }
//
//        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
//        if( M($model)->where($where)->save($data)!==false ) {
//            $this->success($msg['success'],$msg['url'],$msg['ajax']);
//        }else{
//            $this->error($msg['error'],$msg['url'],$msg['ajax']);
//        }
//    }
//
//    /**
//     * 禁用条目
//     * @param string $model 模型名称,供D函数使用的参数
//     * @param array  $where 查询时的 where()方法的参数
//     * @param array  $msg   执行正确和错误的消息,可以设置四个元素 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
//     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
//     *
//     * @author 122837594@qq.com
//     */
//    protected function forbid ( $model , $where = array() , $msg = array( 'success'=>'状态禁用成功！', 'error'=>'状态禁用失败！')){
//        $data    =  array('status' => 0);
//        $this->editRow( $model , $data, $where, $msg);
//    }
//
//    /**
//     * 恢复条目
//     * @param string $model 模型名称,供D函数使用的参数
//     * @param array  $where 查询时的where()方法的参数
//     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
//     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
//     *
//     * @author 122837594@qq.com
//     */
//    protected function resume (  $model , $where = array() , $msg = array( 'success'=>'状态恢复成功！', 'error'=>'状态恢复失败！')){
//        $data    =  array('status' => 1);
//        $this->editRow(   $model , $data, $where, $msg);
//    }
//
//    /**
//     * 还原条目
//     * @param string $model 模型名称,供D函数使用的参数
//     * @param array  $where 查询时的where()方法的参数
//     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
//     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
//     * @author 122837594@qq.com
//     */
//    protected function restore (  $model , $where = array() , $msg = array( 'success'=>'状态还原成功！', 'error'=>'状态还原失败！')){
//        $data    = array('status' => 1);
//        $where   = array_merge(array('status' => -1),$where);
//        $this->editRow(   $model , $data, $where, $msg);
//    }
//
//    /**
//     * 条目假删除
//     * @param string $model 模型名称,供D函数使用的参数
//     * @param array  $where 查询时的where()方法的参数
//     * @param array  $msg   执行正确和错误的消息 array('success'=>'','error'=>'', 'url'=>'','ajax'=>false)
//     *                     url为跳转页面,ajax是否ajax方式(数字则为倒数计时秒数)
//     *
//     * @author 122837594@qq.com
//     */
//    protected function delete ( $model , $where = array() , $msg = array( 'success'=>'删除成功！', 'error'=>'删除失败！')) {
//        $data['status']         =   -1;
//        $this->editRow(   $model , $data, $where, $msg);
//    }
//
//    /**
//     * 设置一条或者多条数据的状态
//     */
//    public function setStatus($Model=CONTROLLER_NAME){
//
//        $ids    =   I('request.ids');
//        $status =   I('request.status');
//        if(empty($ids)){
//            $this->error('请选择要操作的数据');
//        }
//
//        $map['id'] = array('in',$ids);
//        switch ($status){
//            case -1 :
//                $this->delete($Model, $map, array('success'=>'删除成功','error'=>'删除失败'));
//                break;
//            case 0  :
//                $this->forbid($Model, $map, array('success'=>'禁用成功','error'=>'禁用失败'));
//                break;
//            case 1  :
//                $this->resume($Model, $map, array('success'=>'启用成功','error'=>'启用失败'));
//                break;
//            default :
//                $this->error('参数错误');
//                break;
//        }
//    }



    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model  $model   模型名或模型实例
     * @param array        $where   where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order   排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean      $field   单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 122837594@qq.com
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists ($model,$where=array(),$order='',$field=true){
        $options    =   array();
        $REQUEST    =   (array)I('request.');
        unset($REQUEST['aliyungf_tc'], $REQUEST['PHPSESSID'], $REQUEST['app_cityid']);
        if(is_string($model)){
            $model  =   M($model);
        }

        $OPT        =   new \ReflectionProperty($model,'options');
        $OPT->setAccessible(true);

        $pk         =   $model->getPk();
        if($order===null){
            //order置空
        }else if ( isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']),array('desc','asc')) ) {
            $options['order'] = '`'.$REQUEST['_field'].'` '.$REQUEST['_order'];
        }elseif( $order==='' && empty($options['order']) && !empty($pk) ){
            $options['order'] = $pk.' desc';
        }elseif($order){
            $options['order'] = $order;
        }
        unset($REQUEST['_order'],$REQUEST['_field']);

        if(empty($where)){
            $where  =   array('status'=>array('egt',0));
        }
        if( !empty($where)){
            $options['where']   =   $where;
        }
        $options      =   array_merge( (array)$OPT->getValue($model), $options );
        $total        =   $model->where($options['where'])->count();

        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        
        $this->assign('_page', $p? $p: '');
        $this->assign('_total',$total);
        $options['limit'] = $page->firstRow.','.$page->listRows;
        $model->setProperty('options',$options);

        return $model->field($field)->select();
    }

    /**
     * 分享到行业圈
     * @param  [array]  $datas [数据]
     * $datas['type'] 标志来源
     * 1->资讯详情
     * 2->产品详情
     * 3->求购详情
     * 4->企业主页
     * 5->企业相册
     */
    protected function shareToCircle( $datas = array() ) {
        switch ($datas['type']) {
            case '1':
                $datas['type'] = 'news_detail';
                break;
            case '2':
                $datas['type'] = 'product_detail';
                break;
            case '3':
                $datas['type'] = 'need_detail';
                break;
            case '4':
                $datas['type'] = 'company_home';
                break;
            case '5':
                $datas['type'] = 'company_album';
                break;
            default:
                # code...
                break;
        }
        M('Circle')->add($datas);
    }

    /**
     * 查询不同模块的所有数据
     * @param  [String] $modelName  [要查询的模块名字]
     * product_detail->产品
     * news_detail->资讯
     * need_detail->求购
     * company_home->企业
     * @return [Array]              [模块下的所有数据]
     */
    public function getModelAllDatas( $modelName = '' ) {
        IS_AJAX && $modelName = I('request.model_name');

        switch ($modelName) {
            case 'news_detail':
                $dataModel = M('Article');
                $field = 'id,title';
                $map['is_display'] = array('eq', 1);
                $order = 'flags DESC,sort DESC,uptime DESC';
                break;
//            case 'product_detail':
//                $dataModel = M('ProductSale');
//                $field = 'id,title';
//                $map['status'] = array('eq', 1);
//                $order = 'flags DESC,sort DESC,modify_time DESC';
//                break;
//            case 'need_detail':
//                $dataModel = M('ProductBuy');
//                $field = 'id,title';
//                $map['status'] = array('eq', 1);
//                $order = 'flags DESC,sort DESC,modify_time DESC';
//                break;
            case 'company_home':
                $dataModel = M('Company');
                $field = 'id,name title';
                $map['status'] = array('eq', 1);
                $order = 'flags DESC,sort DESC,modify_time DESC';
                break;
//            default:
//                $dataModel = M('ProductSale');
//                $field = 'id,title';
//                $map['status'] = array('eq', 1);
//                $order = 'flags DESC,sort DESC,modify_time DESC';
//                break;
        }
        $dataList = $dataModel->field($field)->where($map)->order($order)->select();
        IS_AJAX && $this->ajaxReturn($dataList);
        return $dataList;
    }
}