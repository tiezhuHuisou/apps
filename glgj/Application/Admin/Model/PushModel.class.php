<?php
namespace Admin\Model;

use Think\Model;

class PushModel extends Model {
    /* 自动验证 */
    protected $_validate = array(
        array('type', 'require', '请选择关联页面类型', self::MUST_VALIDATE),
        array('page', 'require', '请选择关联页面', self::MUST_VALIDATE),
        array('title', 'require', '请填写推送标题', self::MUST_VALIDATE),
        array('title', '1,50', '推送标题应为1-50个字符', self::MUST_VALIDATE, 'length'),
        array('content', 'require', '请填写推送内容', self::MUST_VALIDATE),
        array('content', '1,120', '推送内容应为1-120个字符', self::MUST_VALIDATE, 'length'),
    );

    /* 自动完成 */
    protected $_auto = array(
        array('url', 'getUrl', Model::MODEL_BOTH, 'callback'),
        array('addtime', 'time', Model::MODEL_INSERT, 'function')
    );

    /**
     * 查询单条记录
     * @param array $condition 查询条件
     * @param array $field 查询字段
     */
    public function getOneInfo($condition = array(), $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 添加、编辑
     */
    public function update() {
        /* 获取数据对象 */
        $datas = $_POST;
        $datas = $this->create($datas);

        if (empty($datas)) {
            return false;
        }

        /* 添加或更新 */
        if ($datas['uid'] > 0) {
            /* 修改 */
            $save = $this->save($datas);
            if ($save === false) {
                $this->error = '推送失败';
                return false;
            }
        } else {
            /* 添加 */
            $id = $this->add($datas);
            if (!$id) {
                $this->error = '推送失败';
                return false;
            }
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
            "alert" => $datas['content'],
            // 安卓自定义
            "android" => array(
                "alert" => $datas['content'],
                "title" => $datas['title'],
                "builder_id" => 1,
                "extras" => array("url" => $datas['url'])
            ),
            // ios的自定义
            "ios" => array(
                "alert" => $datas['content'],
                "badge" => "1",
                "sound" => "default",
                "extras" => array("url" => $datas['url'])
            ),
        );

        // 苹果自定义---为了弹出值方便调测
        $data['message'] = array(
            "msg_content" => $datas['content'],
            "title" => $datas['title'],
            "extras" => array("url" => $datas['url'])
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
        $datas['push'] = curl_exec($ch);                        // 运行curl
        curl_close($ch);

        return $datas;
    }

    /**
     * 删除数据
     * @param array $condition 删除条件
     */
    public function del($condition) {
        return $this->where($condition)->delete();
    }

    /**
     * 获取推荐页面url
     */
    protected function getUrl() {
        $page = I('post.page', 0, 'intval');
        $param = $this->getParam();
        return $param['url'] . $page;
    }

    /**
     * 根据推送类型获取对应参数
     */
    public function getParam() {
        $type = I('post.type', 0, 'intval');
        switch ($type) {
            case '1':
                /* 资讯 */
                $result['model'] = M('Article');
                $result['field'] = 'id,title,content';
                $result['where']['is_display'] = array('eq', 1);
                $result['order'] = 'sort DESC,flags DESC,uptime DESC';
                $result['url'] = '?g=app&m=news&a=detail&appsign=&id=';
                break;
            case '2':
                /* 产品 */
                $result['model'] = M('ProductSale');
                $result['field'] = 'id,title,summary content';
                $result['where']['status'] = array('eq', 1);
                $result['order'] = 'sort DESC,flags DESC,modify_time DESC';
                $result['url'] = '?g=app&m=apps&a=product_detail&id=';
                break;
            case '3':
                /* 企业 */
                $result['model'] = M('Company');
                $result['field'] = 'id,name title,summary content';
                $result['where']['status'] = array('eq', 1);
                $result['order'] = 'sort DESC,flags DESC,id DESC';
                $result['url'] = '?g=app&m=company&a=detail&appsign=1&id=';
                break;
            case '4':
                /* 求购 */
                $result['model'] = M('ProductBuy');
                $result['field'] = 'id,title,summary content';
                $result['where']['status'] = array('eq', 1);
                $result['order'] = 'sort DESC,flags DESC,modify_time DESC';
                $result['url'] = '?g=app&m=apps&a=need_detail&id=';
                break;
            case '5':
                /* 行业圈 */
                $result['model'] = M('Circle');
                $result['field'] = 'id,content title,content';
                $result['where']['status'] = array('eq', 1);
                $result['order'] = 'id DESC';
                $result['url'] = '?g=app&m=apps&a=circle_detail&id=';
                break;
            case '6':
                /* 公告 */
                $result['model'] = M('Announce');
                $result['field'] = 'id,content title,content';
                $result['where']['status'] = array('eq', 1);
                $result['order'] = 'id DESC';
                $result['url'] = '?g=app&m=product&a=announceDetail&appsign=1&id=';
                break;
            default:
                /* 默认为资讯 */
                $result['model'] = M('Article');
                $result['field'] = 'id,title,content';
                $result['where']['is_display'] = array('eq', 1);
                $result['order'] = 'sort DESC,flags DESC,uptime DESC';
                $result['url'] = '?g=app&m=apps&a=news_detail&id=';
                break;
        }
        $result['url'] = C('HTTP_ORIGIN') . '/' . $result['url'];
        return $result;
    }
}