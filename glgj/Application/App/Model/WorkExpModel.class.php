<?php
namespace App\Model;
use Think\Model;

/*个人工作经历表*/
class WorkExpModel extends Model {
	/*自动验证*/
	protected $_validate = array(
		array('company_name', 'require', '请填写单位名称', self::MUST_VALIDATE),
		array('post', 'require', '请填写在职岗位', self::MUST_VALIDATE),
		array('begin_time', 'require', '请选择开始时间', self::MUST_VALIDATE),
		array('exp', '1,255', '工作描述应为1-255', self::VALUE_VALIDATE, 'length')

	);
	/*自动完成*/
	protected $_auto = array(
		array('begin_time', 'strtotime', self::MODEL_BOTH, 'function'),
		array('end_time', 'strtotime', self::MODEL_BOTH, 'function'),
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

}