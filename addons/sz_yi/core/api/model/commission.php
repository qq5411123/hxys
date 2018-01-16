<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace api\model;

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require __API_ROOT__ . '/../../plugin/commission/model.php';
class commission extends \CommissionModel
{
	protected $name_map = array(
		'status' => array('' => '非法状态', 0 => '未审核', 1 => '已审核')
		);

	public function __construct()
	{
		parent::__construct('commission');
	}

	public function getList($para)
	{
		$condition[] = 'WHERE 1';
		$params = array();
		if (isset($para['status']) && ($para['status'] != '')) {
			$condition['status'] = ' AND dm.status=' . intval($para['status']);
		}

		if (isset($para['id']) && !empty($para['id'])) {
			$condition['id'] = 'AND dm.id<' . $para['id'];
		}

		$condition['other'] = ' AND dm.uniacid = ' . $para['uniacid'] . ' AND dm.isagent =1 ';
		$condition_str = implode(' ', $condition);
		$_obf_DSk2GzI5CQUYHiodEQ5AEDIKBx8JFCI_ = '普通等级';
		$sql = 'select dm.id as member_id,dm.mobile,dm.realname,dm.nickname,dm.avatar,IFNULL(l.levelname,\'' . $_obf_DSk2GzI5CQUYHiodEQ5AEDIKBx8JFCI_ . '\') as levelname,dm.status from ' . tablename('sz_yi_member') . ' dm ' . ' left join ' . tablename('sz_yi_member') . ' p on p.id = dm.agentid ' . ' left join ' . tablename('sz_yi_commission_level') . ' l on l.id = dm.agentlevel' . ' left join ' . tablename('mc_mapping_fans') . 'f on f.openid=dm.openid and f.uniacid=' . $para['uniacid'] . '   ' . $condition_str . ' ';
		$sql .= 'ORDER BY dm.id DESC,dm.agenttime DESC';
		$list = pdo_fetchall($sql, $params);

		foreach ($list as &$item) {
			$item = $this->formatInfo($item);
		}

		return $list;
	}

	private function formatInfo($info)
	{
		$info['status_value'] = $info['status'];
		$info['status'] = array('name' => $this->name_map['status'][$info['status']], 'value' => $info['status']);
		return $info;
	}
}

?>
