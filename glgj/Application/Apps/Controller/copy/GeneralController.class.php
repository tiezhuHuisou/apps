<?php
namespace Apps\Controller;
use Think\Controller;

class GeneralController extends ApiController {
    /**
     * 架构函数
     */
    protected function _initialize() {
        parent::_initialize();
    }

    /**
     * 通用图片上传接口，前台用
     */
    public function uploadImage() {
        if ( IS_POST ) {
            $secretKey   = 'Luxury';
            $imgStream   = I('request.img_stream');
            $imgSuffix   = I('request.suffix');
            $imgStream   = explode(',', $imgStream);
            $imgSuffix   = explode(',', $imgSuffix);
            $allowSuffix = array('.jpg', '.png', '.gif', '.jpeg');
            $nowDate     = date('Ymd');

            /* 先验证全部参数再统一上传图片，减少无用的图片上传操作 */
            foreach ($imgStream as $k => $v) {
                if ( empty($v) ) {
                    $this->ajaxJson('70000', '请选择图片');
                }
                if ( empty($imgSuffix[$k]) ) {
                    $this->ajaxJson('70000', '图片后缀名不能为空');
                }
                if ( !in_array($imgSuffix[$k], $allowSuffix) ) {
                    $this->ajaxJson('70000', '只允许上传jpg、png、gif、jpeg格式的图片');
                }
            }

            /* 判断保存目录是否存在 */
            $folder = './Uploads/Apps/' . $nowDate;
            if ( !is_dir($folder) ) {
                mkdir($folder, 0755, true);
            }

            /* 支持多图片上传 */
            foreach ($imgStream as $key => $value) {
                /* 文件名 */
                $imgName = $folder . '/' . md5( time() . mt_rand(100000, 999999) . $secretKey) . $imgSuffix[$key];
                /* 解码图片流 */
                $img = base64_decode($value);
                /* 保存图片 */
                $status = file_put_contents($imgName, $img);
                if ( $status ) {
                    $list['imgUrl'][] = C('HTTP_ORIGIN') . substr($imgName, 1);
                } else {
                    $this->ajaxJson('70000', '图片上传失败');
                }
            }

            $this->returnJson($list, '40000', '图片上传成功');
        }
        $this->ajaxJson('70001');
    }

    /**
     * 通用图片上传接口，后台用
     */
    public function uploadImgs($stream, $suffix) {
        $secretKey   = 'Luxury';
        $imgStream   = $stream;
        $imgSuffix   = $suffix;
        $allowSuffix = array('.jpg', '.png', '.gif', '.jpeg');
        if ( empty($imgStream) ) {
            return array('info' => '请选择图片', 'img' => '');
        }
        if ( empty($imgSuffix) ) {
            return array('info' => '图片后缀名不能为空', 'img' => '');
        }
        if ( !in_array($imgSuffix, $allowSuffix) ) {
            return array('info' => '只允许上传jpg、png、gif、jpeg格式的图片', 'img' => '');
        }
        /* 判断保存目录是否存在 */
        $folder = './Uploads/Apps/' . date('Ymd');
        if ( !is_dir($folder) ) {
            mkdir($folder, 0755, true);
        }
        $imgName = $folder . '/' . md5( time() . mt_rand(100000, 999999) . $secretKey) . $imgSuffix;
        /* 解码图片流 */
        $img = base64_decode($imgStream);
        /* 保存图片 */
        $status = file_put_contents($imgName, $img);
        if ( $status ) {
            $imgUrl = C('HTTP_ORIGIN') . substr($imgName, 1);
            return array('info' => '图片上传成功', 'img' => $imgUrl);
        } else {
            return array('info' => '图片上传失败', 'img' => '');
        }
    }

    /**
     * 通用收藏接口
     */
    public function collectAdd() {
        if ( IS_POST ) {
            $token = I('token');
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');

            $model = D('UserFavorite');
            $result = $model->update();
            if ( $result ) {
                $this->ajaxJson('40000', '收藏成功');
            } else {
                $this->ajaxJson('70000', $model->getError());
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 通用取消收藏接口
     */
    public function collectDel() {
        if ( IS_POST ) {
            $aid   = I('post.aid', 0, 'intval');
            $aid   = explode(',', $aid);
            $token = I('token');
            $favorite_category = I('post.favorite_category', 0, 'intval');
            if ( !is_array($aid) || !count($aid) || !$favorite_category ) {
                $this->ajaxJson('70000', '参数错误');
            }
            /* 检测用户登陆状态 */
            empty($token) && $this->ajaxJson('70000', '请先登陆');
            $memberInfo = D('Token')->getMemberInfo($token);
            !$memberInfo && $this->ajaxJson('40004');
            
            $model = D('UserFavorite');
            $condition['aid'] = array('in', $aid);
            $condition['favorite_category'] = array('eq', $favorite_category);
            $return = $model->del($condition);
            if ( $return ) {
                $this->ajaxJson('40000', '取消收藏成功');
            } else {
                $this->ajaxJson('70000', '取消收藏失败');
            }
        }
        $this->ajaxJson('70001');
    }

    /**
     * 发送站内信
     * @param  [String]  $title   [站内信标题]
     * @param  [String]  $message [站内信内容]
     * @param  [integer] $to_user [用户id，0代表全体]
     * @param  [integer] $to_type [接收人群：1->全体会员，2->个人]
     */
    public function sendSiteMessage( $title, $message, $to_user, $to_type = 2 ) {
        $datas['to_user'] = $to_user;
        $datas['to_type'] = $to_type;
        $datas['title']   = $title;
        $datas['message'] = $message;
        $datas['addtime'] = time();
        $datas['mobile']  = $to_type == 2 ? M('Member')->where(array('uid'=>$to_user))->getField('mphone') : '全体成员';
        M('UserMessage')->add($datas);
    }
}