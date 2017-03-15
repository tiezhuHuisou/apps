<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-04
 * Time: 15:50
 */

namespace App\Model;

use Think\Model;

class ApplyJobModel extends Model {
	protected $_validate = array(
		array('user_id', 'require', '参数错误', self::MUST_VALIDATE),
		array('name', 'require', '请填写姓名', self::MUST_VALIDATE),
		array('name', '2,12', '姓名为2-12位字符', self::MUST_VALIDATE, 'length'),
		// array('recommond_mobile', 'require', '请填写推荐人手机号', self::MUST_VALIDATE,'regex',Model:: MODEL_INSERT),
		// array('recommond_mobile', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '请输入正确的手机号码', self::MUST_VALIDATE,'regex',Model:: MODEL_INSERT),
		// array('recommond_mobile', 'checkRecommondExist', '推荐人不存在', self::MUST_VALIDATE,'callback',Model:: MODEL_INSERT),
		// array('recommond_mobile', 'checkRecommondSelf', '不能成为自己的推荐人', self::MUST_VALIDATE,'callback',Model:: MODEL_INSERT),
		// array('recommond_mobile', 'checkRecommondMobile', '不能成为相互的推荐人', self::MUST_VALIDATE,'callback',Model:: MODEL_INSERT),
		// array('recommond_mobile', 'checkRecommondEqual', '推荐人不能修改', self::MUST_VALIDATE,'callback',Model:: MODEL_UPDATE),
		array('age', 'require', '请填写年龄', self::MUST_VALIDATE),
		array('expect_wages', 'require', '请填写期望薪资', self::MUST_VALIDATE),
		array('name', 'require', '姓名不能为空', self::MUST_VALIDATE),
		array('phone', 'require', '请填写联系电话', self::MUST_VALIDATE),
		array('phone', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '请填写正确的联系电话', self::MUST_VALIDATE),
		array('email', 'require', '请填写常用邮箱', self::MUST_VALIDATE),
		array('email', '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', '请填写有效的常用邮箱', self::MUST_VALIDATE,'regex'),
		array('address', 'require', '请填写家庭住址', self::MUST_VALIDATE),
		array('address', '2,64', '家庭住址长度为2-64位字符', self::MUST_VALIDATE,'length')
	);
	protected $_auto = array(
		// array('pid', 'getRecommondId', Model::MODEL_INSERT, 'callback'),
		array('status', 1, Model::MODEL_INSERT),
		array('ctime', 'time', Model::MODEL_INSERT, 'function'),
	);

	/*新增编辑数据*/
	public function update() {
		$data = $_POST;
		$data = $this->create($data);
		if (empty($data)) {
			return false;
		}
		if (!$data['uid']) {
			// add
			$data['uid'] = $_POST['user_id'];
			unset($data['user_id']);
			$uid = $this->add($data);
			unset($data['uid']);
			if (!$uid) {
				$this->error = '新增出错';
				return false;
			}
		} else {
			// update
			unset($data['user_id']);
			$status = $this->save($data);
			if (false === $status) {
				$this->error = '修改出错';
				return false;
			}
		}
		return $data;
	}

	/**
	 * 获取推荐人id
	 */
	protected function getRecommondId() {
		$recommond_mobile = I('post.recommond_mobile');
		$uid = I('post.user_id');
		return M('member')->where(array('mphone'=>$recommond_mobile))->getField('uid');
	}

	/**
	 * 检测推荐人是否存在
	 */
	protected function checkRecommondExist() {
		if ( $this->getRecommondId() ) {
			return true;
		}
		return false;
	}

	/**
	 * 检测推荐人是否为自己
	 */
	protected function checkRecommondSelf() {
		$pid = $this->getRecommondId();
		$uid = I('post.user_id');
		if ( $uid == $pid ) {
			return false;
		}
		return true;
	}

	/**
	 * 检测是否相互添加推荐人
	 */
	protected function checkRecommondMobile() {
		$pid = $this->getRecommondId();
		$res = M('apply_job')->where(array('uid'=>$pid))->getField('pid');
		if ( $res == $pid ) {
			return false;
		}
		return true;
	}

	/**
	 * 推荐人不能修改
	 */
	protected function checkRecommondEqual() {
		$pid = $this->getRecommondId();
		$uid = I('post.user_id');
		$res = M('apply_job')->where(array('uid'=>$uid))->getField('pid');
		if ( $res == $pid ) {
			return true;
		}
		return false;
	}
}