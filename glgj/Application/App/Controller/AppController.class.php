<?php
namespace App\Controller;

use Think\Controller;

class AppController extends Controller {
    /**
     * 在此申明，客户端一律传token
     *
     * APP控制器初始化
     * @author 张铁柱 605959252@qq.com
     * APPSIGN 客户端1   网页0
     * UID 用户uid   0=》登录信息有误
     *
     */
    protected function _initialize() {
        /*网页和客户端用户*/
        define(APPSIGN, I('request.appsign', 0, 'intval') ? 1 : 0);

        if (APPSIGN == 1) {
            $user_info = M('Token')
                ->alias('l')
                ->join(C('DB_PREFIX') . 'member r ON l.id = r.token_id', 'LEFT')
                ->field('l.uuid,r.*')
                ->where(array('l.uuid' => I('request.uuid', '', 'strval')))
                ->find();
            define(UID, $user_info['uid'] ? $user_info['uid'] : 0);
        } else {
            $user_auth = session('user_auth');
            $this->user_id = $user_auth['user_id'];
            define(UID, $user_auth['user_id'] ? $user_auth['user_id'] : 0);
        }
    }

    /**
     * 通用分页列表数据集获取方法
     *
     *  可以通过url参数传递where条件,例如:  index.html?name=asdfasdfasdfddds
     *  可以通过url空值排序字段和方式,例如: index.html?_field=id&_order=asc
     *  可以通过url参数r指定每页数据条数,例如: index.html?r=5
     *
     * @param sting|Model $model 模型名或模型实例
     * @param array $where where查询条件(优先级: $where>$_REQUEST>模型设定)
     * @param array|string $order 排序条件,传入null时使用sql默认排序或模型属性(优先级最高);
     *                              请求参数中如果指定了_order和_field则据此排序(优先级第二);
     *                              否则使用$order参数(如果$order参数,且模型也没有设定过order,则取主键降序);
     *
     * @param boolean $field 单表模型用不到该参数,要用在多表join时为field()方法指定参数
     * @author 122837594@qq.com
     *
     * @return array|false
     * 返回数据集
     */
    protected function lists($model, $where = array(), $order = '', $field = true) {
        $options = array();
        $REQUEST = (array)I('request.');
        if (is_string($model)) {
            $model = M($model);
        }

        $OPT = new \ReflectionProperty($model, 'options');
        $OPT->setAccessible(true);

        $pk = $model->getPk();
        if ($order === null) {
            //order置空
        } else if (isset($REQUEST['_order']) && isset($REQUEST['_field']) && in_array(strtolower($REQUEST['_order']), array('desc', 'asc'))) {
            $options['order'] = '`' . $REQUEST['_field'] . '` ' . $REQUEST['_order'];
        } elseif ($order === '' && empty($options['order']) && !empty($pk)) {
            $options['order'] = $pk . ' desc';
        } elseif ($order) {
            $options['order'] = $order;
        }
        unset($REQUEST['_order'], $REQUEST['_field']);

        if (empty($where)) {
            $where = array('status' => array('egt', 0));
        }
        if (!empty($where)) {
            $options['where'] = $where;
        }
        $options = array_merge((array)$OPT->getValue($model), $options);
        $total = $model->where($options['where'])->count();

        if (isset($REQUEST['r'])) {
            $listRows = (int)$REQUEST['r'];
        } else {
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $page = new \Think\Page($total, $listRows, $REQUEST);
        if ($total > $listRows) {
            $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p = $page->show();
        $this->assign('_page', $p ? $p : '');
        $this->assign('_total', $total);
        $options['limit'] = $page->firstRow . ',' . $page->listRows;

        $model->setProperty('options', $options);

        return $model->field($field)->select();
    }

    /**
     * MYSQL多表连接
     * @param $l_table  左表
     * @param $r_table  右表
     * @param $l_id
     * @param $r_id
     * @param $where
     * @param $field
     */
    protected function joinTable($l_table, $r_table, $l_id, $r_id, $where = array(), $field = false, $join = 'INNER', $limit = '*', $order = '') {
        $prefix = C('DB_PREFIX');
        $l_table = $prefix . $l_table;
        $r_table = $prefix . $r_table;
        $res = M()->table($l_table . ' l')
            ->join($r_table . ' r ON l.' . $l_id . '= r.' . $r_id, $join)
            ->where($where)
            ->limit($limit)
            ->field($field)
            ->order($order)
            ->select();
        return $res;
    }



    /**
     * 省市区
     */
    protected function regions() {
        $list = M('Regions')->cache(true)->getField('id,name', true);
        return $list;
    }

//    /**
//     * 省市区
//     */
//    public function regions() {
//        if (IS_GET) {
//            $list['regions'] = M('Regions')->field('id regions_id,parent,name')->cache(true)->select();
//            foreach ($list['regions'] as $key => $val) {
//                $val['parent'] === NULL && $list['regions'][$key]['parent'] = 0;
//                $list['regions'][$key]['child'] = array();
//            }
//            $list['regions'] = list_to_tree($list['regions'], $pk = 'regions_id', $pid = 'parent', $child = 'child', $root = 0);
//
//            $this->returnJson($list);
//        }
//        $this->ajaxJson('70002');
//    }

    /* 车辆类型 */
    protected $truck_type = array('平板车', '高栏车', '集装车', '厢式车', '半封闭', '单桥车', '双桥车', '冷藏车', '轿车运输车', '特种车', '大件车', '危险品车', '封闭车', '半挂车', '商品运输车', '挂车', '爬梯车', '可拼车', '低栏车', '半挂一拖二', '半挂一拖三', '半挂二拖二', '半挂二拖三', '前四后四', '前四后六', '前四后八', '前四后十', '五轮车', '后八轮', '罐式车', '自卸车', '棉被车', '高低高板', '高低平板', '超低板', '金杯车', '敞篷车', '笼子车', '起重车', '面包车', '仓栏车', '翻斗车', '中栏车', '保温车', '其他');

    /* 车辆长度 */
    protected $truck_length = array('21.0m', '20.8m', '20.6m', '20.5m', '20m', '19.5m', '19m', '18.5m', '18.0m', '17.8m', '17.5m', '17.0m', '16.5m', '16.0m', '15.5m', '15.0m', '14.5m', '14m', '13.5m', '13.0m', '12.5m', '12m', '11m', '10.5m', '10m', '9.8m', '9.6m', '9.2m', '9m', '8.8m', '8.7m', '8.6m', '8.5m', '8.2m', '8.0m', '7.8m', '7.7m', '7.6m', '7.5m', '7.4m', '7.2m', '7m', '6.8m', '6.5m', '6.3m', '6.2m', '6m', '5.8m', '5.7m', '5.3m', '5m', '4.8m', '4.5m', '4.3m', '4.2m', '4m', '3.8m');

    /* 货物类型 */
    protected $goods_type = array('重货', '轻货');

//    /* 运输类型 */
//    protected $transport = array('物流公司', '整车配货', '零担配货');

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

    /*重货单位*/
    protected $heavy_unit = array('吨', '公斤');
    /*轻货单位*/
    protected $light_unit = array('方');

    /*仓储,类型*/
    protected $depotCategory = array('1' => '厂房', '2' => '仓库', '3' => '车位', '4' => '土地', '5' => '其他', '6' => '标准库', '7' => '恒温库', '8' => '冷库', '9' => '保税库', '10' => '危险品', '12' => '电商库');
    /*仓储，库内地面*/
    protected $depotGround = array('防尘', '高标水泥', '地砖', '环氧', '防潮', '防静电', '金刚砂', '其他');
    /*仓储，主体结构*/
    protected $depotStructure = array('钢混结构', '彩钢结构', '砖混结构', '其他');
    /*仓储，建筑标准*/
    protected $depotStandard = array('钢混结构', '彩钢结构', '砖混结构', '其他');

    /*货物管理*/
    /*货物运输方式*/
    protected $goodsTransport = array('1' => '物流公司', '2' => '整车配货', '3' => '零担配货');
    /*货物类型*/
    protected $goodsType = array('1' => '货', '2' => '重货', '3' => '轻货');
    /*货物名称*/
    protected $goodsName = array('1' => '设备', '2' => '煤炭', '3' => '矿产', '4' => '钢材', '5' => '饲料', '6' => '建材', '7' => '木材', '8' => '粮食', '9' => '食品', '10' => '饮料', '11' => '蔬菜', '12' => '电器', '13' => '化工产品', '14' => '畜产品');

    /*运费意向单位*/
    protected $freight = array('元/车', '元/吨', '元/批', '价格面议');
}