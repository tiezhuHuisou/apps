<?php
namespace App\Controller;

use Think\Controller;

class MemberController extends AppController {


    /* 车辆类型 */
    protected $truck_type = array('平板车', '高栏车', '集装车', '厢式车', '半封闭', '单桥车', '双桥车', '冷藏车', '轿车运输车', '特种车', '大件车', '危险品车', '封闭车', '半挂车', '商品运输车', '挂车', '爬梯车', '可拼车', '低栏车', '半挂一拖二', '半挂一拖三', '半挂二拖二', '半挂二拖三', '前四后四', '前四后六', '前四后八', '前四后十', '五轮车', '后八轮', '罐式车', '自卸车', '棉被车', '高低高板', '高低平板', '超低板', '金杯车', '敞篷车', '笼子车', '起重车', '面包车', '仓栏车', '翻斗车', '中栏车', '保温车', '其他');

    /* 车辆长度 */
    protected $truck_length = array('21.0m', '20.8m', '20.6m', '20.5m', '20m', '19.5m', '19m', '18.5m', '18.0m', '17.8m', '17.5m', '17.0m', '16.5m', '16.0m', '15.5m', '15.0m', '14.5m', '14m', '13.5m', '13.0m', '12.5m', '12m', '11m', '10.5m', '10m', '9.8m', '9.6m', '9.2m', '9m', '8.8m', '8.7m', '8.6m', '8.5m', '8.2m', '8.0m', '7.8m', '7.7m', '7.6m', '7.5m', '7.4m', '7.2m', '7m', '6.8m', '6.5m', '6.3m', '6.2m', '6m', '5.8m', '5.7m', '5.3m', '5m', '4.8m', '4.5m', '4.3m', '4.2m', '4m', '3.8m');

    /* 货物类型 */
    protected $goods_type = array('重货', '轻货');

    /* 运输类型 */
    protected $transport = array('物流公司', '整车配货', '零担配货');

    /*车源类型*/
    protected $source_type = array('单程', '返程', '专线', '长途', '倒短', '配送');


    /*车辆数量*/
    protected function truck_num() {
        $k = '';
        $array = array();
        for ($k = 1; $k < 71; $k++) {
            $array[] = $k;
        }
        return $array;
    }


    /**
     * 页面基本设置
     * @return [type] [description]
     */
    public function _initialize() {
        parent::_initialize();
        /* 判断登陆状态 */
        if (!UID) {
            session('user_auth', null);
            redirect('?g=app&m=login');
        }
        /* 查询公司信息 */
//        $company = M('Company')->where(array('user_id'=>$this->user_id))->find();
//        $this->company_info = $company;
        $this->assign('site', 'member');
    }

    /**
     * 我的（个人中心）页面
     * @return [type] [description]
     */
    public function index() {
        $list = array();

        //用户信息
        $list['memberInfo'] = M('Member')->where(array('uid' => UID))->find();

        /*客服电话*/
        $list['companphone'] = M('Conf')->where(array('name' => 'companphone'))->getField('value');
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "个人中心";
        $this->site_keywords = "个人中心";
        $this->site_description = "个人中心";

        $this->display();
    }

    /**
     * 我发布的车源列表
     */
    public function truck() {
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Truck');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,heavy,heavy_unit,light,light_unit,truck_type')->order('id desc')->limit(10)->select();
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['start'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']];
            $val['end'] = $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
        }

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "我的车源";
        $this->site_keywords = "我的车源";
        $this->site_description = "我的车源";
        $this->display();
    }

    /**
     * 我的车源列表下拉加载
     * truckAjax
     */
    public function truckAjax() {
        $page = I('get.page', '1', 'intval');
        $page = $page * 10;
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Truck');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,heavy,heavy_unit,light,light_unit,truck_type')->order('id desc')->limit($page, 10)->select();

        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['start'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']];
            $val['end'] = $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];

            $val['heavy'] = $val['heavy'] ? $val['heavy'] : '';
            $val['heavy_unit'] = $val['heavy_unit'] ? $val['heavy_unit'] : '';
            $val['light'] = $val['light'] ? $val['light'] : '';
            $val['light_unit'] = $val['light_unit'] ? $val['light_unit'] : '';
            $val['truck_type'] = $val['truck_type'] ? $val['truck_type'] : '';

            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);


        }
        $this->ajaxReturn($list);
    }

    /**
     * 我的车源删除，展示列表页
     */
    public function editor_truck() {
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Truck');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,heavy,heavy_unit,light,light_unit,truck_type')->order('id desc')->limit(10)->select();
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['start'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']];
            $val['end'] = $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
        }

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "删除我的车源";
        $this->site_keywords = "删除我的车源";
        $this->site_description = "删除我的车源";
        $this->display();
    }

    /**
     * 发布车源
     */
    public function publishTruck() {
        $list = array();
        $model = array();
        $status = array();
        $where = array();
        $regionsCache = array();    //省市区
        $model = D('Truck');
        if (IS_POST) {
            $status = $model->update();
            if ($status) {
                if ($_POST['id']) {
                    $this->success('修改成功');
                } else {
                    $this->success('发布成功');
                }
            } else {
                $this->error($model->getError());
            }
        }
        if (IS_GET) {
            $where['uid'] = UID;
            $where['id'] = $_GET['id'];
            //车源详情
            $list['info'] = $model->getOneInfo($where);

            /*省市区显示*/
            $regionsCache = $this->regions();
            $list['info']['provincename'] = $regionsCache[$list['info']['provincen']];
            $list['info']['cityname'] = $regionsCache[$list['info']['cityn']];
            $list['info']['provincename2'] = $regionsCache[$list['info']['provincen2']];
            $list['info']['cityname2'] = $regionsCache[$list['info']['cityn2']];

            //车源类型
            $list['source_type'] = $this->source_type;
            //车辆类型
            $list['truck_type'] = $this->truck_type;
            //车辆数量
            $list['truck_num'] = $this->truck_num();
            //车辆长度
            $list['truck_length'] = $this->truck_length;
//            print_r($list);
            $this->assign('list', $list);
        }

        /* 页面基本设置 */
        $this->site_title = "发布车源";
        $this->site_keywords = "发布车源";
        $this->site_description = "发布车源";
        $this->display();
    }

    /**
     * 我收藏的货车资源
     */
    /**
     * 我收藏的货车资源下拉加载
     */

    /**
     * 我发布的仓储列表
     */
    public function depot() {
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Depot');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,province_id,city_id,country_id,address,area,standard')->order('id desc')->limit(10)->select();

        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['province_id']] . $regions[$val['city_id']] . $regions[$val['country_id']];
            unset($val['province_id']);
            unset($val['city_id']);
            unset($val['country_id']);
        }
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "我的仓储";
        $this->site_keywords = "我的仓储";
        $this->site_description = "我的仓储";
        $this->display();
    }

    /**
     * 我的仓储删除，展示列表页
     */
    public function editor_depot() {
        $model = array();
        $list = array();
        $regions = array();
        $model = M('Depot');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,province_id,city_id,country_id,address,area,standard')->order('id desc')->limit(10)->select();
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['province_id']] . $regions[$val['city_id']] . $regions[$val['country_id']];
            unset($val['province_id']);
            unset($val['city_id']);
            unset($val['country_id']);
        }
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = "删除我的仓储";
        $this->site_keywords = "删除我的仓储";
        $this->site_description = "删除我的仓储";
        $this->display();
    }

    /**
     * 我发布的仓储列表下拉加载
     */
    public function depotAjax() {
        $page = I('get.page', '1', 'intval');
        $page = $page * 10;
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Depot');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,province_id,city_id,country_id,address,area,standard')->order('id desc')->limit($page, 10)->select();

        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['province_id']] . $regions[$val['city_id']] . $regions[$val['country_id']];
            unset($val['province_id']);
            unset($val['city_id']);
            unset($val['country_id']);
        }
        $this->ajaxReturn($list);
    }

    /**
     * 我收藏的仓储资源
     */
    /**
     * 我收藏的仓储资源下拉加载
     */
    /**
     * 发布我的仓储
     */
    public function publishDepot() {
        $list = array();
        $model = array();
        $status = array();
        $where = array();
        $regionsCache = array();    //省市区

        $model = D('Depot');
        if (IS_POST) {
            $status = $model->update();
            if ($status) {
                if ($_POST['id']) {
                    $this->success('修改成功');
                } else {
                    $this->success('发布成功');
                }
            } else {
                $this->error($model->getError());
            }
        }
        if (IS_GET) {
            $where['uid'] = UID;
            $where['id'] = $_GET['id'];
            //车源详情
            $list['unordered'] = $model->getOneInfo($where);
            /*省市区显示*/
            $regionsCache = $this->regions();
            $list['unordered']['provincename'] = $regionsCache[$list['unordered']['province_id']];
            $list['unordered']['cityname'] = $regionsCache[$list['unordered']['city_id']];

//            $list['info']['provincename2'] = $regionsCache[$list['info']['provincen2']];
//            $list['info']['cityname2'] = $regionsCache[$list['info']['cityn2']];

            /*仓储,类型*/
            $list['depotCategory'] = $this->depotCategory;

            /*仓储，库内地面*/
            $list['depotGround'] = $this->depotGround;
            /*仓储，主体结构*/
            $list['depotStructure'] = $this->depotStructure;
            /*仓储，建筑标准*/
            $list['depotStandard'] = $this->depotStandard;
            $this->assign('list', $list);
        }

        /* 页面基本设置 */
        $this->site_title = "发布仓储";
        $this->site_keywords = "发布仓储";
        $this->site_description = "发布仓储";
        $this->display();
    }

    /**
     * 我发布的货物列表
     */
    public function goods() {
        $model = array();
        $list = array();
        $regions = array();

        $model = M('Goods');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,category_id,transport_id,name')->order('id desc')->limit(10)->select();
        /*运输类型*/
        $goodsTransport = $this->goodsTransport;
        /*货物类型*/
        $goodsType = $this->goodsType;
        /*货物名称*/
        $goodsName = $this->goodsName;
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']] . ' -- ' . $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
            $val['category_id'] = $goodsType[$val['category_id']];
            $val['transport_id'] = $goodsTransport[$val['transport_id']];
        }
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "我的仓储";
        $this->site_keywords = "我的仓储";
        $this->site_description = "我的仓储";
        $this->display();
    }

    /*
     * 删除我的我发布的货物列表
     */
    public function editor_goods() {
        $model = array();
        $list = array();
        $regions = array();
        $model = M('Goods');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,category_id,transport_id,name')->order('id desc')->limit(10)->select();
        /*运输类型*/
        $goodsTransport = $this->goodsTransport;
        /*货物类型*/
        $goodsType = $this->goodsType;
        /*货物名称*/
        $goodsName = $this->goodsName;
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']] . ' -- ' . $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
            $val['category_id'] = $goodsType[$val['category_id']];
            $val['transport_id'] = $goodsTransport[$val['transport_id']];
        }
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = "删除我的货物";
        $this->site_keywords = "删除我的货物";
        $this->site_description = "删除我的货物";
        $this->display();
    }

    /**
     * 我发布的货物列表下拉加载
     */
    public function goodsAjax() {
        $page = I('get.page', '1', 'intval');
        $page = $page * 10;
        $model = array();
        $list = array();
        $regions = array();
        $model = M('Goods');
        $list = $model->where(array('uid' => UID, 'status' => '1'))->field('id,provinceN,cityN,countryN,provinceN2,cityN2,countryN2,category_id,transport_id,name')->order('id desc')->limit($page, 10)->select();
        /*运输类型*/
        $goodsTransport = $this->goodsTransport;
        /*货物类型*/
        $goodsType = $this->goodsType;
        /*货物名称*/
        $goodsName = $this->goodsName;
        $regions = $this->regions();
        foreach ($list as &$val) {
            $val['location'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']] . ' -- ' . $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
            $val['category_id'] = $goodsType[$val['category_id']];
            $val['transport_id'] = $goodsTransport[$val['transport_id']];
        }
        $this->ajaxReturn($list);
    }

    /**
     * 我收藏的货物资源
     */
    /**
     * 我收藏的货物资源下拉加载
     */
    /**
     * 发布我的货物
     */
    public function publishGoods() {
        $list = array();
        $model = array();
        $status = array();
        $where = array();
        $regionsCache = array();    //省市区
        $model = D('Goods');
        if (IS_POST) {
            $status = $model->update();
            if ($status) {
                if ($_POST['id']) {
                    $this->success('修改成功');
                } else {
                    $this->success('发布成功');
                }
            } else {
                $this->error($model->getError());
            }
        }
        if (IS_GET) {
            $where['uid'] = UID;
            $where['id'] = $_GET['id'];
            //车源详情
            $list['unordered'] = $model->getOneInfo($where);

            /*省市区显示*/
            $regionsCache = $this->regions();
            $list['unordered']['provincename'] = $regionsCache[$list['unordered']['provincen']];
            $list['unordered']['cityname'] = $regionsCache[$list['unordered']['cityn']];
            $list['unordered']['provincename2'] = $regionsCache[$list['unordered']['provincen2']];
            $list['unordered']['cityname2'] = $regionsCache[$list['unordered']['cityn2']];

            //车源类型
            $list['source_type'] = $this->source_type;
            //车辆类型
            $list['truck_type'] = $this->truck_type;
            //车辆数量
            $list['truck_num'] = $this->truck_num();
            //车辆长度
            $list['truck_length'] = $this->truck_length;
            /*运输类型*/
            $list['goodsTransport'] = $this->goodsTransport;
            /*货物类型*/
            $list['goodsType'] = $this->goodsType;
            /*货物名称*/
            $goodsName = $this->goodsName;
//            print_r($list);
            $this->assign('list', $list);
        }

        /* 页面基本设置 */
        $this->site_title = "发布货源";
        $this->site_keywords = "发布货源";
        $this->site_description = "发布货源";
        $this->display();
    }

    /**
     * 删除我的车源
     * type = 1 删除我发布的。2删除我收藏的
     * mold = 1车源2货源3仓储
     */
    public function delInfo() {
        $parameter = array();     // 参数
        $status = '';       //状态判断
        $where = array();    //查询条件
        $datas = array();   //保存的字段
        $model = array();   //数据库操作使用
        $parameter = I('get.', '', 'strval');
        $where['uid'] = array('eq', UID);
        $where['id'] = array('in', $parameter['ids']);
        $datas['status'] = 0;
        if (empty($parameter)) {
            $this->error('参数错误');
        }
        /*删除我发布的*/
        if ($parameter['type'] == 1) {
            switch ($parameter['mold']) {
                case 1:
                    $model = M('Truck');
                    break;
                case 2:
                    $model = M('Goods');
                    break;
                case 3:
                    $model = M('Depot');
                    break;
                default:
                    $this->error('参数错误');
            }
            $status = $model->where($where)->setField('status', '0');

            if ($status) {
                $this->success('删除成功');
            } else {
                $this->error('参数错误');
            }

        }
        /*删除我收藏的*/
        if ($parameter['type'] == 2) {

            $this->success('删除成功');
        }

    }

    /**
     * 通用收藏接口
     * id 收藏id
     * type 1->资讯收藏,2->产品收藏,3->企业收藏,4->物流收藏,5->货车收藏,6->货物收藏,7->仓储收藏
     */
    public function collect() {
        $get = '';  //获得参数
        $model = array();    //操作数据库
        $datas = array();      //查询和插入数据
        $collectInfo = array(); //收藏信息
        $model = M('UserFavorite');
        $get = I('get.', '', 'strval');
        if (!UID) {
            $this->error('请先登录');
        }
        $datas['type'] = $get['type'];
        $datas['uid'] = UID;
        $datas['aid'] = $get['id'];
        $collectInfo = $model->where($datas)->find();
        if ($collectInfo) {
            $model->where($datas)->setField('status', 1);
            $this->success('收藏成功');
        } else {
            $datas['addtime'] = time();
            $collectInfo = $model->add($datas);
            $this->success('收藏成功');
        }
    }


    /**
     * 企业收藏页面
     * @return [type] [description]
     */
    public function company_collect() {
        $data = array();    //查询条件
        $order = array();    //排序
        $field = array();    //筛选字段
        $list = array();    //返回数据

        $data['l.uid'] = UID;
        $data['l.type'] = 3;
        $order = "l.addtime desc";
        $field = 'l.id,r.name,c.contact_user,c.telephone,c.subphone,c.address';
        $list['ordered'] = M('UserFavorite')->alias('l')
            ->join(C('DB_PREFIX') . 'company r ON l.aid = r.id', LEFT)
            ->join(C('DB_PREFIX') . 'company_link c ON l.aid = c.company_id', LEFT)
            ->where($data)
            ->order($order)
            ->field($field)
            ->select();

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "企业收藏";
        $this->site_keywords = "企业收藏";
        $this->site_description = "企业收藏";

        $this->display();
    }

    /**
     * 资讯收藏页面
     * @return [type] [description]
     */
    public function news_collect() {
        $where['l.uid'] = UID;
        $where['l.type'] = 1;
        $order = "l.addtime desc";
        $field = 'l.id,r.title,r.image,r.addtime';
        $list = M('UserFavorite')->alias('l')
            ->join(C('DB_PREFIX') . 'article r ON l.aid=r.id', INNER)
            ->where($where)
            ->order($order)
            ->field($field)
            ->select();
        foreach ($list['ordered'] as &$val) {
            $val['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
        }
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "资讯收藏";
        $this->site_keywords = "资讯收藏";
        $this->site_description = "资讯收藏";

        $this->display();
    }

    /**
     * 货源收藏
     *
     */
    public function truckList() {
        $where['l.uid'] = UID;
        $where['l.type'] = 6;
        $order = "l.addtime desc";
        $field = 'l.id,r.provinceN,r.cityN,r.countryN,r.provinceN2,r.cityN2,r.countryN2,r.heavy,r.heavy_unit,r.light,r.light_unit,r.truck_type,r.truck_no,r.source_type,r.image,r.name';
        $list['ordered'] = M('UserFavorite')->alias('l')
            ->join(C('DB_PREFIX') . 'truck r ON l.aid=r.id', INNER)
            ->where($where)
            ->order($order)
            ->field($field)
            ->select();
        $regions = $this->regions();
        foreach ($list['ordered'] as &$val) {
            $val['start'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']];
            $val['end'] = $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
        }

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "货源收藏";
        $this->site_keywords = "货源收藏";
        $this->site_description = "货源收藏";

        $this->display();
    }


    public function goodsList() {
        $where['l.uid'] = UID;
        $where['l.type'] = 5;
        $order = "l.addtime desc";
        //$field = 'l.id,r.provinceN,r.cityN,r.countryN,r.provinceN2,r.cityN2,r.countryN2,r.heavy,r.heavy_unit,r.light,r.light_unit,r.truck_type,r.truck_no,r.source_type';
        $field = 'l.id,r.provinceN,r.cityN,r.countryN,r.provinceN2,r.cityN2,r.countryN2,r.category_id,r.transport_id,r.name';
        $list['ordered'] = M('UserFavorite')->alias('l')
            ->join(C('DB_PREFIX') . 'goods r ON l.aid=r.id', INNER)
            ->where($where)
            ->order($order)
            ->field($field)
            ->select();
        /*运输类型*/
        $goodsTransport = $this->goodsTransport;
        /*货物类型*/
        $goodsType = $this->goodsType;
        /*货物名称*/
        $goodsName = $this->goodsName;
        $regions = $this->regions();
        foreach ($list['ordered'] as &$val) {
            $val['location'] = $regions[$val['provincen']] . $regions[$val['cityn']] . $regions[$val['cityN']] . ' -- ' . $regions[$val['provincen2']] . $regions[$val['cityn2']] . $regions[$val['countryn2']];
            unset($val['provincen']);
            unset($val['cityn']);
            unset($val['countryn']);
            unset($val['provincen2']);
            unset($val['cityn2']);
            unset($val['countryn2']);
            $val['category_id'] = $goodsType[$val['category_id']];
            $val['transport_id'] = $goodsTransport[$val['transport_id']];
        }

        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "车源收藏";
        $this->site_keywords = "车源收藏";
        $this->site_description = "车源收藏";
        $this->display();
    }

    public function depotList() {
        $where['l.uid'] = UID;
        $where['l.type'] = 7;
        $order = "l.addtime desc";
        //$field = 'l.id,r.provinceN,r.cityN,r.countryN,r.provinceN2,r.cityN2,r.countryN2,r.heavy,r.heavy_unit,r.light,r.light_unit,r.truck_type,r.truck_no,r.source_type';
        $field = 'l.id,r.province_id,r.city_id,r.country_id,r.address,r.area,r.standard';
        $list['ordered'] = M('UserFavorite')->alias('l')
            ->join(C('DB_PREFIX') . 'depot r ON l.aid=r.id', INNER)
            ->where($where)
            ->order($order)
            ->field($field)
            ->select();

        $regions = $this->regions();
        foreach ($list['ordered'] as &$val) {
            $val['location'] = $regions[$val['province_id']] . $regions[$val['city_id']] . $regions[$val['country_id']];
            unset($val['province_id']);
            unset($val['city_id']);
            unset($val['country_id']);
        }
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "车源收藏";
        $this->site_keywords = "车源收藏";
        $this->site_description = "车源收藏";
        $this->display();
    }

    /**
     * 站内信息页面
     * @return [type] [description]
     */
    public function message() {
        $where['to_user'] = array(array('eq', UID), array('eq', 0), 'or');
        $where['status'] = 1;
        $order = 'addtime desc';
        $list = M('UserMessage')->where($where)->order($order)->limit(0, 10)->select();
        $this->assign('list', $list);
        /* 页面基本设置 */
        $this->site_title = "站内信息";
        $this->site_keywords = "站内信息";
        $this->site_description = "站内信息";

        $this->display();
    }

    /**
     * ajax站内信息列表
     * @return [type] [description]
     */
    public function ajax_message() {
        $num = I('get.num', 0, 'intval');
        $where['to_user'] = array(array('eq', $this->user_id), array('eq', 0), 'or');
        $where['status'] = 1;
        $order = 'addtime desc';
        $list = M('UserMessage')->where($where)->order($order)->limit($num, 10)->select();
        foreach ($list as $key => $val) {
            $list[$key]['addtime'] = date('Y-m-d H:i', $val['addtime']);
        }
        $this->ajaxReturn($list);
    }

    /**
     * 意见反馈
     * @return [type] [description]
     */
    public function opinion() {
        if(IS_AJAX){
            $data['content'] = I('content');
            if(empty($data['content'])){
                $return['errno'] 		= 1;
                $return['error'] 		= "请输入反馈内容";
                $this->ajaxReturn($return);
                exit;
            }
            $data['phone'] = I('phone');
            if(!preg_match("/1[3458]{1}\d{9}$/",$data['phone'])){
                $return['errno'] 		= 1;
                $return['error'] 		= "请输入正确的手机号码";
                $this->ajaxReturn($return);
                exit;
            }
            $data['addtime'] = time();
            $data['user_id'] = $this->user_id;
            $sug = M('Suggestion')->add($data);
            if($sug){
                $return['errno'] 		= 0;
                $return['error'] 		= "发送成功";
                $this->ajaxReturn($return);
                exit;
            }else{
                $return['errno'] 		= 1;
                $return['error'] 		= "发送失败";
                $this->ajaxReturn($return);
                exit;
            }
        }
        /* 页面基本设置 */
        $this->site_title 		= "意见反馈";
        $this->site_keywords 	= "意见反馈";
        $this->site_description = "意见反馈";

        $this->display();
    }

    /**
     * 身份验证
     */
    public function identity(){

        
        /* 页面基本设置 */
        $this->site_title 		= "身份验证";
        $this->site_keywords 	= "身份验证";
        $this->site_description = "身份验证";
    }

    /*   封印   */
//	/**
//	 * 完善资料
//	 */
//	public function info(){
//	    if(IS_POST){
//	        $status=D('Member')->update();
//	        if($status){
//	            $this->success('更新基本信息成功');
//	        }else{
//	            $this->error('更新基本信息失败');
//	        }
//	    }else{
//	        $map['uid']=array('eq',$this->user_id);
//	        $user_info=M('Member')->where($map)->find();
//	        $this->assign('user_info',$user_info);
//	
//	        /* 页面基本设置 */
//	        $this->site_title 		= "完善资料";
//	        $this->site_keywords 	= "完善资料";
//	        $this->site_description = "完善资料";
//	        $this->display();
//	    }
//	
//	}

//
//	/**
//	 * 产品收藏页面
//	 * @return [type] [description]
//	 */
//	public function product_collect(){
//	    $data['uid'] = $this->user_id;
//	    $data['favorite_category'] = 2;
//	    $order = "addtime desc";
//	    $list = M('UserFavorite')->where($data)->order($order)->select();
//	    $product = M('ProductSale');
//	    foreach ($list as $key=>$val){
//	        $detail = $product->where(array('id'=>$val['aid']))->find();
//	        $list[$key]['title'] = $detail['title'];
//	        $list[$key]['short_title'] = $detail['short_title'];
//	        $list[$key]['price'] = $detail['price'];
//	        $list[$key]['image'] = $detail['img'];
//	        $list[$key]['summary'] = $detail['summary'];
//	    }
//	    $this->assign('list',$list);
//		/* 页面基本设置 */
//        $this->site_title 		= "产品收藏";
//        $this->site_keywords 	= "产品收藏";
//        $this->site_description = "产品收藏";
//
//		$this->display();
//	}
//
//	
//
//	/**
//	 * 收藏页面
//	 * @return [type] [description]
//	 */
//	public function need_collect(){
//	    $where['uid'] = $this->user_id;
//	    $where['favorite_category'] = 4;
//	    $order = "addtime desc";
//	    $list = M('UserFavorite')->where($where)->order($order)->select();
//	    $buy = M('ProductBuy');
//	    $companylink = M('CompanyLink');
//	    $company = M('Company');
//	    foreach ($list as &$val){
//	        $detail = $buy->where(array('id'=>$val['aid']))->find();
//	        $tmp = $companylink->where(array('company_id'=>$detail['company_id']))->field('contact_user')->find();
//	        $tmp1 = $company->where(array('id'=>$detail['company_id']))->field('logo')->find();
//	        $val['title'] = $detail['title'];
//	        $val['short_title'] = $detail['short_title'];
//	        $val['summary'] = $detail['summary'];
//	        $val['buy_name'] = $tmp['contact_user'];
//	        $val['logo'] = $tmp1['logo'];
//	        $val['img'] = $detail['img'];
//	        $val['modify_time'] = mdate($detail['modify_time']);
//	    }
//	    $this->assign('list',$list);
//		/* 页面基本设置 */
//        $this->site_title 		= "求购收藏";
//        $this->site_keywords 	= "求购收藏";
//        $this->site_description = "求购收藏";
//
//		$this->display();
//	}
//

//	
//	/**
//     * 自己发布的服务
//     */
//    public function supply() {
//        /* 查询 */
//        $where['company_id'] = array('eq', $this->user_id);
//        $list = M('ProductSupply')->field('id,title,img,short_title,issue_time')->where($where)->order('modify_time DESC')->select();
//        $this->assign('list', $list);
//
//        /* 页面基本设置 */
//        $this->site_title       = '我发布的供应';
//        $this->site_keywords    = '我发布的供应';
//        $this->site_description = '我发布的供应';
//
//        $this->display();
//    }
//
//    /**
//     * 发布、编辑供应
//     */
//    public function publishSupply() {
//        $id    = I('request.id', 0, 'intval');
//        $opt   = $id > 0 ? '编辑' : '发布';
//        $model = D('ProductSupply');
//        if( IS_POST ) {
//            $result = $model->update();
//            if ( $result ) {
//                $backUrl = '?g=app&m=member&a=supply' . ( $this->utoken ? '&appsign=1&uuid=' . $this->utoken : '' );
//                $this->success($opt.'成功', $backUrl);
//            } else {
//                $this->error($model->getError());
//            }
//        }
//
//        /* 修改 */
//        if ( $id > 0 ) {
//            $condition['id'] = $id;
//            $detail = $model->getOneInfo($condition);
//            $this->assign('detail',$detail);
//        }
//
//        /* 分类 */
//        $where['status']    = array('eq', 1);
//        $category = M('ProductBuyCategory')->field('id,name,parent_id')->where($where)->order('sort DESC,id DESC')->select();
//        $category = lowest($category);
//        $this->assign('category', $category);
//
//        /* 页面基本设置 */
//        $this->site_title       = $opt . '供应信息';
//        $this->site_keywords    = $opt . '供应信息';
//        $this->site_description = $opt . '供应信息';
//
//        $this->display();
//    }
//
//    /**
//     * 删除供应
//     */
//    public function delSupply() {
//        if ( IS_POST ) {
//            $id = I('post.id', 0, 'intval');
//            if ( $id === 0 ) {
//                $this->error('参数错误');
//            }
//            $where['company_id'] = array('eq', $this->user_id);
//            $where['id']         = array('eq', $id);
//            $delete = M('ProductSupply')->where($where)->delete();
//            if ( $delete === false ) {
//                $this->error('删除失败');
//            }
//            $this->success('删除成功');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * 自己发布的求购
//     */
//    public function need() {
//        /* 查询 */
//        $where['company_id'] = array('eq', $this->user_id);
//        $list = M('ProductBuy')->field('id,title,img,short_title,issue_time')->where($where)->order('modify_time DESC')->select();
//        $this->assign('list', $list);
//
//        /* 页面基本设置 */
//        $this->site_title       = '我发布的求购';
//        $this->site_keywords    = '我发布的求购';
//        $this->site_description = '我发布的求购';
//
//        $this->display();
//    }
//
//    /**
//     * 发布、编辑求购
//     */
//    public function publishNeed() {
//        $id    = I('request.id', 0, 'intval');
//        $opt   = $id > 0 ? '编辑' : '发布';
//        $model = D('ProductBuy');
//        if( IS_POST ) {
//            $result = $model->update();
//            if ( $result ) {
//                $backUrl = '?g=app&m=member&a=need' . ( $this->utoken ? '&appsign=1&uuid=' . $this->utoken : '' );
//                $this->success($opt . '成功', $backUrl);
//            } else {
//                $this->error($model->getError());
//            }
//        }
//
//        /* 修改 */
//        if ( $id > 0 ) {
//            $condition['id'] = $id;
//            $detail = $model->getOneInfo($condition);
//            $this->assign('detail',$detail);
//        }
//
//        /* 分类 */
//        $where['status']    = array('eq', 1);
//        $category = M('ProductBuyCategory')->field('id,name,parent_id')->where($where)->order('sort DESC,id DESC')->select();
//        $category = lowest($category);
//        $this->assign('category', $category);
//
//        /* 页面基本设置 */
//        $this->site_title       = $opt . '求购信息';
//        $this->site_keywords    = $opt . '求购信息';
//        $this->site_description = $opt . '求购信息';
//
//        $this->display();
//    }
//
//    /**
//     * 删除求购
//     */
//    public function delNeed() {
//        if ( IS_POST ) {
//            $id = I('post.id', 0, 'intval');
//            if ( $id === 0 ) {
//                $this->error('参数错误');
//            }
//            $where['company_id'] = array('eq', $this->user_id);
//            $where['id']         = array('eq', $id);
//            $delete = M('ProductBuy')->where($where)->delete();
//            if ( $delete === false ) {
//                $this->error('删除失败');
//            }
//            $this->success('删除成功');
//        }
//        $this->error('非法操作');
//    }
//
//    /**
//     * ajaxUpload.js图片上传插件专用
//     */
//    public function publishUpload() {
//        if ( IS_POST ) {
//            $now    = time();
//            $dir    = dirname(__FILE__) . '/../../../Uploads/Home/image/'.date('Ymd',$now).'/';         // 上传路径
//            $fileName = $_FILES['uploadfile']['name'];                                                  // 上传文件名
//            if ( $_FILES['uploadfile']['error'] == 0 ) {                                                // 没有错误信息
//                if ( strrchr( $fileName, '.' ) ) {                                                      // 如果有后缀名
//                    $suffix = strrchr( $fileName, '.' );                                                // 获取".后缀名"
//                } else {                                                                                // 没有后缀名
//                    $suffix = '';                                                                       // 后缀名为空
//                }
//                if ( !is_dir( $dir ) ) {                                                                // 如果上传目录不存在
//                    mkdir( $dir, 0755, true );                                                          // 创建目录
//                    $saveName = md5($now).$suffix;                                                      // 保存后的文件名称
//                    move_uploaded_file( $_FILES['uploadfile']['tmp_name'], $dir.$saveName );            // 把上传的文件移动到上传目录
//                    $result['info'] = 'success';                                                        // 返回成功消息
//                    $result['path'] = C('HTTP_ORIGIN').'/Uploads/Home/image/'.date('Ymd',$now).'/'.$saveName;
//                } else {                                                                                // 如果上传目录存在
//                    if ( is_writable( $dir ) ) {                                                        // 上传目录可写
//                        $saveName = md5($now).$suffix;                                                  // 保存后的文件名称
//                        move_uploaded_file( $_FILES['uploadfile']['tmp_name'], $dir.$saveName );        // 把上传的文件移动到上传目录
//                        $result['info'] = 'success';                                                    // 返回成功消息
//                        $result['path'] = C('HTTP_ORIGIN').'/Uploads/Home/image/'.date('Ymd',$now).'/'.$saveName;
//                    } else {                                                                            // 上传目录不可写
//                        $result['info'] = 'error';                                                      // 返回失败消息
//                    }
//                }
//            } else {                                                                                    // 有错误消息
//                $result['info'] = $_FILES['uploadfile']['error'];                                       // 返回失败消息
//            }
//        } else {
//            $result['info'] = 'error';
//        }
//        $this->ajaxReturn($result);
//    }
//
//	/**
//	 * 官方客服QQ
//	 */
//	public function contact() {
//	    // 官方客服QQ
//	    $conf = M('Conf')->select();
//	    foreach($conf as $val){
//	        $conf_arr[$val['name']]=$val['value'];
//	    }
//		if ( !empty( $conf_arr['linkqq'] ) ) {
//            $linkqq = explode('|', $conf_arr['linkqq']);
//            foreach ($linkqq as $k => $v) {
//				$temp = explode(',', $v);
//				$qqArr[$k]['qq']   = $temp[0];
//				$qqArr[$k]['name'] = $temp[1];
//			}
//            $this->assign('qq', $qqArr);
//        }
//		/* 页面基本设置 */
//        $this->site_title 		= "官方QQ客服";
//        $this->site_keywords 	= "官方QQ客服";
//        $this->site_description = "官方QQ客服";
//
//		$this->display();
//	}
}