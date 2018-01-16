<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace admin\api\model;

class commissionApply
{
	private $commission_model;
	private $order_list;
	private $uniacid;
	protected $name_map = array(
		'status'     => array(-1 => '未通过', 0 => '未知', 1 => '审核中', 2 => '已通过', 3 => '已打款'),
		'type'       => array('余额', '微信'),
		'commission' => array(1 => '一级佣金', 2 => '二级佣金', 3 => '三金佣金')
		);

	public function __construct($uniacid)
	{
		$this->uniacid = $uniacid;
		$this->commission_model = new commission();
	}

	public function getInfo($commission_apply_id, $uniacid)
	{
		$commission_model = $this->commission_model;
		$apply_info = $this->_getBaseInfo($uniacid, $commission_apply_id);
		$_obf_DQMTFgERGBMQLDgKK1saKQ4uGiYlGyI_ = $commission_model->getLevel($apply_info['mid']);
		$apply_info['order_list'] = $this->_getOrderList($apply_info['orderids'], $uniacid, $_obf_DQMTFgERGBMQLDgKK1saKQ4uGiYlGyI_['id']);
		$this->order_list = $apply_info['order_list'];
		return $apply_info;
	}

	public function _getBaseInfo($uniacid, $id, $fields = '*')
	{
		$apply = pdo_fetch('select ' . $fields . ' from ' . tablename('sz_yi_commission_apply') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $uniacid, ':id' => $id));
		$apply['applytime'] = date('Y-m-d H:i', $apply['applytime']);
		$apply['checktime'] = date('Y-m-d H:i', $apply['checktime']);
		$apply['paytime'] = date('Y-m-d H:i', $apply['paytime']);
		$apply['invalidtime'] = date('Y-m-d H:i', $apply['invalidtime']);
		$apply['type_name'] = $this->name_map['type'][$apply['type']];
		$apply['status_name'] = $this->name_map['status'][$apply['status']];
		return $apply;
	}

	private function _getOrderList($orderids, $uniacid, $agent_level_id)
	{
		$orderids = iunserializer($orderids);
		if (!is_array($orderids) || (count($orderids) <= 0)) {
			$this->returnError('无任何订单，无法查看!');
		}

		$id_array = array_column($orderids, 'orderid');
		$order_list = pdo_fetchall('select id as order_id, ordersn,price,createtime, paytype,dispatchprice from ' . tablename('sz_yi_order') . ' where id in ( ' . implode(',', $id_array) . ' );');
		$_obf_DSw3GAE9PREbBxojKR4CJDIQFS4tLCI_ = array_column($orderids, 'level', 'orderid');

		foreach ($order_list as &$order) {
			$order['level'] = $_obf_DSw3GAE9PREbBxojKR4CJDIQFS4tLCI_[$order['order_id']];
			$goods_list = $this->_getOrderGoods($uniacid, $order, $agent_level_id);
			$order['commission'] = array_sum(array_column($goods_list, 'commission'));
			$order['commission_pay'] = array_sum(array_column($goods_list, 'commission_pay'));
			$order['goods'] = $goods_list;
			$order['createtime'] = date('Y-m-d H:i', $order['createtime']);
			$order_model = new order();
			$_obf_DRkoOBcEDR0ZIRwiOTIFKxArOBhbPwE_ = $order_model->getPayTypeName();
			$order['pay_type_name'] = $_obf_DRkoOBcEDR0ZIRwiOTIFKxArOBhbPwE_[$order['paytype']];
		}

		return $order_list;
	}

	public function getCheckOrderGoods($order_ids, $check_status_array, $agent_level_id)
	{
		$_obf_DTwfHjYiFxYcNAlAJxQdEFsECDcBHSI_ = implode(',', $order_ids);
		$_obf_DRckPAYnCxoSWzArDg8iLTQ7DAcUOyI_ = implode(',', array_keys($check_status_array));
		$order_goods_list = pdo_fetchall('SELECT og.*,o.id as order_id FROM ' . tablename('sz_yi_order_goods') . " AS og\n        JOIN " . tablename('sz_yi_order') . " AS o ON og.orderid = o.id\n        WHERE og.uniacid = :uniacid AND og.orderid IN (" . $_obf_DTwfHjYiFxYcNAlAJxQdEFsECDcBHSI_ . ') AND nocommission=0 AND og.id IN (' . $_obf_DRckPAYnCxoSWzArDg8iLTQ7DAcUOyI_ . ')', array(':uniacid' => $this->uniacid));

		foreach ($order_goods_list as &$order_goods) {
			$_obf_DRg_HAY_DwUjMiYlAx4oFRcVJg0XWyI_ = $check_status_array[$order_goods['id']];
			$order_goods['status' . $agent_level_id] = $_obf_DRg_HAY_DwUjMiYlAx4oFRcVJg0XWyI_;
			$order_goods = $this->_getCommissionInfo($order_goods, $agent_level_id);

			if (isset($order_goods['commission_pay'])) {
				$update = array('checktime' . $agent_level_id => time(), 'status' . $agent_level_id => intval($_obf_DRg_HAY_DwUjMiYlAx4oFRcVJg0XWyI_), 'content' . $agent_level_id => '');
				pdo_update('sz_yi_order_goods', $update, array('id' => $order_goods['id']));
			}
		}

		return $order_goods_list;
	}

	private function _getOrderGoods($uniacid, $order)
	{
		$goods_list = pdo_fetchall("SELECT og.id as order_goods_id,g.thumb,og.price,og.realprice, og.total,g.title,o.paytype,og.optionname,og.commission1,og.commission2,og.commission3,og.commissions,og.status1,og.status2,og.status3,og.content1,og.content2,og.content3 \nfrom " . tablename('sz_yi_order_goods') . ' og' . " \nleft join " . tablename('sz_yi_goods') . ' g on g.id=og.goodsid  ' . " \nleft join " . tablename('sz_yi_order') . ' o on o.id=og.orderid  ' . " \nwhere og.uniacid = :uniacid and og.orderid=:orderid and og.nocommission=0 \norder by og.createtime  desc ", array(':uniacid' => $uniacid, ':orderid' => $order['order_id']));

		foreach ($goods_list as &$goods) {
			$goods = $this->_getCommissionInfo($goods, $order['level']);
			$goods['level'] = $order['level'];
			$goods = set_medias($goods, 'thumb');
			$goods['total_price'] = $goods['realprice'] * $goods['total'];
			$goods = array_part('order_goods_id,thumb,title,realprice,total,total_price,status,status_name,optionname,commission,commission_pay,commission_name', $goods);
		}

		return $goods_list;
	}

	private function _getCommissionInfo($goods, $order_commission_level)
	{
		$commission_model = $this->commission_model;
		$_obf_DQcBLSkKFQ8DGxswOxYpJRIrKg89NQE_ = $commission_model->getSet();
		$commission_level = min($_obf_DQcBLSkKFQ8DGxswOxYpJRIrKg89NQE_, $order_commission_level);
		$_obf_DTE3JR0BLTMDKiQUMi4wPBcBJykvFgE_ = $this->_statisticsCommission(iunserializer($goods['commissions']), iunserializer($goods['commission' . $commission_level]), $commission_level, $goods['status' . $commission_level]);
		$_obf_DS8iLhA_KCccMDg3CzYxGRQnEzNAKxE_ = $this->name_map['commission'];
		$_obf_DSYIDQwmAgI8ODMDJCgHAS4tBwI_KBE_ = $this->name_map['status'];
		$goods['commission'] = $_obf_DTE3JR0BLTMDKiQUMi4wPBcBJykvFgE_['commission'];
		$goods['commission_pay'] = $_obf_DTE3JR0BLTMDKiQUMi4wPBcBJykvFgE_['commission_pay'];
		$goods['commission_name'] = $_obf_DS8iLhA_KCccMDg3CzYxGRQnEzNAKxE_[$commission_level];
		$goods['status'] = $goods['status' . $commission_level];
		$goods['status_name'] = $_obf_DSYIDQwmAgI8ODMDJCgHAS4tBwI_KBE_[$goods['status']];
		return $goods;
	}

	private function _statisticsCommission($commissions_arr, $agent_commission, $commission_level, $goods_check_status)
	{
		$agent_level_id = $this->agent_level['id'];

		if (empty($commissions_arr)) {
			$commission = (isset($agent_commission['level' . $agent_level_id]) ? $agent_commission['level' . $agent_level_id] : $agent_commission['default']);
		}
		else {
			$commission = (isset($commissions_arr['level' . $commission_level]) ? floatval($commissions_arr['level' . $commission_level]) : 0);
		}

		if (2 <= $goods_check_status) {
			$commission_pay = $commission;
		}
		else {
			$commission_pay = 0;
		}

		$res = array('commission' => $commission, 'commission_pay' => $commission_pay);
		return $res;
	}

	public function getList($para)
	{
		$condition[] = 'WHERE 1';
		$params = array();
		$status = $para['status'];
		$condition['other'] = ' and a.uniacid=:uniacid and a.status=:status';
		if (isset($para['id']) && !empty($para['id'])) {
			$condition['id'] = ' AND a.id<:id';
			$params += array(':id' => $para['id']);
		}

		$params += array(':uniacid' => $para['uniacid'], ':status' => $status);

		if (3 <= $status) {
			$orderby = 'paytime';
		}
		else if (2 <= $status) {
			$orderby = ' checktime';
		}
		else {
			$orderby = ' applytime';
		}

		$condition_str = implode(' ', $condition);
		$sql = 'select a.id as commission_apply_id,a.status,a.commission,m.avatar,m.realname,applytime,checktime,invalidtime,paytime,type from ' . tablename('sz_yi_commission_apply') . ' a ' . ' left join ' . tablename('sz_yi_member') . ' m on m.id = a.mid' . ' left join ' . tablename('sz_yi_commission_level') . ' l on l.id = m.agentlevel' . ' ' . $condition_str . ' ORDER BY ' . $orderby . ' desc ';
		$list = pdo_fetchall($sql, $params);

		foreach ($list as &$row) {
			$row = $this->formatInfo($row);
		}

		dump($list);
		return $list;
	}

	private function formatInfo($row)
	{
		$row['applytime'] = (1 <= $row['status']) || ($row['status'] == -1) ? date('Y-m-d H:i', $row['applytime']) : '--';
		$row['checktime'] = 2 <= $row['status'] ? date('Y-m-d H:i', $row['checktime']) : '--';
		$row['paytime'] = 3 <= $row['status'] ? date('Y-m-d H:i', $row['paytime']) : '--';
		$row['invalidtime'] = $row['status'] == -1 ? date('Y-m-d H:i', $row['invalidtime']) : '--';
		$row['typestr'] = $this->name_map['type'][$row['type']];
		return $row;
	}

	public function getAgentLevel($mid)
	{
		$agentLevel = parent::getLevel($mid);

		if (empty($agentLevel['id'])) {
			$agentLevel = array('levelname' => empty($this->set['levelname']) ? '普通等级' : $this->set['levelname'], 'commission1' => $this->set['commission1'], 'commission2' => $this->set['commission2'], 'commission3' => $this->set['commission3']);
		}

		return $agentLevel;
	}
}

if (!defined('IN_IA')) {
	exit('Access Denied');
}

require __CORE_PATH__ . '/../plugin/commission/model.php';

?>
