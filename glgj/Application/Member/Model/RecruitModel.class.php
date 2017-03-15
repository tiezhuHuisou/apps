<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-01-04
 * Time: 15:50
 */

namespace Member\Model;

use Think\Model;

class PostModel extends Model {

	protected $_validate = array(
		array('company_name', 'require', '请填写公司名称', self::MUST_VALIDATE),
		array('company_name', '2,10', '公司名称必须在2到10个字符之间', self::MUST_VALIDATE, 'length'),
		array('phone', 'require', '请填写手机号码', self::MUST_VALIDATE),
		array('phone', '/^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$/', '请填写正确的手机号码', self::MUST_VALIDATE, 'regex'),
		array('contact', 'require', '请填写联系人', self::MUST_VALIDATE),
		array('address', 'require', '请填写地址', self::MUST_VALIDATE),
		array('logo', 'require', '请上传公司LOGO', self::MUST_VALIDATE),
	);
	protected $_auto = array(
		array('ctime', 'time', self::MODEL_INSERT, 'function'),
		array('etime', 'time', self::MODEL_UPDATE, 'function'),
	);

	/*新增编辑数据*/
	public function update() {
		$data = $this->create($_POST);
		if (empty($data)) {
			return false;
		}
		if (empty($data['uid'])) {
			//add
			$data['uid'] = UID;
			$uid = $this->add($data);
			unset($data['uid']);
			if (!$uid) {
				$this->error = '新增出错';
				return false;
			}
		} else {
			//update
			$status = $this->save($data);
			if (false === $status) {
				$this->error = '修改出错';
				return false;
			}
		}
		return $data;
	}
}