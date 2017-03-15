<?php
namespace Apps\Controller;

use Think\Controller;

class ApiController extends Controller {
    /**
     * 架构函数
     */
    protected function _initialize() {
        /* 订单自动确认收货 */
        //$this->orderAutoConfirmReceive();
    }

    /**
     * 缓存省市区
     * 用户后代码查询省市区
     */
    protected function regionsCache() {
        $info = M('Regions')->cache(true)->getField('id,name', true);
        return $info;
    }

    /* 车辆类型 */
    public $truck_type = array('平板车', '高栏车', '集装车', '厢式车', '半封闭', '单桥车', '双桥车', '冷藏车', '轿车运输车', '特种车', '大件车', '危险品车', '封闭车', '半挂车', '商品运输车', '挂车', '爬梯车', '可拼车', '低栏车', '半挂一拖二', '半挂一拖三', '半挂二拖二', '半挂二拖三', '前四后四', '前四后六', '前四后八', '前四后十', '五轮车', '后八轮', '罐式车', '自卸车', '棉被车', '高低高板', '高低平板', '超低板', '金杯车', '敞篷车', '笼子车', '起重车', '面包车', '仓栏车', '翻斗车', '中栏车', '保温车', '其他');

    /* 车辆长度 */
    public $truck_length = array('21.0m', '20.8m', '20.6m', '20.5m', '20m', '19.5m', '19m', '18.5m', '18.0m', '17.8m', '17.5m', '17.0m', '16.5m', '16.0m', '15.5m', '15.0m', '14.5m', '14m', '13.5m', '13.0m', '12.5m', '12m', '11m', '10.5m', '10m', '9.8m', '9.6m', '9.2m', '9m', '8.8m', '8.7m', '8.6m', '8.5m', '8.2m', '8.0m', '7.8m', '7.7m', '7.6m', '7.5m', '7.4m', '7.2m', '7m', '6.8m', '6.5m', '6.3m', '6.2m', '6m', '5.8m', '5.7m', '5.3m', '5m', '4.8m', '4.5m', '4.3m', '4.2m', '4m', '3.8m');

    /* 货物类型 */
    public $goods_type = array('重货', '轻货');

    /* 运输类型 */
    public $transport = array('物流公司', '整车配货', '零担配货');


//    /**
//     *
//     * 订单自动确认收货
//     *
//     * @author 406764368@qq.com 黄东
//     * @version 2017年2月4日 13:38:14
//     *
//     */
//    public function orderAutoConfirmReceive() {
//        $nowTime = time();
//        $orderModel = M('Order');
//        $autoDays = C('AUTO_CONFIRM_RECEIVE_DAYS');
//
//        /* 查询到期的订单 */
//        $where['state'] = array('eq', 2);
//        $where['send_time'] = array('elt', $nowTime - ($autoDays * 86400));
//        $orderList = M('Order')->field('id,uid,company_id,send_time')->where($where)->select();
//
//        /* 更新数据 */
//        if ($orderList) {
//            $orderClogModel = M('OrderClog');
//            $withdrawalsModel = M('Withdrawals');
//            $commissionModel = M('Commission');
//            $companyModel = M('Company');
//
//            foreach ($orderList as $value) {
//                /* 更新订单数据 */
//                $orderModel->where(array('id' => $value['id']))->setField('state', 3);
//
//                /* 生成订单操作记录 */
//                $clogDatas['order_id'] = $value['id'];
//                $clogDatas['action'] = 15;
//                $clogDatas['uid'] = $value['uid'];
//                $clogDatas['remark'] = '系统自动确认收货';
//                $clogDatas['addtime'] = $value['send_time'] + ($autoDays * 86400);
//                $orderClogModel->add($clogDatas);
//
//                /* 更新商家利润数据 */
//                $withdrawalsDatas['status'] = -2;
//                $withdrawalsDatas['etime'] = $nowTime;
//                $withdrawalsModel->where(array('order_id' => $value['id']))->save($withdrawalsDatas);
//
//                /* 扣除商家销售利润（发放佣金） */
//                $withdrawals = $commissionModel->where(array('order_id' => $value['id'], 'status' => 0))->sum('commission');
//                if ($withdrawals) {
//                    $deductCommissionDatas['order_id'] = $value['id'];
//                    $deductCommissionDatas['withdrawals'] = $withdrawals;
//                    $deductCommissionDatas['cname'] = $companyModel->where(array('id' => $value['company_id']))->getField('name');
//                    $deductCommissionDatas['cid'] = $value['company_id'];
//                    $deductCommissionDatas['etime'] = $nowTime;
//                    $deductCommissionDatas['ctime'] = $nowTime;
//                    $deductCommissionDatas['status'] = 4;
//                    M('Withdrawals')->add($deductCommissionDatas);
//
//                    /* 更新佣金数据 */
//                    $commissionModel->where(array('order_id' => $value['id']))->setField('status', 1);
//                }
//            }
//        }
//    }

    /**
     * 使数组里指定下标元素路径变为绝对路径
     * @param  [Mixed]  $list [要处理的数据，只支持非空字符串、一维数组、二维数组，传其他数据全部返回空字符串]
     * @param  [String] $k    [指定下标，默认为空，代表一位数组，指定下标表示二维数组]
     * @return [Array]        [处理后的数组]
     */
    public function getAbsolutePath($list, $k = '', $default = '') {
        /* 要处理的数据为数组 并且至少一个元素 */
        if (is_array($list) && count($list) > 0) {
            if (empty($k)) {
                /* 不指定下标，代表要处理的数据为一位数组 */
                foreach ($list as $key => $value) {
                    if ($value) {
                        if (strpos($value, 'http') === false && strpos($value, 'https') === false) {
                            if (strpos($value, 'Uploads') || strpos($value, 'Public') || strpos($value, 'data')) {
                                if (substr($value, 0, 1) == '.') {
                                    $list[$key] = C('HTTP_ORIGIN') . substr($value, 1, strlen($value) - 1);
                                } else {
                                    $list[$key] = C('HTTP_ORIGIN') . $value;
                                }
                            }
                        }
                    } else {
                        $list[$key] = is_array($list[$key]) ? array() : $default;
                    }
                }
            } else {
                /* 指定数组下标，代表要处理的数据是二维数组 */
                foreach ($list as $key => $value) {
                    if ($value[$k]) {
                        if (strpos($value[$k], 'http') === false && strpos($value[$k], 'https') === false) {
                            if (strpos($value[$k], 'Uploads') || strpos($value[$k], 'Public') || strpos($value[$k], 'data')) {
                                if (substr($value[$k], 0, 1) == '.') {
                                    $list[$key][$k] = C('HTTP_ORIGIN') . substr($value[$k], 1, strlen($value[$k]) - 1);
                                } else {
                                    $list[$key][$k] = C('HTTP_ORIGIN') . $value[$k];
                                }
                            }
                            if (!$list[$key][$k]) {
                                $list[$key][$k] = $default;
                            }
                        }
                    } else {
                        $list[$key][$k] = is_array($list[$key][$k]) ? array() : $default;
                    }
                }
            }
        } elseif (is_string($list) && !empty($list)) {
            if (strpos($list, 'http') === false && strpos($list, 'https') === false) {
                if (strpos($list, 'Uploads') || strpos($list, 'Public') || strpos($list, 'data')) {
                    if (substr($list, 0, 1) == '.') {
                        $list = C('HTTP_ORIGIN') . substr($list, 1, strlen($list) - 1);
                    } else {
                        $list = C('HTTP_ORIGIN') . $list;
                    }
                }
            }
        } else {
            if (is_array($list)) {
                $list = array();
            } else {
                $list = $default;
            }
        }
        return $list;
    }

    /**
     * 输出Json格式化后的数据、返回码和提示信息
     * @param  string $list [返回的数据，默认为空字符串]
     * @param  string $code [返回码，默认为40000，代表请求成功]
     * @param  string $hint [提示信息，默认为空字符串]
     */
    protected function returnJson($list = '', $code = '40000', $hint = '') {
        echo json_encode(array('code' => $code, 'hint' => $hint, 'list' => $list));
        exit;
    }

    /**
     * 输出Json格式化后的返回码和提示信息
     * @param  string $code [返回码，默认为40000，代表请求成功]
     * @param  string $hint [提示信息，默认为空字符串]
     */
    protected function ajaxJson($code = '40000', $hint = '') {
        echo json_encode(array('code' => $code, 'hint' => $hint));
        exit;
    }

    /**
     * 按规则处理时间 unix时间戳转字符串
     * 规则：
     * 1、一分钟之内显示：刚刚
     * 2、一小时之内显示：具体多少分钟前，例：23分钟前
     * 3、一天之内显示：具体多少小时前，例：12小时前
     * 4、超过1天不足2天显示：1天前
     * 5、超过2天不足3天显示：2天前
     * 6、超过三天并且是当前年份显示：月-日，例：04-23
     * 7、超过三天并且不是当前年份显示：年-月-日，例：2015-04-23
     * @param  [Integer] $dateTime          [unix时间戳]
     * @param  [String]  $nowYearformat     [当前年份格式化字符串]
     * @param  [String]  $histroyYearformat [历史年份格式化字符串]
     * @return [String]                     [处理之后的时间]
     */
    protected function dateTimeDeal($dateTime, $nowYearformat = 'm-d', $histroyYearformat = 'Y-m-d') {
        $nowYear = date('Y');
        $dateYear = date('Y', $dateTime);
        $difference = time() - $dateTime;
        if ($difference < 3600) {
            /* 一小时之内显示具体多少分钟前 */
            if ($difference < 60) {
                $result = '刚刚';
            } else {
                $result = floor($difference / 60) . '分钟前';
            }
        } elseif ($difference < 86400) {
            /* 一天之内显示具体多少小时前 */
            $result = floor($difference / 3600) . '小时前';
        } elseif ($difference < 172800) {
            /* 超过1天不足2天显示：1天前 */
            $result = '1天前';
        } elseif ($difference < 259200) {
            /* 超过2天不足3天显示：2天前 */
            $result = '2天前';
        } else {
            /* 超过三天 */
            if ($nowYear == $dateYear) {
                /* 当前年份显示：月-日 */
                $result = date($nowYearformat, $dateTime);
            } else {
                $result = date($histroyYearformat, $dateTime);
            }
        }
        return $result;
    }

    /**
     * 处理数字类数据
     * @param  [Mixed]  $number [数字或数字字符串]
     * @return [String]         [处理后的数字字符串]
     */
    public function dealNumber($number) {
        if (is_numeric($number) && intval($number) > 999) {
            $number = '999+';
        }
        return $number;
    }

    /**
     * 去除字符串所有空格
     * @param  [string] $str [要去除空格的字符串]
     * @return [type]        [去除所有空格后的字符串]
     */
    public function destroySpace($str) {
        if (is_string($str)) {
            $search = array(' ', '　', '&nbsp;');
            $replace = array('', '', ' ');
            $str = str_replace($search, $replace, $str);
        }
        return $str;
    }

    /**
     * 行业圈分享处理
     * @param  array $arr [行业圈数据数组]
     * @param  string $share_model [分享模块]
     * 1->资讯详情
     * 2->产品详情
     * 3->求购详情
     * 4->企业主页
     * 5->企业相册
     * @param  string $data_id [数据id]
     * @return [array]             [处理后添加分享的标题和分享的图片到传过来的行业圈数据数组里，没有分享也会新增2个字段，但均为空]
     */
    protected function dealShareToCircle($arr = array(), $share_model = '', $data_id = '') {
        switch ($share_model) {
            case 'news_detail':
                $model = M('Article');
                $where['is_display'] = array('eq', 1);
                $where['id'] = array('eq', $data_id);
                $field = 'image,title';
                break;
            /*case 'product_detail':
                $model           = M('ProductSale');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                $field           = 'img image,title';
                break;
            case 'need_detail':
                $model           = M('ProductBuy');
                $where['status'] = array('eq', 1);
                $where['id']     = array('eq', $data_id);
                $field           = 'img image,title';
                break;*/
            case 'company_home':
                $model = M('Company');
                $where['status'] = array('eq', 1);
                $where['id'] = array('eq', $data_id);
                $field = 'logo image,name title';
                break;
            default:
                $field = '';
                $arr['share_title'] = '';
                $arr['share_image'] = '';
                $arr['data_id'] = $arr['data_id'] ? $arr['data_id'] : '';
                break;
        }
        if (!empty($field)) {
            $info = $model->field($field)->where($where)->find();
            $arr['share_title'] = $info['title'];
            $arr['share_image'] = $this->getAbsolutePath($info['image']);
        }

        return $arr;
    }

    /**
     * 模拟提交数据函数
     * @param  string $url [请求地址URL]
     * @param  array $data [请求数据数组]
     * @param  integer $header [头信息]
     * @return [type]          [description]
     */
    public function curlPost($url = '', $data = array(), $header = 1) {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        //curl_setopt($curl, CURLOPT_REFERER, "mp.weixin.qq.com");
        //curl_setopt($curl, CURLOPT_COOKIEFILE, $this->cookie);
        if ($data) {
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            $this->ajaxJson('70000', curl_error($curl));
            // echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话

        $tmpInfo = json_decode($tmpInfo, true);
        if ($tmpInfo['code'] != '40000') {
            if ($tmpInfo['code'] = '70000') {
                $this->ajaxJson('70000', $tmpInfo['hint']);
            }
            $this->ajaxJson('70000', '非法操作');
        } else {
            $tmpInfo = $tmpInfo['list'];
        }

        return $tmpInfo; // 返回数据
    }

    /**
     * 解析数据
     * @param  [Array] $postDatas [添加、编辑页面客户端回传的数据]
     * @return [Array]            [解析后的数据数组]
     */
    public function analyticalDatas($postDatas) {
        if (!$postDatas) {
            return false;
        }

        /* 取出section数据 */
        $postDatas = is_array($postDatas) ? $postDatas : json_decode($postDatas, true);
        /* 数据处理 */
        $datas = array();
        foreach ($postDatas as $value) {
            $value = is_array($value) ? $value : json_decode($value, true);
            /* 去壳 */
            foreach ($value as $first) {
                $first = is_array($first) ? $first : json_decode($first, true);
                /* 循环第一层数据 */
                foreach ($first as $second) {
                    $second = is_array($second) ? $second : json_decode($second, true);
                    /* 解析数组 */
                    $result_second = $this->analyticalArray($second, $datas);
                    $datas = array_merge($datas, $result_second);
                    /* 循环子级数据(第二层数据) */
                    if ($second['child']) {
                        foreach ($second['child'] as $third) {
                            /* 解析数组 */
                            $result_third = $this->analyticalArray($third, $datas);
                            $datas = array_merge($datas, $result_third);
                        }
                        unset($result_third);
                    }
                }
                unset($result_two);
            }
        }

        return $datas ? $datas : array();
    }

    /**
     * 解析数组
     * @param  [Array] $arrayDatas [添加、编辑页面客户端回传的数据数组]
     * @param  [Array] $datas      [解析后的数据数组]
     * @return [Array]             [解析后的数据数组]
     */
    public function analyticalArray($arrayDatas, $datas = array()) {
        if (!is_array($arrayDatas)) {
            return false;
        }

        /* 有name值才做处理 */
        if ($arrayDatas['name']) {
            switch ($arrayDatas['type']) {
                /* 图片上传 */
                case 'img_view':
                    /* 用|分隔name值 */
                    $name = explode('|', $arrayDatas['name']);
                    if ($name[1] == 'array') {
                        /* 数组name值 */
                        $datas[$name[0]][] = $arrayDatas['value'][0];
                    } else {
                        /* 普通name值 */
                        if ($arrayDatas['num'] > 1) {
                            /* 多图上传 */
                            $datas[$name[0]] = $arrayDatas['value'];
                        } else {
                            /* 单图上传 */
                            $datas[$name[0]] = $arrayDatas['value'][0];
                        }
                    }
                    break;
                /* 两行父级，默认是普通name值，不做判断 */
                case 'parent_two':
                    $name = explode(',', $arrayDatas['name']);
                    $datas[$name[0]] = $arrayDatas['value'][0];
                    $datas[$name[1]] = $arrayDatas['value'][1];
                    break;
                /* 两行子级，默认是数组name值，不做判断 */
                case 'child_two':
                    $name = explode(',', $arrayDatas['name']);
                    $datas[$name[0]][] = $arrayDatas['value'][0];
                    $datas[$name[1]][] = $arrayDatas['value'][1];
                    break;
                /* 其他type类型值处理 */
                default:
                    /* 用|分割name值 */
                    $name = explode('|', $arrayDatas['name']);
                    if ($name[1] == 'array') {
                        /* 数组name值 */
                        $datas[$name[0]][] = $arrayDatas['value'][0];
                    } else {
                        /* 普通name值 */
                        $datas[$name[0]] = $arrayDatas['value'][0];
                    }
                    break;
            }
            unset($name);
        }

        return $datas;
    }

    /**
     * 商品评论星级
     * @param  [Float] $praise [商品评价平均分]
     * 规则：
     * 0.9 <= x       一星
     * 0.3 <= x < 0.9 半星
     * 0.0 <= x < 0.3 零星
     */
    public function praiseDeal($praise) {
        $praise = sprintf('%.2f', $praise);
        $praise = explode('.', $praise);
        if ($praise[1] >= 0.9) {
            $praise = $praise[0] + 1;
        } elseif ($praise[1] >= 0.3 && $praise[1] < 0.9) {
            $praise = $praise[0] + 0.5;
        } else {
            $praise = $praise[0];
        }
        return $praise;
    }

    /**
     * 发送短信
     * @author 406764368@qq.com
     * @version 2016年11月17日 15:08:02
     * @param  [String] $mobile    [接收短信的手机号]
     * @param  [Array]  $sendDatas [需要发送的数据]
     * @param  [String] $tempName  [模版字符串，配置文件中的键名]
     */
    public function smsSend($mobile, $sendDatas, $tempName) {
        /* 获取云通讯配置参数 */
        $smsConfig = C('SMS_CONFIG');

        /* 判断是否开通短信功能 */
        if ($smsConfig['isopen'] != 1) {
            return false;
        }

        /* 验证手机号 */
        if (empty($mobile) || !checkMobile($mobile)) {
            return false;
        }

        /* 判断模版id是否存在 */
        if (empty($smsConfig[$tempName])) {
            return false;
        }

        /* 导入云通讯第三方包 */
        vendor('CCPRestSDK');

        /* 初始化SDK */
        $rest = new \REST($smsConfig['serverIP'], $smsConfig['serverPort'], $smsConfig['softVersion']);
        $rest->setAccount($smsConfig['accountSid'], $smsConfig['accountToken']);
        $rest->setSubAccount($smsConfig['SubAccountSid'], $smsConfig['SubAccountToken'], $smsConfig['VoIPAccount'], $smsConfig['VoIPPassword']);
        $rest->setAppId($smsConfig['appId']);

        /* 发送短信模版 */
        $result = $rest->sendTemplateSMS($mobile, $sendDatas, $smsConfig[$tempName]);

        return $result;
    }

    /**
     * 极光推送
     */
    public function jPush($title, $content, $url = '') {
        if (!C('PUSH_APPKEY') || !C('PUSH_SECRET') || empty($title) || empty($content)) {
            return false;
        }
        /* 推送消息 */
        $receive = 'all';        // 全部
        $base64 = base64_encode(C('PUSH_APPKEY') . ':' . C('PUSH_SECRET'));
        $header = array("Authorization:Basic $base64", "Content-Type:application/json");
        $data = array();
        $data['platform'] = 'all';  // 目标用户终端手机的平台类型android,ios,winphone
        $data['audience'] = 'all';  // 目标用户

        $data['notification'] = array(
            // 统一的模式--标准模式
            "alert" => $content,
            // 安卓自定义
            "android" => array(
                "alert" => $content,
                "title" => $title,
                "builder_id" => 1,
                "extras" => array("url" => $url)
            ),
            // ios的自定义
            "ios" => array(
                "alert" => $content,
                "badge" => "1",
                "sound" => "default",
                "extras" => array("url" => $url)
            ),
        );

        // 苹果自定义---为了弹出值方便调测
        $data['message'] = array(
            "msg_content" => $content,
            "title" => $title,
            "extras" => array("url" => $url)
        );

        // 附加选项
        $data['options'] = array(
            "sendno" => time(),
            // "time_to_live"    => $m_time,      // 保存离线时间的秒数默认为一天
            "apns_production" => 1,            // 指定 APNS 通知发送环境：0开发环境，1生产环境。
        );
        $param = json_encode($data);

        $postUrl = "https://api.jpush.cn/v3/push";
        $ch = curl_init();                                      // 初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl);                // 抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);                    // 设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);                      // post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);          // 增加 HTTP Header（头）里的字段 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = curl_exec($ch);                        // 运行curl
        curl_close($ch);

        return $result;
    }
}
