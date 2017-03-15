<?php
namespace Apps\Controller;
use Think\Controller;

class StartController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 启动广告图
     */
    public function index() {
        if ( IS_GET ) {
            /* 定义app图片路径 */
            $appsPath = C('HTTP_APPS_IMG');
            /* 查询启动广告图信息 */
            $conf = M('Conf')->getField('name,value', true);
            /* 启动广告图标识：1开启；0关闭 */
            $list['start_flag']         = $conf['start_flag'] ? strval($conf['start_flag']) : '0';
            /* 启动广告图自动跳过时间，默认3秒 */
            $list['start_time']         = $conf['start_time'] ? strval($conf['start_time']) : '3';
            /* 启动广告图图片 */
            $list['start_img']          = $conf['start_img'] ? $this->getAbsolutePath($conf['start_img']) : '';
            /* 启动广告图跳转链接地址 */
            $list['start_url']          = $conf['start_url'] ? $conf['start_url'] : '';
            /* 企业客服电话 */
            $list['companphone']        = $conf['companphone'] ? $conf['companphone'] : '';
            /* app版本标识：1-> 电商版；2->资讯版*/
            $list['index_template']     = C('FLASHFLAG');
            /* 分销标识：1开启；0关闭 */
            $list['distribution_flag']  = strval($conf['distribution']);
            /* 获取云通讯配置参数 */
            $smsConfig                  = C('SMS_CONFIG');
            /* 短信功能标识：1开启；0关闭 */
            $list['sms_flag']           = $smsConfig['isopen'];
            /* 支付宝私钥 */
            $list['alipay_private_key'] = C('ALIPAY_PRIVATE_KEY');
            /* 融云key */
            $list['rongcloud_key']      = C('RONGCLOUDAPPKEY');
            /* 极光推送key */
            $list['push_key']           = C('PUSH_APPKEY');
            /* 高德地图key */
            $list['amap_key']           = C('AMAP_APPKEY');
            /* 下载链接 */
            $downloadLink = M('ApplicationConfig', 'hscom_', 'mysql://system:system@192.168.27.42:3306/system')
                          ->alias('l')
                          ->field('l.app_id,r.ios,r.apk')
                          ->join('hscom_application_download r ON l.app_id = r.app_id', 'LEFT')
                          ->where(array('l.code'=>C('DB_USER')))
                          ->find();
            /* ios下载链接 */
            $list['download_ios']     = $downloadLink['ios'] ? $downloadLink['ios'] : '';
            /* android下载链接 */
            $list['download_android'] = $downloadLink['apk'] ? $downloadLink['apk'] : '';
            /* android版本号 */
            $list['android_code']     = '2.0';
            /* 底部栏 */
            if ( $list['index_template'] == 1 ) {
                $list['footer_list'] = array(
                    array( 'title' => '首页', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_home_normal.png', 'icon_selected' => $appsPath . 'footer_home_selected.png', 'check_login' => '0', 'href' => 'home' ),
                    array( 'title' => '产品', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_product_normal.png', 'icon_selected' => $appsPath . 'footer_product_selected.png', 'check_login' => '0', 'href' => 'product_list' ),
                    array( 'title' => '行业圈', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_circle_normal.png', 'icon_selected' => $appsPath . 'footer_circle_selected.png', 'check_login' => '0', 'href' => 'circle_list' ),
                    array( 'title' => '企业', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_company_normal.png', 'icon_selected' => $appsPath . 'footer_company_selected.png', 'check_login' => '0', 'href' => 'company_list' ),
                    array( 'title' => '我的', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_mine_normal.png', 'icon_selected' => $appsPath . 'footer_mine_selected.png', 'check_login' => '1', 'href' => 'mine' )
                    // array( 'title' => '求购', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_need_normal.png', 'icon_selected' => $appsPath . 'footer_need_selected.png', 'check_login' => '0', 'href' => 'need_list' ),
                    // array( 'title' => '购物车', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_cart_normal.png', 'icon_selected' => $appsPath . 'footer_cart_selected.png', 'check_login' => '1', 'href' => 'cart' ),
                );
            } else {
                $list['footer_list'] = array(
                    array( 'title' => '首页', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_home_normal.png', 'icon_selected' => $appsPath . 'footer_home_selected.png', 'check_login' => '0', 'href' => 'home' ),
                    array( 'title' => '资讯', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_news_normal.png', 'icon_selected' => $appsPath . 'footer_news_selected.png', 'check_login' => '0', 'href' => 'news_list' ),
                    array( 'title' => '产品', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_product_normal.png', 'icon_selected' => $appsPath . 'footer_product_selected.png', 'check_login' => '0', 'href' => 'product_list' ),
                    array( 'title' => '行业圈', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_circle_normal.png', 'icon_selected' => $appsPath . 'footer_circle_selected.png', 'check_login' => '0', 'href' => 'circle_list' ),
                    array( 'title' => '我的', 'title_color_normal' => '#151618', 'title_color_selected' => '#FF5000', 'icon_normal' => $appsPath . 'footer_mine_normal.png', 'icon_selected' => $appsPath . 'footer_mine_selected.png', 'check_login' => '1', 'href' => 'mine' )
                );
            }

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }

    /**
     * ios热更新
     */
    public function ios() {
        if ( IS_GET ) {
            /* 读取ios热更新js内容返回给客户端 */
            $list['js'] = file_get_contents('./Public/Apps/js/test.js');

            $this->returnJson($list);
        }
        $this->ajaxJson('70002');
    }
}