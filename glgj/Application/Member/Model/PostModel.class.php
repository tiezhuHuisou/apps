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
		array('title', 'require', '请填写岗位名称', self::MUST_VALIDATE),
		array('title', '2,50', '岗位名称在2到50个字符之间', self::MUST_VALIDATE, 'length'),
		array('wages', 'require', '请填写薪资', self::MUST_VALIDATE),
		array('wages', '/^\d+(\.\d{1,2})?$/', '薪资最多保留2位小数', self::MUST_VALIDATE),
		array('num', 'require', '请填写招聘人数', self::MUST_VALIDATE),
		array('address', 'require', '请填写工作地点', self::MUST_VALIDATE),
		array('address', '2,200', '工作地点在2到200个字符之间', self::MUST_VALIDATE, 'length'),
		array('desc', 'require', '请填写岗位描述', self::MUST_VALIDATE)
	);
	protected $_auto = array(
		array('company_id', 'getCompanyId', self::MODEL_INSERT, 'callback'),
		array('ctime', 'time', self::MODEL_INSERT, 'function'),
		array('etime', 'time', self::MODEL_BOTH, 'function'),
	);

	/*新增编辑数据*/
	public function update() {
		$data = $this->create($_POST);
		if (empty($data)) {
			return false;
		}
		if (empty($data['id'])) {
			//add
			$id = $this->add($data);
			if (!$id) {
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

	/**
	 * 获取企业id
	 * @return [type] [description]
	 */
	protected function getCompanyId() {
		return M('Company')->where(array('user_id'=>UID))->getField('id');
	}
}