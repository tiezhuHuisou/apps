<?php
namespace Member\Controller;

use Think\Controller;

class IndexController extends MemberController {
    public function _initialize() {
        parent::_initialize();
        $this->assign('site', 'home');
    }

    /**
     * 首页
     * @return [type] [description]
     * 备注type = 1->仓储主,2->个人货主,3->企业货主,4->个人车主,5->企业车主表,
     * 备注status = 0->申请中。1-》申请成功。2-》被拒。
     */
    public function index() {
        $array = array();       //方便存储数据
        /* 查询用户企业信息 */
        $info = M('Company')->field('id,name,logo,status')->where(array('user_id' => UID))->select();
        $this->assign('info', $info);

        /*我的权限数组*/
        $array[]['name'] = '仓储主';
        $array[]['name'] = '个人货主';
        $array[]['name'] = '企业货主';
        $array[]['name'] = '个人车主';
        $array[]['name'] = '企业车主';
        $i = 1;
        $statusInfo = M('Master')->where(array('uid' => UID))->getField('type,status', true);
        foreach ($array as $key => $val) {
            $array[$key]['type'] = $i;
            $array[$key]['num'] = '+';
            if (empty($statusInfo[$i])) {
                $array[$key]['url'] = '?g=member&m=master&a=apply&type=' . $i;
            } else if ($statusInfo[$i] == 1) {
                $array[$key]['url'] = '?g=member&m=master&a=index&type=' . $i;
            } else {
                $array[$key]['url'] = '?g=member&m=master&a=apply&type=' . $i;
            }
            $i++;
        }
        $list['area'] = $array;
        $this->assign('list', $list);
        /* 时间段 */

        $nowTime = time();
        $nowTime = $nowTime - 28800;
        $nowDate = date('Y-m-d', $nowTime);
        if ((strtotime($nowDate . ' 00:00:00') <= $nowTime) && ($nowTime < strtotime($nowDate . ' 05:00:00'))) {
            $dateTime = '凌晨了，这是要奋战到天亮的节奏吗？';
        } elseif (strtotime($nowDate . ' 05:00:00') <= $nowTime && $nowTime < strtotime($nowDate . ' 08:00:00')) {
            $dateTime = '早上好！一日之计在于晨，加油！';
        } elseif (strtotime($nowDate . ' 08:00:00') <= $nowTime && $nowTime < strtotime($nowDate . ' 11:00:00')) {
            $dateTime = '上午好！今天的早餐是不是又忘记吃了？';
        } elseif (strtotime($nowDate . ' 11:00:00') <= $nowTime && $nowTime < strtotime($nowDate . ' 13:00:00')) {
            $dateTime = '中午好！睡午觉是一种奢侈的享受！';
        } elseif (strtotime($nowDate . ' 13:00:00') <= $nowTime && $nowTime < strtotime($nowDate . ' 18:00:00')) {
            $dateTime = '下午好！一杯下午茶能很好的激活大脑内的小海马';
        } else {
            $dateTime = '晚上好！早睡早起身体好！';
        }
        $this->assign('dateTime', $dateTime);

        $this->site_title = '首页';
        $this->display();
    }
}