<?php
namespace App\Controller;
use Think\Controller;
class IndexController extends AppController {
    /**
     * 页面基本设置
     * @return [type] [description]
     */
    public function _initialize() {
        parent::_initialize();
        $this->assign('site','index');
    }
    
    /**
     * 网站首页
     * @return [type] [description]
     */
    public function index() {
//        if(!cookie('cityid')){
//            /*IP定位*/
//            $ip=get_client_ip(0,true);
//            // $url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
//            $url=$url='http://api.map.baidu.com/location/ip?ak=940E5AE387e6a1c1c73f35aa6eaaff50&ip='.$ip.'&coor=bd09ll'; ;
//            $city_res=$this->http_get($url);
//            $city_obj=json_decode($city_res);
//            $city_arr=get_object_vars($city_obj->content->address_detail);
//            $point=get_object_vars($city_obj->content->point);
//            $this->assign('city_arr',$city_arr);
//        }

        /*首页轮播图*/
        $map_banner['tid']=array('eq',2);
        $list['banner']=M('Jdpic')->where($map_banner)->order('listorder,addtime desc')->limit(6)->select();

        /* 今日头条 */
        $list['articleToday']=M('Article')->order('addtime desc')->limit(6)->field('id,title')->select();

        /* 推荐企业 */
        $map_company['flags']=array('in',array('c'));
        $map_company['status']=array('eq',1);
        $list['flags_company']=M('Company')->where($map_company)->order('sort desc,modify_time desc')->field('id,name,logo')->limit(10)->select();
        foreach ($list['flags_company'] as $key=>$val){
            $list['flags_company'][$key]['name'] = mb_substr($val['name'],0,10,'utf-8');
        }

        /*推荐资讯*/
        $map_article['flags']=array('in',array('c'));
        $list['article']=M('Article')->where($map_article)->order('sort desc')->limit(3)->field("id,title,image,FROM_UNIXTIME(addtime,'%Y-%m-%d') addtime")->select();

        /*最新加入*/
        unset($map_company);
        $map_company['status']=array('eq',1);
        $list['company']=M('Company')->where($map_company)->order('issue_time desc')->field('id,name,logo')->limit(10)->select();
        foreach ($list['company'] as $key=>$val){
            $list['company'][$key]['name'] = mb_substr($val['name'],0,10,'utf-8');

        }

        /*省市*/
//        $cityid=cookie('cityid');
//        $region=M('Regions')->cache(true)->select();
//        foreach($region as $val){
//            if($val['grade']==1){
//                $province_arr[$val['first_word']][]=$val;
//            }
//        
//            if($val['grade']==2){
//                $city_arr[$val['parent']][]=$val;
//            }
//            if($val['id']==$cityid){
//                $city_name=$val['name'];
//                $pid=$val['parent'];
//            }
//            
//            if($val['name']==$city_arr['city']){
//                $city_id=$val['id'];
//            }
//            $name_arr[$val['id']]=$val;
//        
//        }
//        if($city_id){
//            cookie('cityid',$city_id);
//        }
//        
//        $this->city_name=$city_name?$city_name:$city_arr['city'];
//        $this->province_name=$name_arr[$pid]['name'];

        $this->assign('list',$list);
        /* 页面基本设置 */
        $this->site_title       = "首页";
        $this->site_keywords    = "首页";
        $this->site_description = "首页";

        $this->display();
    }


    /**
     * 保存定位市
     */
    public function savecity(){
        $city_name=I('get.city_name');
        $lng=I('get.lng');
        $lat=I('get.lat');
        $region=M('Regions')->cache(true)->select();
        foreach($region as $val){
            if($val['name']==$city_name){
                cookie('cityid',$val['id']);
                /*记录定位的经纬度*/
                cookie('location_detail',array($lng,$lat));
            }
        }

    }
    
    /**
     * 选择省、城市页面
     * @return [type] [description]
     */
    public function location() {
    $cityid=cookie('cityid');
        if(IS_POST){
            $id=I('post.id',0,'intval');
            cookie('cityid',$id);
            $this->success('切换城市成功','?g=app&m=index');
        }else{
            /*省市详情*/
            $region=M('Regions')->cache(true)->select();
            foreach($region as $val){
                if($val['grade']==1){
                    $province_arr[$val['first_word']][]=$val;
                }

                if($val['grade']==2){
                    $city_arr[$val['parent']][]=$val;
                }
                if($val['grade']==3){
                    $area_arr[$val['parent']][]=$val;
                }
                if($val['id']==$cityid){
                    $city_name=$val['name'];
                    $pid=$val['parent'];
                }

                $name_arr[$val['id']]=$val;

            }
            ksort($province_arr);
            $this->assign('province_arr',$province_arr);
            $this->assign('city_arr',json_encode($city_arr));
            $this->assign('area_arr',json_encode($area_arr));
            $this->city_name=$city_name;
            $this->province_name=$name_arr[$pid]['name'];
            /* 页面基本设置 */
            $this->site_title       = "定位";
            $this->site_keywords    = "定位";
            $this->site_description = "定位";

            $this->display();
        }
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
     * 供应页面
     * @return [type] [description]
     */
    public function supply() {
        $categoryid = I('get.categoryid', 0, 'intval');
        if(IS_GET){
            $title = I('title');
            if(!empty($title)){
                $where['title'] = array('like', '%'.$title.'%');
                $this->assign('title', $title);
            }
        }

        if($categoryid>0){
            $where['supply_category_id'] = array('eq', $categoryid);
            $this->assign('categoryid', $categoryid);
        }


        /* 求购列表 */
        $list         = M('ProductSupply')->field('id,company_id,issue_type,title,img,short_title,issue_time,days')->where($where)->order('issue_time desc')->limit(10)->select();
        $companyModel = M('Company');
        $memberModel  = M('Member');
        $prefix       = C('DB_PREFIX');
        foreach ($list as $key => $value) {
            if ( $value['days'] > 0 && ($value['issue_time'] + $value['days'] * 86400) < time() ) {
                unset($list[$key]);
                continue;
            }
            /* 获取联系方式 */
            if ( $value['issue_type'] == 2 ) {
                $list[$key]['mobile'] = $companyModel
                                      ->alias('l')
                                      ->join($prefix.'company_link r ON l.id = r.company_id', 'LEFT')
                                      ->where(array('l.user_id'=>$value['company_id']))
                                      ->getField('r.subphone');
            } else {
                $list[$key]['mobile'] = $memberModel->where(array('uid'=>$value['company_id']))->getField('mphone');
            }
        }
        $this->assign('list',$list);

        /* 求购分类 */
        $category_list = M('ProductBuyCategory')->field("id as cid,parent_id as pid,name as cname")->order("cid desc")->select();
        foreach($category_list as $val){
            $category_arr[$val['pid']][]=$val;
        }
        $this->assign('category_list',$category_arr);
        $this->assign('category_arr',json_encode($category_arr));
        /* 页面基本设置 */
        $this->site_title       = "供应中心";
        $this->site_keywords    = "供应中心";
        $this->site_description = "供应中心";

        $this->display();
    }
    
    /**
     * ajax请求供应列表
     * @return [type] [description]
     */
    public function ajaxsupplylist() {
        $num=I('get.num',0,'intval');
        $categoryid = I('get.categoryid', 0, 'intval');
        if(IS_GET){
            $title = I('title');
            if(!empty($title)){
                $where['title'] = array('like', '%'.$title.'%');
                $this->assign('title', $title);
            }
        }

        if($categoryid>0){
            $where['supply_category_id'] = array('eq', $categoryid);
            $this->assign('categoryid', $categoryid);
        }


        /* 求购列表 */
        $list         = M('ProductSupply')->field('id,company_id,issue_type,title,img,short_title,issue_time,days')->where($where)->order('issue_time desc')->limit($num,10)->select();
        $companyModel = M('Company');
        $memberModel  = M('Member');
        $prefix       = C('DB_PREFIX');
        foreach ($list as $key => $value) {
            if ( $value['days'] > 0 && ($value['issue_time'] + $value['days'] * 86400) < time() ) {
                unset($list[$key]);
                continue;
            }
            /* 获取联系方式 */
            if ( $value['issue_type'] == 2 ) {
                $list[$key]['mobile'] = $companyModel
                                      ->alias('l')
                                      ->join($prefix.'company_link r ON l.id = r.company_id', 'LEFT')
                                      ->where(array('l.user_id'=>$value['company_id']))
                                      ->getField('r.subphone');
            } else {
                $list[$key]['mobile'] = $memberModel->where(array('uid'=>$value['company_id']))->getField('mphone');
            }
        }
        $this->ajaxReturn($list);
    }

    /**
     * 供应详情页
     * @return [type] [description]
     */
    public function supplyDetail() {
        $id = I('get.id', 0, 'intval');
        if ( $id === 0 ) {
            $this->error('非法操作');
        }
        $detail = M("ProductSupply")->where(array("id"=>$id))->find();
        $detail['limit'] = $detail['issue_time'] + $detail['days'] * 86400;
        if ( $detail['days'] > 0 && $detail['limit'] < time() ) {
            $this->error('该供应信息已过期');
        }
        /* 获取联系方式 */
        if ( $detail['issue_type'] == 2 ) {
            $companyInfo = M('Company')->field('id,name')->where(array('user_id'=>$detail['company_id']))->find();
            $info = M('CompanyLink')->field('subphone mphone,qq')->where(array('company_id'=>$companyInfo['id']))->find();
            $info['issue_name'] = $companyInfo['name'];
        } else {
            $info = M('Member')->field('name issue_name,mphone,qq')->where(array('uid'=>$detail['company_id']))->find();
        }

        $detail['mobile']     = $info['mphone'];
        $detail['qq']         = $info['qq'];
        $detail['issue_name'] = $info['issue_name'] ? $info['issue_name'] : '佚名';
        $detail['is_login']   = 0;
        if ( $this->user_id > 0 ) {
            $data['aid'] = $id;
            $data['uid'] = $this->user_id;
            $data['favorite_category'] = 4;
            $favorite = M("UserFavorite")->where($data)->find();
            if ( $favorite != "" || $favorite != false ) {
                /* 判断当前用户有没有收藏该内容 */
                $this->assign('sign', 1);
            }
            $this->assign('uid', $this->user_id);
        }
        $this->assign('detail',$detail);
        

        /* 页面基本设置 */
        $this->site_title       = "供应详情";
        $this->site_keywords    = "供应详情";
        $this->site_description = "供应详情";

        $this->display();
    }

    /**
     * 求购页面
     * @return [type] [description]
     */
    public function need() {
        $categoryid = I('get.categoryid', 0, 'intval');
        if(IS_GET){
            $title = I('title');
            if(!empty($title)){
                $where['title'] = array('like', '%'.$title.'%');
                $this->assign('title', $title);
            }
        }

        if($categoryid>0){
            $where['buy_category_id'] = array('eq', $categoryid);
            $this->assign('categoryid', $categoryid);
        }


        /* 求购列表 */
        $list         = M('ProductBuy')->field('id,company_id,issue_type,title,img,short_title,issue_time,days')->where($where)->order('issue_time desc')->limit(10)->select();
        $companyModel = M('Company');
        $memberModel  = M('Member');
        $prefix       = C('DB_PREFIX');
        foreach ($list as $key => $value) {
            if ( $value['days'] > 0 && ($value['issue_time'] + $value['days'] * 86400) < time() ) {
                unset($list[$key]);
                continue;
            }
            /* 获取联系方式 */
            if ( $value['issue_type'] == 2 ) {
                $list[$key]['mobile'] = $companyModel
                                      ->alias('l')
                                      ->join($prefix.'company_link r ON l.id = r.company_id', 'LEFT')
                                      ->where(array('l.user_id'=>$value['company_id']))
                                      ->getField('r.subphone');
            } else {
                $list[$key]['mobile'] = $memberModel->where(array('uid'=>$value['company_id']))->getField('mphone');
            }
        }
        $this->assign('list',$list);

        /* 求购分类 */
        $category_list=M('ProductBuyCategory')->field("id as cid,parent_id as pid,name as cname")->order("cid desc")->select();
        foreach($category_list as $val){
            $category_arr[$val['pid']][]=$val;
        }
        $this->assign('category_list',$category_arr);
        $this->assign('category_arr',json_encode($category_arr));
        /* 页面基本设置 */
        $this->site_title       = "求购中心";
        $this->site_keywords    = "求购中心";
        $this->site_description = "求购中心";

        $this->display();
    }

    /**
     * ajax请求求购列表
     * @return [type] [description]
     */
    public function ajaxneedlist() {
        $categoryid=I('get.categoryid',0,'intval');
        $num=I('get.num',0,'intval');
    
        if($categoryid){
            $where['buy_category_id']=array('eq',$categoryid);
            $this->assign('categoryid',$categoryid);
        }
        $title = I('title');
        if(!empty($title)){
            $where['title'] = array('like','%'.$title.'%');
            $this->assign('title',$title);
        }
        $where['status'] = array('eq',1);
        $list = M('ProductBuy')->where($where)->order('issue_time desc')->limit($num,10)->select();
        $companylink = M('CompanyLink');
        $company = M('Company');
        $time = time();
        foreach ($list as $key=>$val){
            $tmp = $companylink->where(array( 'company_id'=>$val['company_id']))->field('contact_user')->find();
            $tmp1 = $company->where(array('id'=>$val['company_id']))->field('logo')->find();
            $list[$key]['buy_name'] = $tmp['contact_user'];
            $list[$key]['logo'] = $tmp1['logo'];
            if($val['days'] != 0){
               if(($list[$key]['issue_time']+86400*($val['days'])) > $time){
                   $list[$key]['issue_time'] = mdate($list[$key]['issue_time']);
                }else{
                    unset($list[$key]);
                }
            }else{
                $list[$key]['issue_time'] = mdate($list[$key]['issue_time']);
            }
        }
        $this->ajaxReturn($list);
    }
    
    /**
     * 求购详情页
     * @return [type] [description]
     */
    public function need_detail() {
        $id = I('get.id', 0, 'intval');
        if ( $id === 0 ) {
            $this->error('非法操作');
        }
        $detail = M("ProductBuy")->where(array("id"=>$id))->find();
        $detail['limit'] = $detail['issue_time'] + $detail['days'] * 86400;
        if ( $detail['days'] > 0 && $detail['limit'] < time() ) {
            $this->error('该求购信息已过期');
        }
        /* 获取联系方式 */
        if ( $detail['issue_type'] == 2 ) {
            $companyInfo = M('Company')->field('id,name')->where('user_id = '.$detail['company_id'])->find();
            $info = M('CompanyLink')->field('subphone mphone,qq')->where('company_id = '.$companyInfo['id'])->find();
            $info['issue_name'] = $companyInfo['name'];
        } else {
            $info = M('Member')->field('name issue_name,mphone,qq')->where('uid = '.$detail['company_id'])->find();
        }
        $detail['mobile']     = $info['mphone'];
        $detail['qq']         = $info['qq'];
        $detail['issue_name'] = $info['issue_name'] ? $info['issue_name'] : '佚名';
        $detail['is_login']   = 0;
        if ( $this->user_id > 0 ) {
            $data['aid'] = $id;
            $data['uid'] = $this->user_id;
            $data['favorite_category'] = 4;
            $favorite = M("UserFavorite")->where($data)->find();
            if ( $favorite != "" || $favorite != false ) {
                /* 判断当前用户有没有收藏该内容 */
                $this->assign('sign', 1);
            }
            $this->assign('uid', $this->user_id);
        }
        $this->assign('detail',$detail);
        

        /* 页面基本设置 */
        $this->site_title       = "求购详情";
        $this->site_keywords    = "求购详情";
        $this->site_description = "求购详情";

        $this->display();
    }
    
    /**
     * 收藏
     * @return [type] [description]
     */
    public function favorite_add() {
        $nid = I("nid");
        $title = I("title");
        $data['aid'] = $nid;
        $data['uid'] = $this->user_id;
        $data['favorite_category'] = 4;
        $favorite = M("UserFavorite")->where($data)->find();
        if($favorite == ""){
            $data['title'] = $title;
            $data['addtime'] = time();
            $favorite = M("UserFavorite")->add($data);
            $return['errno']        = 0;
            $return['error']        = "收藏成功";
            $this->ajaxReturn($return);
        }else{
            $return['errno']        = 1;
            $return['error']        = "已收藏";
            $this->ajaxReturn($return);
        }
    }
    /**
     * 删除收藏
     * @return [type] [description]
     */
    public function favorite_del() {
        $nid = I("nid");
        $data['aid'] = $nid;
        $data['uid'] = $this->user_id;
        $data['favorite_category'] = 4;
        $favorite = M("UserFavorite")->where($data)->delete();
        $return['errno']        = 0;
        $return['error']        = "删除成功";
        $this->ajaxReturn($return);
    }
    /**
     * 取消多个收藏
     * @return [type] [description]
     */
    public function favorite_delall() {
        if (IS_POST){
            $ids        = $_POST['ids'];
            $classify       = $_POST['classify'];
            $condition['id'] = array('in',$ids);
            $condition['favorite_category'] = $classify;
            $tem = M("UserFavorite")->where($condition)->delete();
            if($tem != false){
                $return['errno']        = 0;
                $return['error']        = "删除成功";
                $this->ajaxReturn($return);
            }else{
                $return['errno']        = 1;
                $return['error']        = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }
    
    /**
     * 附近页面
     * @return [type] [description]
     */
    public function near() {
        $city_id=cookie('cityid');
        if($city_id){
            $prefix=C("DB_PREFIX");
            $l_table=$prefix."company_link";
            $r_table=$prefix."company";
            $map['l.city_id|l.countyN']=array('eq',$city_id);
            $map['r.status']=array('eq',1);
            if(IS_GET){
                $title = I('title');
                if(!empty($title)){
                    $map['r.name'] = array('like','%'.$title.'%');
                    $this->assign('title',$title);
                }
            }
            $list=M()->table($l_table." l")
                    ->join($r_table." r on l.company_id=r.id","left")
                    ->field("r.name,r.business,r.logo,r.id")
                    ->where($map)
                    ->select();
            $this->assign('list',$list);
        }

        // $location_detail=cookie('location_detail');
        // if($location_detail){
        //  $prefix=C("DB_PREFIX");
        //  $l_table=$prefix."company_link";
        //  $r_table=$prefix."company";
        //  /*附近经纬度*/
        //  $arounds=$this->getAround($location_detail[1],$location_detail[0]);
            
        //  $map['l.lat']=array("between",array($arounds['minLat'],$arounds['maxLat']));
        //  $map['l.lng']=array("between",array($arounds['minLng'],$arounds['maxLng']));
        //  $list=M()->table($l_table." l")
        //           ->join($r_table." r on l.company_id=r.id","left")
        //           ->field("l.lng,l.lat,r.name,r.business,r.logo,r.id")
        //           ->where($map)
        //           ->select();
            
        //  /*经纬度转换米排序只保留9条数据*/
        //   $keyval = array();
        //   foreach ($list as $k=>$v){
        //      $meters = $this->getMeter($location_detail[1], $location_detail[0], $v['lat'], $v['lng']);
        //      $list[$k]['sortmeter']=$meters;
        //      if($meters>1000){
        //          $list[$k]['meters']=sprintf("%.2f",$meters/1000).'km';
        //      }else{
        //          $list[$k]['meters']=$meters.'m';
        //      }
        //      $keyval[]=$meters;
                
        //   }  
        //  array_multisort($keyval,SORT_ASC,$list);
        //  $this->assign('list',$list);
        //  $this->assign();
        // }
        /* 页面基本设置 */
        $this->site_title       = "周边";
        $this->site_keywords    = "周边";
        $this->site_description = "周边";

        $this->display();
    }

    /**
     *通讯录页面
     * @return [type] [description]
     */
    public function contacts(){
        $length = I('page',15);

        $count = M('member')->count();

        $offset = I('num',0);
        $map = array();
        $keywords = I('title','');

        if($keywords){
            $map['name|nickname|mphone'] = array('like','%'.trim($keywords).'%'); 
        }

        $list = M('member')->where($map)->limit($offset,$length)->field('uid,industry,name,nickname,mphone,head_pic')->select();

        $this->user_id&&$industry = $user_auth['user_auth'] = 22;
        foreach ($list as $key => $vo) {
            //需要申请才能查看
            $list[$key]['can_view'] = 0;
            if($industry){
                
                if($industry==$vo['industry']){
                    //相同分类不用申请即可查看
                    $list[$key]['can_view'] = 2;
                }else{
                    //不同分类，则根据会员关系判断
                    $where['ma'] = $this->user_id;
                    $where['mb'] = $vo['uid'];
                    // $where = "(ma=".UID." and mb=".$vo['uid'] .") or (ma=".$vo['uid']." and mb=".UID." and type=2)";
                    if($view = M('view_apply')->where($where)->find()){
                        if($view['is_deal']==1){
                            //已经处理过
                            if($view['result']==1){
                                //通过查看申请
                                $list[$key]['can_view'] = 2;
                            }else{
                                //对方拒绝查看申请
                                $list[$key]['can_view'] = -1;
                            }
                        }else{
                            //未处理，待对方处理
                            $list[$key]['can_view'] = 1;
                        }
                    }
                }
            }

            foreach ($vo as $k => $value) {
                !$value&&$list[$key][$k] = '';
            }
        }


        if(IS_AJAX){
            $this->ajaxreturn($list);
        }else{
            
            $this->assign('list',$list);
            /* 页面基本设置 */
            $this->site_title       = "通讯录";
            $this->site_keywords    = "通讯录";
            $this->site_description = "通讯录";
            $this->display();
        }

    }


    /**
     *AJAX申请查看通讯录信息
     *@return [pool] [description]
     * @param $ma   申请人UID
     * @param $mb   被查看人UID
     */
    public function ajax_apply_view(){
        $ma = I('ma');
        $mb = I('mb');
        if($ma&&$mb){
            $data['ma'] = $ma;
            $data['mb'] = $mb;
            $data['create_time'] = time();
            if(M('view_apply')->add($data)){
                //申请成功
                $status = 1;$msg = '申请成功，等待对方处理！';
            }else{
                //申请失败
                $status = 0;$msg = '申请失败，请稍后重试！';
            }
        }else{
            $status = -1;$msg = '非法操作！';
        }
        $this->ajaxreturn(array('code'=>$status,'msg'=>$msg));
    }
    
    /**
     * 获取位置PHP计算用
     * 根据经纬度和半径计算出经纬度的范围
     * @param $latitude     纬度
     * @param $longitude    经度
     * @param $raidusMile   范围 米
     */
    private function getAround($latitude,$longitude,$raidusMile =10000){
         
        $PI         = 3.14159265;
        $degree     = (24901*1609)/360.0;
        $dpmLat     = 1/$degree;
        $radiusLat  = $dpmLat * $raidusMile;
        $minLat     = $latitude - $radiusLat;
        $maxLat     = $latitude + $radiusLat;
        $mpdLng     = $degree*cos($latitude * ($PI/180));
        $dpmLng     = 1 / $mpdLng;
        $radiusLng  = $dpmLng*$raidusMile;
        $minLng     = $longitude - $radiusLng;
        $maxLng     = $longitude + $radiusLng;
        return array('minLat'=>$minLat,'maxLat'=>$maxLat,'minLng'=>$minLng,'maxLng'=>$maxLng);
    }
    
    /**
     * 获取位置PHP计算用
     * 根据经纬度计算实际距离 米
     * @param unknown_type $lat1 纬度1
     * @param unknown_type $lng1 经度1
     * @param unknown_type $lat2 纬度2
     * @param unknown_type $lng2 经度2
     */
    protected function getMeter($lat1,$lng1,$lat2,$lng2){
         
        $PI         = pi();
        $earthR     = 6378137;
        $radlat1    = $lat1*($PI/180);
        $radlng1    = $lng1*($PI/180);
        $radlat2    = $lat2*($PI/180);
        $radlng2    = $lng2*($PI/180);
        $a          = $radlat1-$radlat2;
        $b          = $radlng1-$radlng2;
        $s          = 2*asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s          = round($s*$earthR);
        return $s;
    }

    /**
     * json省市区，反给js
     */
    public function regionsJson(){
        $list['regions'] = M('Regions')->field('id code,parent,name')->cache(true)->select();
        foreach ($list['regions'] as $key => $val) {
            $val['parent'] === NULL && $list['regions'][$key]['parent'] = 0;
            $list['regions'][$key]['sub'] = array();
        }
        $list['regions'] = list_to_tree($list['regions'], $pk = 'code', $pid = 'parent', $child = 'sub', $root = 0);
        $this->ajaxReturn($list['regions']);
    }



}