<?php
namespace Admin\Controller;

use Think\Controller;

class IndexController extends AdminController
{
    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('site', 'home');
    }

    /**
     * 网站首页
     * @return [type] [description]
     */
    public function index()
    {
        /*页面基本设置*/
        $this->site_title = "核心管理-管理首页";
        $this->assign('left', 'index');
        $this->display();
    }

    /**
     * 核心设置-基本信息
     * @return [type] [description]
     */
    public function core()
    {
       /*页面基本设置*/
        $this->site_title = "核心设置-基本信息";
        $this->assign('left', 'core');
        $this->assign('corenav','core');
        if (IS_POST) {
            $post = I('post.');
            if (!$post['member_type']) {
                $post['member_type'] = 0;
            }
            if (!$post['company_type']) {
                $post['company_type'] = 0;
            }
            if (!$post['start_flag']) {
                $post['start_flag'] = 0;
            } else {
                if ($post['href_type'] == 2) {
                    $data_id = $post['data_id'];
                    switch ($post['href_model']) {
                        case 'product_detail':
                            $url = '?g=app&m=apps&a=product_detail&id=' . $data_id;
                            break;
                        case 'news_detail':
                            $url = '?g=app&m=apps&a=news_detail&id=' . $data_id;
                            break;
                        case 'need_detail':
                            $url = '?g=app&m=apps&a=need_detail&id=' . $data_id;
                            break;
                        case 'company_home':
                            $url = '?g=app&m=apps&a=company_home&id=' . $data_id;
                            break;
                        default:
                            $this->error('参数错误');
                            break;
                    }
                    $post['start_url'] = C('HTTP_ORIGIN') . $url;
                }
            }
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);

            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
//            $dataList = $this->getModelAllDatas($info_arr['href_model']);
            $this->assign('dataList', $dataList);
            $this->assign('info_arr', $info_arr);
            $this->display();
        }
    }

    /**
     * 核心设置-公司信息
     * @return [type] [description]
     */
    public function core_company()
    {
        /*页面基本设置*/
        $this->site_title = "核心设置-基本信息";
        $this->assign('left', 'core');
        $this->assign('corenav','core_company');

        if (IS_POST) {
            $post = I('post.');
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);

            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
            $this->assign('info_arr', $info_arr);
            $this->display();
        }
    }

    /**
     * 核心设置-联系人信息
     * @return [type] [description]
     */
    public function core_linkman()
    {
        /*页面基本设置*/
        $this->site_title = "核心设置-联系人信息";
        $this->assign('left', 'core');
        $this->assign('corenav','core_linkman');

        if (IS_POST) {
            $post = I('post.');
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);

            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
            $this->assign('info_arr', $info_arr);
            $this->display();
        }
    }

    /**
     * 核心设置-支付宝帐号设置
     * @return [type] [description]
     */
    public function core_weixin()
    {
        /*页面基本设置*/
        $this->site_title = "核心设置-微信账号设置";
        $this->assign('left', 'core');
        $this->assign('corenav','core_weixin');
        if (IS_POST) {
            $post = I('post.');
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);

            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
            $this->assign('info_arr', $info_arr);
            $this->display();
        }
    }


    /**
     * 核心设置-支付宝帐号设置
     * @return [type] [description]
     */
    public function core_alipay()
    {
        /*页面基本设置*/
        $this->site_title = "核心设置-支付宝帐号信息";
        $this->assign('left', 'core');
        $this->assign('corenav','core_alipay');
        if (IS_POST) {
            $post = I('post.');
            foreach ($post as $key => $val) {
                $data['value'] = $val;
                M('Conf')->where(array('name' => $key))->save($data);
                unset($data);

            }
            $this->success('修改成功');
        } else {
            $info = M('Conf')->select();
            foreach ($info as $val) {
                $info_arr[$val['name']] = $val['value'];
            }
            $this->assign('info_arr', $info_arr);
            $this->display();
        }
    }

    /*
     * 下载说明文档
     *
     * */
    public function downfile()
    {
        $filename = realpath("app.doc");  //文件名
        $date = date("Ymd-H:i:m");
        Header("Content-type:   application/octet-stream ");
        Header("Accept-Ranges:   bytes ");
        Header("Accept-Length: " . filesize($filename));
        header("Content-Disposition:   attachment;   filename= {$date}.doc");
        echo file_get_contents($filename);
        readfile($filename);
    }

    /**
     * 省市区管理
     */
    public function regions()
    {
        $content = I('request.search');
        $where['name'] = array('like', '%' . $content . '%');
        $this->assign('search', $content);

        $list = $this->lists('Regions', $where, 'id ASC', 'id,name,parent,grade');
        foreach ($list as $key => $value) {
            switch ($list[$key]['grade']) {
                case '1':
                    $list[$key]['grade'] = '省份';
                    break;
                case '2':
                    $list[$key]['grade'] = '城市';
                    break;
                case '3':
                    $list[$key]['grade'] = '区/县';
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = '省市区管理';
        $this->assign('left', 'regions');
        $this->display();
    }

    /**
     * 省市区添加、修改
     */
    public function regionsAdd()
    {
        $id = I('request.id', 0, 'intval');
        $model = D('Regions');
        $opt = $id > 0 ? '修改' : '添加';
        if (IS_POST) {
            $result = $model->update();
            if ($result) {
                $this->success($opt . '成功', '?g=admin&m=index&a=regions');
            } else {
                $errorInfo = $model->getError();
                $this->error($errorInfo);
            }
        }

        /* 修改 */
        if ($id) {
            $condition['id'] = $id;
            $detail = $model->getOneInfo($condition);
            $this->assign('detail', $detail);
        }

        /* 所有省市区 */
        $where['grade'] = array('neq', 3);
        $where['status'] = array('eq', 1);
        $regions = M('Regions')->field('id,name,parent')->where($where)->select();
        $regions = $this->dumpTreeList($regions);
        $this->assign('regions', $regions);

        /* 页面基本设置 */
        $this->site_title = $opt . '省市区';
        $this->assign('left', 'regions');
        $this->display();
    }

    /**
     * 省市区删除
     */
    public function regionsDel()
    {
        $id = I('id');
        $model = D('Regions');
        $condition['id'] = $id;
        $return = $model->del($condition);
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 省市区批量删除
     */
    public function regionsDelAll()
    {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = D('Regions');
            $condition['id'] = array('in', $ids);
            $tem = $model->del($condition);
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * 运费模版列表
     */
    public function freight()
    {
        $content = I('request.search');
        $where['title'] = array('like', '%' . $content . '%');
        $this->assign('search', $content);

        /* 查询自营商家 */
        $where['proprietary'] = array('eq', 1);
        $list = $this->lists('Freight', $where);

        $this->assign('list', $list);

        /* 页面基本设置 */
        $this->site_title = '运费模版管理';
        $this->assign('left', 'freight');
        $this->display();
    }

    /**
     * 运费模版添加、编辑
     */
    public function freightAdd()
    {
        $id = I('id', 0, 'intval');
        // $model = D('Regions');
        $opt = $id > 0 ? '修改' : '添加';

        $ModelDB = M('Freight');
        $detail = $ModelDB->where(array('id' => $id))->find();
        if ($id && empty($detail)) {
            $this->error('运费模板不存在');
        }

        if (IS_POST) {
            $model = D('Freight');
            $result = $model->update();
            if ($result) {
                $this->success($opt . '成功', '/index.php?g=admin&m=index&a=freight');
            }
            $this->error($model->getError());
            // $post = $_POST;
            // $data['title'] = $post['title'];
            // $data['delivery_id'] = $post['delivery_id'];
            // $data['piece'] = $post['piece'];
            // foreach ($post['placeAllId'] as $key => $val) {
            //     $arr[$key]['placeallid'] = $val;
            //     $arr[$key]['package_first'] = $post['package_first'][$key] ? $post['package_first'][$key] : 0;
            //     $arr[$key]['freight_first'] = $post['freight_first'][$key] ? $post['freight_first'][$key] : 0;
            //     $arr[$key]['package_other'] = $post['package_other'][$key] ? $post['package_other'][$key] : 0;
            //     $arr[$key]['freight_other'] = $post['freight_other'][$key] ? $post['freight_other'][$key] : 0;
            // }
            // $data['postage'] = json_encode($arr);
            // $data['sort'] = $post['sort'] ? $post['sort'] : 0;
            // $data['etime'] = time();
            // if (!$post['id']) {
            //     $data['wid'] = $wid;
            //     $data['ctime'] = time();

            //     $save = $ModelDB->data($data)->add();

            //     $this->success('添加成功', '/index.php?g=admin&m=index&a=freight');
            // $this->assign('error', array('url' => ''));
            // $this->assign('info', $post);
            // $this->display();
            // exit;
            // } else {
            // if ($detail['wid'] != $wid) {
            //     echo "<script>alert('非法操作！');history.go(-1);</script>";
            //     exit;

            // }
            // $data['id'] = $post['id'];
            // $save = $ModelDB->data($data)->save();

            // $this->assign('error', array('url' => '/index.php?g=admin&m=index&a=freight'));
            // $this->assign('info', $data);
            // $this->display();
            // exit;
            // $this->success('添加成功', '/index.php?g=admin&m=index&a=freight');
            // }
        } else {
            $delivery = M('Express')->select();
            //获取所有省市区信息
            $city_arr = M('Regions')->cache(true, 3600)->select();
            foreach ($city_arr as $val) {
                $city_arr_name[$val['id']] = $val['name'];
                $city_arr_new[$val['id']] = $val;

                if ($val['parent']) {
                    $all_arr[$val['parent']][] = $val['id'];
                } else {
                    $all_arr['sheng'][] = $val['id'];
                }

                // if($val['grade']==2){
                //  $city_id_arr[]=$val['id'];
                // }
                // if($val['grade']==1){
                //  $province_id_arr[]=$val['id'];
                // }
            }
            // foreach($city_arr)

            $this->assign('city_arr_name', $city_arr_name);

            $detail['postage'] = json_decode($detail['postage']);
            foreach ($detail['postage'] as $val) {
                $val = get_object_vars($val);
                $post_address = explode(",", $val['placeallid']);

                if ($val['placeallid'] == 'moren') {
                    $val['placename'] = '默认';
                } else {
                    $val['placename'] = $this->detailtree($post_address, $city_arr_new, $city_arr_name);
                }

                $postage_arr[] = $val;
            }

            $detail['postage'] = $postage_arr;

            unset($postage_arr[0]);
            foreach ($postage_arr as $val) {
                $area_str .= $val['placeallid'];
            }
            $area_arr = explode(",", $area_str);
            //当前已选市ID
            foreach ($area_arr as $val) {
                if ($city_arr_new[$val]['parent']) {
                    $shi_arr[$city_arr_new[$val]['parent']][] = $val;
                    $shi_arr2[] = $city_arr_new[$val]['parent'];
                }
                if (!$all_arr[$val] && ($city_arr_new[$val]['grade'] == 2)) {

                    $shi_arr2[] = $val;
                    $use_city_id[] = $val;
                }
            }
            //当前已选省ID
            foreach (array_unique($shi_arr2) as $val) {
                if ($city_arr_new[$val]['parent']) {
                    $sheng_arr[$city_arr_new[$val]['parent']][] = $val;
                    $sheng_arr2[] = $city_arr_new[$val]['parent'];
                }
            }

            //获取隐藏市ID
            foreach ($all_arr as $key => $value) {
                foreach ($shi_arr as $k => $val) {
                    if (($key == $k)) {
                        if (!array_diff($value, $val)) {
                            $use_city_id[] = $key;
                        }

                    }
                }
            }
            //获取隐藏省ID
            foreach ($all_arr as $keynew => $valuenew) {
                foreach ($sheng_arr as $ks => $vals) {
                    if (($keynew == $ks)) {
                        if (!array_diff($valuenew, $vals)) {
                            // dump($valuenew);

                            // dump($vals);
                            $use_sheng_id[] = $keynew;
                        }

                    }
                }
            }
            // dump($all_arr[423]);
            // dump($sheng_arr);
            // dump(array_diff($all_arr[423],$sheng_arr[423]));

            $this->assign('use_city_id', implode(",", $use_city_id));
            $this->assign('use_sheng_id', implode(",", $use_sheng_id));

            $this->assign('info', $detail);

            //页面显示省市区
            $all_area = list_to_tree($city_arr, $pk = 'id', $pid = 'parent', $child = '_child', $root = 0);
            $areastr = $this->detailareastr($all_area, $id, $detail['postage']);
            $this->assign('areastr', $areastr);

            // echo($areastr);exit;

            $right = $this->detailrightstr($all_area, $id);
            $this->assign('right', $right);

            $this->assign('delivery', $delivery);
            // $this->display();

            /* 页面基本设置 */
            $this->site_title = $opt . '运费模版';
            $this->assign('left', 'freight');
            $this->display();
        }


    }

    /**
     * 运费模版删除
     */
    public function freightDel()
    {
        $id = I('id');
        $model = M('Freight');
        $condition['id'] = $id;
        $return = $model->where($condition)->delete();
        if ($return != false) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 运费模版批量删除
     */
    public function freightDelAll()
    {
        if (IS_POST) {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $model = M('Freight');
            $condition['id'] = array('in', $ids);
            $tem = $model->where($condition)->delete();
            if ($tem != false) {
                $return['errno'] = 0;
                $return['error'] = "删除成功";
                $this->ajaxReturn($return);
            } else {
                $return['errno'] = 1;
                $return['error'] = "删除失败";
                $this->ajaxReturn($return);
            }
        }
    }

    /**
     * @param  array $city_arr 所有省市区信息
     * @return string           左侧地区信息
     * @author 122837594@qq.com
     */
    public function detailareastr($city_arr, $id)
    {

        $this->assign('id', $id);
        $this->assign('city_arr', $city_arr);
        return $this->fetch('detailareastr');
    }

    /**
     * @param  array $city_arr 所有省市区信息
     * @return string           右侧地区信息
     * @author 122837594@qq.com
     */
    public function detailrightstr($city_arr, $id)
    {
        $this->assign('id', $id);
        $this->assign('city_arr', $city_arr);
        return $this->fetch('detailrightstr');
    }

    /**
     * 把当前邮费地址处理成树
     * @param  array $arr 当前邮费地址
     * @param  array $city_arr 所有省市区信息
     * @return string           地区信息
     * @author 122837594@qq.com
     */
    protected function detailtree($arr, $city_arr, $city_arr_name)
    {
        foreach ($arr as $val) {
            if ($city_arr[$val]['parent']) {
                $new_arr[$city_arr[$val]['parent']][] = $val;
            }
        }
        foreach ($new_arr as $key => $val) {
            $tmp_arr[$key] = $val;
            //if ($city_arr[$key]['parent']) {
            $new_arr2[$city_arr[$key]['parent']][] = $tmp_arr;
            //}
            $tmp_arr = array();
        }
        $str = "";
        foreach ($new_arr2 as $provincek => $provincevo) {

            $str .= $city_arr_name[$provincek] . "(";
            foreach ($provincevo as $allcity) {
                foreach ($allcity as $citykey => $cityvo) {
                    $str .= $city_arr_name[$citykey] . ":";
                    foreach ($cityvo as $areavo) {
                        $str .= $city_arr_name[$areavo] . ",";
                    }
                }
            }
            $str = rtrim($str, ',');
            $str .= ")<br /><br />";
        }

        return $str;
    }

    private function &dumpTreeList($arr, $parentId = 0, $lv = 0)
    {
        $lv++;
        $tree = array();
        foreach ((array)$arr as $row) {
            if ($row['parent'] == $parentId) {
                $row['level'] = $lv;
                if ($row['parent'] != 0) {
                    $row['sty'] = "|";
                }

                for ($i = 1; $i < $row['level']; $i++) {
                    $row['sty'] .= "——";
                    $row['sty2'] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                $row['sty2'] = $row['sty2'] . $row['sty'];
                $tree[] = $row;
                if ($children = $this->dumpTreeList($arr, $row['id'], $lv)) {
                    $tree = array_merge($tree, $children);
                }
            }
        }
        return $tree;
    }
}