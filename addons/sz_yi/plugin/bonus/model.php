<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	exit('Access Denied');
}

define('TM_COMMISSION_AGENT_NEW', 'commission_agent_new');
define('TM_BONUS_ORDER_PAY', 'bonus_order_pay');
define('TM_BONUS_ORDER_FINISH', 'bonus_order_finish');
define('TM_BONUS_ORDER_AREA_PAY', 'bonus_order_area_pay');
define('TM_BONUS_ORDER_AREA_FINISH', 'bonus_order_area_finish');
define('TM_COMMISSION_APPLY', 'commission_apply');
define('TM_COMMISSION_CHECK', 'commission_check');
define('TM_BONUS_PAY', 'bonus_pay');
define('TM_BONUS_PAY_AREA', 'bonus_pay_area');
define('TM_BONUS_GLOBAL_PAY', 'bonus_global_pay');
define('TM_BONUS_UPGRADE', 'bonus_upgrade');
define('TM_COMMISSION_BECOME', 'commission_become');

if (!class_exists('BonusModel')) {
	class BonusModel extends PluginModel
	{
		private $agents = array();
		private $parentAgents = array();

		public function getSet($uniacid = 0)
		{
			global $_W;

			if (!empty($uniacid)) {
				$_W['uniacid'] = $uniacid;
			}

			$set = parent::getSet();
			$set['texts'] = array('agent' => empty($set['texts']['agent']) ? '代理商' : $set['texts']['agent'], 'premiername' => empty($set['texts']['premiername']) ? '全球分红' : $set['texts']['premiername'], 'center' => empty($set['texts']['center']) ? '分红中心' : $set['texts']['center'], 'commission' => empty($set['texts']['commission']) ? '佣金' : $set['texts']['commission'], 'commission1' => empty($set['texts']['commission1']) ? '分红佣金' : $set['texts']['commission1'], 'commission_total' => empty($set['texts']['commission_total']) ? '累计分红佣金' : $set['texts']['commission_total'], 'commission_ok' => empty($set['texts']['commission_ok']) ? '待分红佣金' : $set['texts']['commission_ok'], 'commission_apply' => empty($set['texts']['commission_apply']) ? '已申请佣金' : $set['texts']['commission_apply'], 'commission_check' => empty($set['texts']['commission_check']) ? '待打款佣金' : $set['texts']['commission_check'], 'commission_lock' => empty($set['texts']['commission_lock']) ? '未结算佣金' : $set['texts']['commission_lock'], 'commission_detail' => empty($set['texts']['commission_detail']) ? '分红明细' : $set['texts']['commission_detail'], 'commission_pay' => empty($set['texts']['commission_pay']) ? '已分红佣金' : $set['texts']['commission_pay'], 'order' => empty($set['texts']['order']) ? '分红订单' : $set['texts']['order'], 'order_area' => empty($set['texts']['order_area']) ? '区域订单' : $set['texts']['order_area'], 'mycustomer' => empty($set['texts']['mycustomer']) ? '我的下线' : $set['texts']['mycustomer'], 'agent_province' => empty($set['texts']['agent_province']) ? '省级代理' : $set['texts']['agent_province'], 'agent_city' => empty($set['texts']['agent_city']) ? '市级代理' : $set['texts']['agent_city'], 'agent_district' => empty($set['texts']['agent_district']) ? '区级代理' : $set['texts']['agent_district'], 'agent_street' => empty($set['texts']['agent_street']) ? '街级代理' : $set['texts']['agent_street'], 'withdraw' => empty($set['texts']['withdraw']) ? '提现' : $set['texts']['withdraw'], 'team' => empty($set['texts']['team']) ? '团队' : $set['texts']['team'], 'area' => empty($set['texts']['area']) ? '地区' : $set['texts']['area'], 'global' => empty($set['texts']['global']) ? '全球' : $set['texts']['global']);
			return $set;
		}

		public function getChildAgents_level($id, $level_id)
		{
			global $_W;
			$sql = 'select id, agentid, bonuslevel from ' . tablename('sz_yi_member') . ' where agentid=' . $id . ' and uniacid=' . $_W['uniacid'];
			$agents = pdo_fetchall($sql);

			foreach ($agents as $agent) {
				if ($agent['bonuslevel'] == $level_id) {
					return true;
				}

				if (0 < $agent['agentid']) {
					$this->getChildAgents_level($agent['id'], $level_id);
				}
			}
		}

		public function getParentAgents_level($agentid, $level_id)
		{
			global $_W;
			$sql = 'select id, agentid, bonuslevel from ' . tablename('sz_yi_member') . ' where agentid=' . $agentid . ' and uniacid=' . $_W['uniacid'];
			$agents = pdo_fetchall($sql);

			foreach ($agents as $agent) {
				if ($agent['bonuslevel'] == $level_id) {
					return true;
				}

				if (0 < $agent['agentid']) {
					$this->getChildAgents_level($agent['id'], $level_id);
				}
			}
		}

		public function getParentAgents($id, $isdistinction, $level = -1)
		{
			global $_W;
			$sql = 'select id, agentid, bonuslevel, bonus_status, isagency from ' . tablename('sz_yi_member') . ' where id=' . $id . ' and uniacid=' . $_W['uniacid'];
			$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_ = pdo_fetch($sql);

			if (empty($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_)) {
				return $this->parentAgents;
			}

			if (!empty($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel'])) {
				if ($isdistinction == 0) {
					$agentlevel = pdo_fetchcolumn('select level from ' . tablename('sz_yi_bonus_level') . ' where id=' . $_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']);
					if (empty($this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']]) && ($level < $agentlevel)) {
						$level = $agentlevel;
						$this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']] = $_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['id'];
						if (p('love') && ($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['isagency'] < 2)) {
							unset($this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']]);
						}
					}
				}
				else {
					if (empty($this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']])) {
						$this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']] = $_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['id'];
						if (p('love') && ($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['isagency'] < 2)) {
							unset($this->parentAgents[$_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['bonuslevel']]);
						}
					}
				}
			}

			if ($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['agentid'] != 0) {
				return $this->getParentAgents($_obf_DQg8ByUjXAIoHiwbOzUrHjADBTssCSI_['agentid'], $isdistinction, $level);
			}

			return $this->parentAgents;
		}

		public function calculate($orderid = 0, $update = true)
		{
			global $_W;
			$set = $this->getSet();
			$levels = $this->getLevels();
			$time = time();
			$order = pdo_fetch('select openid, address from ' . tablename('sz_yi_order') . ' where id=:id limit 1', array(':id' => $orderid));
			$openid = $order['openid'];
			$address = unserialize($order['address']);
			$goods = pdo_fetchall('select og.id,og.realprice,og.price,og.goodsid,og.total,og.optionname,og.optionid,g.hascommission,g.nocommission,g.nobonus,g.bonusmoney,g.productprice,g.marketprice,g.costprice from ' . tablename('sz_yi_order_goods') . '  og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id = og.goodsid' . ' where og.orderid=:orderid and og.uniacid=:uniacid', array(':orderid' => $orderid, ':uniacid' => $_W['uniacid']));
			$member = m('member')->getInfo($openid);
			$levels = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_bonus_level') . ' WHERE uniacid = \'' . $_W['uniacid'] . '\' ORDER BY level asc');
			$isdistinction = (empty($set['isdistinction']) ? 0 : 1);

			foreach ($goods as $cinfo) {
				$price_all = $this->calculate_method($cinfo);
				if (empty($cinfo['nobonus']) && (0 < $price_all)) {
					if (empty($set['selfbuy'])) {
						$_obf_DQYvBxoUORcvMzcYBCk5EQQvKzMBIzI_ = $member['agentid'];
					}
					else {
						$_obf_DQYvBxoUORcvMzcYBCk5EQQvKzMBIzI_ = $member['id'];
					}

					if (!empty($_obf_DQYvBxoUORcvMzcYBCk5EQQvKzMBIzI_) && !empty($set['start'])) {
						$parentAgents = $this->getParentAgents($_obf_DQYvBxoUORcvMzcYBCk5EQQvKzMBIzI_, $isdistinction);
						$_obf_DRM5JFspEyk1HhgaGxU9EjwYJTg_PxE_ = 0;

						foreach ($levels as $key => $level) {
							$levelid = $level['id'];

							if (array_key_exists($levelid, $parentAgents)) {
								if (0 < $level['agent_money']) {
									$setmoney = $level['agent_money'] / 100;
								}
								else {
									continue;
								}

								$_obf_DSMVChkDHjYxJT9cFxs9CAI1IhcLKSI_ = round($price_all * $setmoney, 2);

								if ($isdistinction == 0) {
									$bonus_money = $_obf_DSMVChkDHjYxJT9cFxs9CAI1IhcLKSI_ - $_obf_DRM5JFspEyk1HhgaGxU9EjwYJTg_PxE_;
									$_obf_DRM5JFspEyk1HhgaGxU9EjwYJTg_PxE_ = $_obf_DSMVChkDHjYxJT9cFxs9CAI1IhcLKSI_;
								}
								else {
									$bonus_money = $_obf_DSMVChkDHjYxJT9cFxs9CAI1IhcLKSI_;
								}

								if ($bonus_money <= 0) {
									continue;
								}

								$data = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $cinfo['goodsid'], 'orderid' => $orderid, 'total' => $cinfo['total'], 'optionname' => $cinfo['optionname'], 'mid' => $parentAgents[$levelid], 'levelid' => $levelid, 'money' => $bonus_money, 'createtime' => $time);
								pdo_insert('sz_yi_bonus_goods', $data);
							}
						}
					}

					$_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_ = 0;

					if (!empty($set['area_start'])) {
						$bonus_commission4 = floatval($set['bonus_commission4']);

						if (!empty($bonus_commission4)) {
							$_obf_DUALKCEYNTQ1PgMTIz03EVwvIyMxGDI_ = pdo_fetchall('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $address['province'] . '\' and bonus_city=\'' . $address['city'] . '\' and bonus_district=\'' . $address['area'] . '\' and bonus_street=\'' . $address['street'] . '\' and bonus_area=4 and uniacid=' . $_W['uniacid']);

							if (!empty($_obf_DUALKCEYNTQ1PgMTIz03EVwvIyMxGDI_)) {
								foreach ($_obf_DUALKCEYNTQ1PgMTIz03EVwvIyMxGDI_ as $key => $agent_street) {
									if (0 < $agent_street['bonus_area_commission']) {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $agent_street['bonus_area_commission']) / 100, 2);
									}
									else {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $set['bonus_commission3']) / 100, 2);
									}

									if (empty($set['isdistinction_area'])) {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ - $_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_;
										$_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_ = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}
									else {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}

									if (0 < $bonus_area_money) {
										$data = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $cinfo['goodsid'], 'orderid' => $orderid, 'total' => $cinfo['total'], 'optionname' => $cinfo['optionname'], 'mid' => $agent_street['id'], 'bonus_area' => 4, 'money' => $bonus_area_money, 'createtime' => $time);
									}

									pdo_insert('sz_yi_bonus_goods', $data);
									if (empty($set['isdistinction_area']) && empty($set['isdistinction_area_all'])) {
										break;
									}
								}
							}
						}

						$bonus_commission3 = floatval($set['bonus_commission3']);

						if (!empty($bonus_commission3)) {
							$_obf_DTg2BDwODSIHHBYRFgwsCzMNFhwjPCI_ = pdo_fetchall('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $address['province'] . '\' and bonus_city=\'' . $address['city'] . '\' and bonus_district=\'' . $address['area'] . '\' and bonus_area=3 and uniacid=' . $_W['uniacid']);

							if (!empty($_obf_DTg2BDwODSIHHBYRFgwsCzMNFhwjPCI_)) {
								foreach ($_obf_DTg2BDwODSIHHBYRFgwsCzMNFhwjPCI_ as $key => $agent_district) {
									if (0 < $agent_district['bonus_area_commission']) {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $agent_district['bonus_area_commission']) / 100, 2);
									}
									else {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $set['bonus_commission3']) / 100, 2);
									}

									if (empty($set['isdistinction_area'])) {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ - $_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_;
										$_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_ = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}
									else {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}

									if (0 < $bonus_area_money) {
										$data = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $cinfo['goodsid'], 'orderid' => $orderid, 'total' => $cinfo['total'], 'optionname' => $cinfo['optionname'], 'mid' => $agent_district['id'], 'bonus_area' => 3, 'money' => $bonus_area_money, 'createtime' => $time);
									}

									pdo_insert('sz_yi_bonus_goods', $data);
									if (empty($set['isdistinction_area']) && empty($set['isdistinction_area_all'])) {
										break;
									}
								}
							}
						}

						$bonus_commission2 = floatval($set['bonus_commission2']);

						if (!empty($bonus_commission2)) {
							$_obf_DRg9IxMpCgouNjVbJChAHRMJMjUyHgE_ = pdo_fetchall('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $address['province'] . '\' and bonus_city=\'' . $address['city'] . '\' and bonus_area=2 and uniacid=' . $_W['uniacid']);

							if (!empty($_obf_DRg9IxMpCgouNjVbJChAHRMJMjUyHgE_)) {
								foreach ($_obf_DRg9IxMpCgouNjVbJChAHRMJMjUyHgE_ as $key => $agent_city) {
									if (0 < $agent_city['bonus_area_commission']) {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $agent_city['bonus_area_commission']) / 100, 2);
									}
									else {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $set['bonus_commission2']) / 100, 2);
									}

									if (empty($set['isdistinction_area'])) {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ - $_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_;
										$_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_ = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}
									else {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}

									if (0 < $bonus_area_money) {
										$data = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $cinfo['goodsid'], 'orderid' => $orderid, 'total' => $cinfo['total'], 'optionname' => $cinfo['optionname'], 'mid' => $agent_city['id'], 'bonus_area' => 2, 'money' => $bonus_area_money, 'createtime' => $time);
										pdo_insert('sz_yi_bonus_goods', $data);
									}

									if (empty($set['isdistinction_area']) && empty($set['isdistinction_area_all'])) {
										break;
									}
								}
							}
						}

						$bonus_commission1 = floatval($set['bonus_commission1']);

						if (!empty($bonus_commission1)) {
							$_obf_DSkWBwwIDAofCgwnCww2MxclNxoLARE_ = pdo_fetchall('select id, bonus_area_commission from ' . tablename('sz_yi_member') . ' where bonus_province=\'' . $address['province'] . '\' and bonus_area=1 and uniacid=' . $_W['uniacid']);

							if (!empty($_obf_DSkWBwwIDAofCgwnCww2MxclNxoLARE_)) {
								foreach ($_obf_DSkWBwwIDAofCgwnCww2MxclNxoLARE_ as $key => $agent_province) {
									if (0 < $agent_province['bonus_area_commission']) {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $agent_province['bonus_area_commission']) / 100, 2);
									}
									else {
										$_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ = round(($price_all * $set['bonus_commission1']) / 100, 2);
									}

									if (empty($set['isdistinction_area'])) {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_ - $_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_;
										$_obf_DT8VNFsyNxgUKx8nLCoGCBwVOUALLAE_ = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}
									else {
										$bonus_area_money = $_obf_DQMaJSIDKAQ_JxEMJhUqHQ4xHS9AGAE_;
									}

									if (0 < $bonus_area_money) {
										$data = array('uniacid' => $_W['uniacid'], 'ordergoodid' => $cinfo['goodsid'], 'orderid' => $orderid, 'total' => $cinfo['total'], 'optionname' => $cinfo['optionname'], 'mid' => $agent_province['id'], 'bonus_area' => 1, 'money' => $bonus_area_money, 'createtime' => $time);
										pdo_insert('sz_yi_bonus_goods', $data);
									}

									if (empty($set['isdistinction_area']) && empty($set['isdistinction_area_all'])) {
										break;
									}
								}
							}
						}
					}
				}
			}
		}

		public function calculate_method($order_goods)
		{
			global $_W;
			$set = $this->getSet();
			$realprice = $order_goods['realprice'];

			if (empty($set['culate_method'])) {
				return (0 < $order_goods['bonusmoney']) && !empty($order_goods['bonusmoney']) ? $order_goods['bonusmoney'] * $order_goods['total'] : $order_goods['price'];
			}

			if ($order_goods['optionid'] != 0) {
				$option = pdo_fetch('select productprice,marketprice,costprice from ' . tablename('sz_yi_goods_option') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $order_goods['optionid'], ':uniacid' => $_W['uniacid']));
				$productprice = $option['productprice'] * $order_goods['total'];
				$marketprice = $option['marketprice'] * $order_goods['total'];
				$costprice = $option['costprice'] * $order_goods['total'];
			}
			else {
				$productprice = $order_goods['productprice'] * $order_goods['total'];
				$marketprice = $order_goods['marketprice'] * $order_goods['total'];
				$costprice = $order_goods['costprice'] * $order_goods['total'];
			}

			if ($set['culate_method'] == 1) {
				return $realprice;
			}

			if ($set['culate_method'] == 2) {
				return $productprice;
			}

			if ($set['culate_method'] == 3) {
				return $marketprice;
			}

			if ($set['culate_method'] == 4) {
				return $costprice;
			}

			if ($set['culate_method'] == 5) {
				$price = $realprice - $costprice;
				return 0 < $price ? $price : 0;
			}
		}

		public function getChildAgents($id)
		{
			global $_W;
			$sql = 'select id from ' . tablename('sz_yi_member') . ' where agentid=' . $id . ' and id!=' . $id . ' and status=1 and isagent = 1 and uniacid=' . $_W['uniacid'];
			$agents = pdo_fetchall($sql);

			foreach ($agents as $agent) {
				$this->agents[] = $agent['id'];
				$this->getChildAgents($agent['id']);
			}

			return $this->agents;
		}

		public function getLevels($all = true)
		{
			global $_W;

			if ($all) {
				return pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid order by level asc', array(':uniacid' => $_W['uniacid']));
			}

			return pdo_fetchall('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and (ordermoney>0 or commissionmoney>0) order by level asc', array(':uniacid' => $_W['uniacid']));
		}

		public function getInfo($openid, $options = NULL)
		{
			if (empty($options) || !is_array($options)) {
				$options = array();
			}

			global $_W;
			$set = $this->getSet();
			$member = m('member')->getInfo($openid);

			if (empty($member['id'])) {
				return false;
			}

			$commission_total = 0;
			$commission_ok = 0;
			$commission_teamok = 0;
			$commission_areaok = 0;
			$commission_apply = 0;
			$commission_check = 0;
			$commission_lock = 0;
			$commission_pay = 0;
			$commission_totaly = 0;
			$commission_totaly_area = 0;
			$ordercount_area = 0;
			$myordermoney = 0;
			$myordercount = 0;
			$agentid = $member['id'];
			$time = time();
			$day_times = intval($set['settledays']) * 3600 * 24;
			$this->agents = array();

			if (in_array('totaly', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=0  and o.status<>4 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and cg.bonus_area = 0';
				$commission_totaly = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('totaly_area', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=0 and o.status<>4  and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and cg.bonus_area!=0';
				$commission_totaly_area = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('ok', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4  and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime > ' . $day_times . ') ORDER BY o.createtime DESC,o.status DESC';
				$commission_ok = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('teamok', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4  and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime > ' . $day_times . ')  and cg.bonus_area=0 ORDER BY o.createtime DESC,o.status DESC';
				$commission_teamok = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('areaok', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4  and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime > ' . $day_times . ')  and cg.bonus_area!=0 ORDER BY o.createtime DESC,o.status DESC';
				$commission_areaok = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('total', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where o.status>=1 and o.status<>4 and o.uniacid=:uniacid and cg.mid = ' . $agentid . ' ORDER BY o.createtime DESC,o.status DESC';
				$commission_total = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid']));
			}

			if (in_array('ordercount', $options)) {
				$ordercount = pdo_fetchcolumn('select count(distinct o.id) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_bonus_goods') . ' cg on cg.orderid=o.id  where o.status>=0  and o.status<>4 and o.status<>5 and cg.status>=0 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid =' . $agentid . ' and cg.bonus_area=0 limit 1');
			}

			if (in_array('ordercount_area', $options)) {
				$ordercount_area = pdo_fetchcolumn('select count(distinct o.id) as ordercount_area from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_bonus_goods') . ' cg on cg.orderid=o.id  where o.status>=0 and o.status<>4 and o.status<>5 and cg.status>=0 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid =' . $agentid . ' and cg.bonus_area!=0 limit 1');
			}

			if (in_array('apply', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=1 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4 and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime <= ' . $day_times . ') ORDER BY o.createtime DESC,o.status DESC';
				$commission_apply = pdo_fetchcolumn($sql);
			}

			if (in_array('check', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=2 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.status<>4 and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime <= ' . $day_times . ') ORDER BY o.createtime DESC,o.status DESC';
				$commission_check = pdo_fetchcolumn($sql);
			}

			if (in_array('pay', $options)) {
				$sql = 'select sum(money) from ' . tablename('sz_yi_bonus_log') . ' where sendpay=1 and uniacid=:uniacid and openid =:openid ';
				$commission_pay = pdo_fetchcolumn($sql, array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
			}

			if (in_array('lock', $options)) {
				$sql = 'select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=1 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.status<>4 and o.status<>5 and o.status<>6 and o.uniacid=' . $_W['uniacid'] . ' and cg.mid = ' . $agentid . ' and (' . $time . ' - o.finishtime <= ' . $day_times . ') ORDER BY o.createtime DESC,o.status DESC';
				$commission_lock = pdo_fetchcolumn($sql);
			}

			if (in_array('myorder', $options)) {
				$myorder = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));
				$myordermoney = $myorder['ordermoney'];
				$myordercount = $myorder['ordercount'];
			}

			$agentids = $this->getChildAgents($member['id']);
			$agentcount = count($agentids);
			$member['commission_ok'] = isset($commission_ok) ? $commission_ok : 0;
			$member['commission_teamok'] = isset($commission_teamok) ? $commission_teamok : 0;
			$member['commission_areaok'] = isset($commission_areaok) ? $commission_areaok : 0;
			$member['commission_total'] = isset($commission_total) ? $commission_total : 0;
			$member['commission_pay'] = isset($commission_pay) ? $commission_pay : 0;
			$member['commission_apply'] = isset($commission_apply) ? $commission_apply : 0;
			$member['commission_check'] = isset($commission_check) ? $commission_check : 0;
			$member['commission_lock'] = isset($commission_lock) ? $commission_lock : 0;
			$member['commission_totaly'] = isset($commission_totaly) ? $commission_totaly : 0;
			$member['commission_totaly_area'] = isset($commission_totaly_area) ? $commission_totaly_area : 0;
			$member['ordercount'] = $ordercount;
			$member['ordercount_area'] = $ordercount_area;
			$member['agentcount'] = $agentcount;
			$member['agentids'] = $agentids;
			$member['myordermoney'] = $myordermoney;
			$member['myordercount'] = $myordercount;
			return $member;
		}

		public function checkOrderConfirm($orderid = '0')
		{
			global $_W;
			global $_GPC;
			$set = $this->getSet();
			if (empty($set['start']) && empty($set['area_start'])) {
				return NULL;
			}

			$this->calculate($orderid);
		}

		public function checkOrderPay($orderid = '0')
		{
			global $_W;
			global $_GPC;
			$set = $this->getSet();
			if (empty($set['start']) && empty($set['area_start'])) {
				return NULL;
			}

			$order = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime from ' . tablename('sz_yi_order') . ' where id=:id and status>=1 and uniacid=:uniacid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));

			if (empty($order)) {
				return NULL;
			}

			$openid = $order['openid'];
			$member = m('member')->getMember($openid);

			if (empty($member)) {
				return NULL;
			}

			$ordergoods = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $order['id']));
			$goods = '';
			$realprice = 0;

			foreach ($ordergoods as $item) {
				$goods .= '' . $item['title'] . '( ';

				if (!empty($item['optiontitle'])) {
					$goods .= ' 规格: ' . $item['optiontitle'];
				}

				$goods .= ' 单价: ' . ($item['realprice'] / $item['total']) . ' 数量: ' . $item['total'] . ' 总价: ' . $item['realprice'] . '); ';
				$realprice += $item['realprice'];
			}

			$_obf_DRY1LhA_ARoLDiYqCg0wDhEvJw8oPSI_ = pdo_fetchall('select distinct mid from ' . tablename('sz_yi_bonus_goods') . ' where uniacid=:uniacid and orderid=:orderid', array(':orderid' => $order['id'], ':uniacid' => $_W['uniacid']));
			$this->upgradeLevelByAgent($openid);

			foreach ($_obf_DRY1LhA_ARoLDiYqCg0wDhEvJw8oPSI_ as $key => $val) {
				$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_member') . ' where id=' . $val['mid'] . ' and uniacid=' . $_W['uniacid']);
				$agent_money = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $val['mid'] . ' and orderid=' . $order['id'] . ' and bonus_area=0 and uniacid=' . $_W['uniacid']);

				if (0 < $agent_money) {
					$this->sendMessage($openid, array('nickname' => $member['nickname'], 'ordersn' => $order['ordersn'], 'price' => $realprice, 'goods' => $goods, 'commission' => $agent_money, 'paytime' => $order['paytime']), TM_BONUS_ORDER_PAY);
				}

				$_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_ = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $val['mid'] . ' and orderid=' . $order['id'] . ' and bonus_area > 0 and uniacid=' . $_W['uniacid']);

				if (0 < $_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_) {
					$this->sendMessage($openid, array('nickname' => $member['nickname'], 'ordersn' => $order['ordersn'], 'price' => $realprice, 'goods' => $goods, 'commission' => $_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_, 'paytime' => $order['paytime']), TM_BONUS_ORDER_AREA_PAY);
				}

				$this->upgradeLevelByAgent($openid);
			}
		}

		public function checkOrderFinish($orderid = '')
		{
			global $_W;
			global $_GPC;

			if (empty($orderid)) {
				return NULL;
			}

			$set = $this->getSet();
			if (empty($set['start']) && empty($set['area_start'])) {
				return NULL;
			}

			$order = pdo_fetch('select id,openid,ordersn,goodsprice,agentid,paytime,finishtime from ' . tablename('sz_yi_order') . ' where id=:id and status>=1 and uniacid=:uniacid limit 1', array(':id' => $orderid, ':uniacid' => $_W['uniacid']));

			if (empty($order)) {
				return NULL;
			}

			$openid = $order['openid'];
			$member = m('member')->getMember($openid);

			if (empty($member)) {
				return NULL;
			}

			$ordergoods = pdo_fetchall('select g.id,g.title,og.total,og.price,og.realprice, og.optionname as optiontitle,g.noticeopenid,g.noticetype,og.commission1 from ' . tablename('sz_yi_order_goods') . ' og ' . ' left join ' . tablename('sz_yi_goods') . ' g on g.id=og.goodsid ' . ' where og.uniacid=:uniacid and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $order['id']));
			$goods = '';
			$realprice = 0;

			foreach ($ordergoods as $item) {
				$goods .= '' . $item['title'] . '( ';

				if (!empty($item['optiontitle'])) {
					$goods .= ' 规格: ' . $item['optiontitle'];
				}

				$goods .= ' 单价: ' . ($item['realprice'] / $item['total']) . ' 数量: ' . $item['total'] . ' 总价: ' . $item['realprice'] . '); ';
				$realprice += $item['realprice'];
			}

			$_obf_DRY1LhA_ARoLDiYqCg0wDhEvJw8oPSI_ = pdo_fetchall('select distinct mid from ' . tablename('sz_yi_bonus_goods') . ' where uniacid=:uniacid and orderid=:orderid', array(':orderid' => $orderid, ':uniacid' => $_W['uniacid']));
			$this->upgradeLevelByAgent($openid);

			foreach ($_obf_DRY1LhA_ARoLDiYqCg0wDhEvJw8oPSI_ as $key => $val) {
				$openid = pdo_fetchcolumn('select openid from ' . tablename('sz_yi_member') . ' where id=' . $val['mid'] . ' and uniacid=' . $_W['uniacid']);
				$agent_money = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $val['mid'] . ' and orderid=' . $order['id'] . ' and bonus_area=0 and uniacid=' . $_W['uniacid']);

				if (0 < $agent_money) {
					$this->sendMessage($openid, array('nickname' => $member['nickname'], 'ordersn' => $order['ordersn'], 'price' => $realprice, 'goods' => $goods, 'commission' => $agent_money, 'finishtime' => $order['finishtime']), TM_BONUS_ORDER_FINISH);
				}

				$_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_ = pdo_fetchcolumn('select sum(money) from ' . tablename('sz_yi_bonus_goods') . ' where mid=' . $val['mid'] . ' and orderid=' . $order['id'] . ' and bonus_area > 0 and uniacid=' . $_W['uniacid']);

				if (0 < $_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_) {
					$this->sendMessage($openid, array('nickname' => $member['nickname'], 'ordersn' => $order['ordersn'], 'price' => $realprice, 'goods' => $goods, 'commission' => $_obf_DT45XDE9NTU7Nho2Ki4DOBYWDw8LISI_, 'finishtime' => $order['finishtime']), TM_BONUS_ORDER_AREA_FINISH);
				}

				$this->upgradeLevelByAgent($openid);
			}
		}

		public function getLevel($openid)
		{
			global $_W;

			if (empty($openid)) {
				return false;
			}

			$member = m('member')->getMember($openid);

			if (empty($member['bonuslevel'])) {
				return false;
			}

			$level = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $member['bonuslevel']));
			return $level;
		}

		public function isLevel($openid)
		{
			global $_W;

			if (empty($openid)) {
				return false;
			}

			$member = m('member')->getMember($openid);

			if (empty($member['bonuslevel'])) {
				$levelid = 0;
			}
			else {
				$levelid = pdo_fetchcolumn('select id from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid and id=:id limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $member['bonuslevel']));
			}

			if (!empty($levelid) || !empty($member['bonus_area'])) {
				return true;
			}

			return false;
		}

		public function upgradeLevelByAgent($mid)
		{
			global $_W;

			if (empty($mid)) {
				return false;
			}

			$set = $this->getSet();
			$member = p('commission')->getInfo($mid, array('ordercount0'));

			if (empty($member)) {
				return NULL;
			}

			if (empty($member['bonuslevel'])) {
				$oldlevel = false;
				$levelup = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where uniacid=' . $_W['uniacid'] . ' order by level asc');
			}
			else {
				$oldlevel = $this->getLevel($member['openid']);
				$_obf_DQIDCgYtCCICKSkMEj8_PCoBEC4yBiI_ = pdo_fetchcolumn('select level from ' . tablename('sz_yi_bonus_level') . ' where  uniacid=:uniacid and id=:bonuslevel order by level asc', array(':uniacid' => $_W['uniacid'], ':bonuslevel' => $member['bonuslevel']));
				$levelup = pdo_fetch('select * from ' . tablename('sz_yi_bonus_level') . ' where  uniacid=:uniacid and level>:levelby order by level asc', array(':uniacid' => $_W['uniacid'], ':levelby' => $_obf_DQIDCgYtCCICKSkMEj8_PCoBEC4yBiI_));
			}

			if (empty($levelup) || ($levelup['status'] == 1)) {
				return false;
			}

			$leveltype = $set['leveltype'];
			$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = true;

			if (in_array('4', $leveltype)) {
				$myprice = pdo_fetchcolumn('select sum(price) from ' . tablename('sz_yi_order') . ' where openid=:openid and status>=3 and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $member['openid']));

				if (0 < $levelup['ordermoney']) {
					if ($myprice < $levelup['ordermoney']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if (in_array('8', $leveltype)) {
				if (!empty($levelup['downcount'])) {
					if ($member['agentcount'] < $levelup['downcount']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if (in_array('9', $leveltype)) {
				if (!empty($levelup['downcountlevel1'])) {
					if ($member['level1'] < $levelup['downcountlevel1']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if (in_array('12', $leveltype)) {
				if (!empty($levelup['downcountlevel2'])) {
					if ($member['level2'] < $levelup['downcountlevel2']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if (in_array('13', $leveltype)) {
				if (!empty($levelup['downcountlevel3'])) {
					if ($member['level3'] < $levelup['downcountlevel3']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if (in_array('11', $leveltype)) {
				if (0 < $levelup['commissionmoney']) {
					if ($member['ordermoney0'] < $levelup['commissionmoney']) {
						$_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ = false;
					}
				}
			}

			if ($_obf_DTE9FA0mBzBAXB8oGS48PDcOGDM3GBE_ == true) {
				pdo_update('sz_yi_member', array('bonuslevel' => $levelup['id'], 'bonus_status' => １), array('id' => $member['id']));
				$_obf_DS4ICzw9NwYTNAcGBlweHicxHyQGKyI_ = $this->upgradeLevelByAgent($member['id']);

				if ($_obf_DS4ICzw9NwYTNAcGBlweHicxHyQGKyI_ == false) {
					$this->sendMessage($member['openid'], array('nickname' => $member['nickname'], 'oldlevel' => $oldlevel, 'newlevel' => $levelup), TM_BONUS_UPGRADE);
				}

				return true;
			}

			return false;
		}

		public function sendMessage($openid = '', $data = array(), $message_type = '')
		{
			global $_W;
			global $_GPC;
			$set = $this->getSet();
			$tm = $set['tm'];
			$templateid = $tm['templateid'];
			$member = m('member')->getMember($openid);
			$usernotice = unserialize($member['noticeset']);

			if (!is_array($usernotice)) {
				$usernotice = array();
			}

			if (($message_type == TM_COMMISSION_AGENT_NEW) && !empty($tm['commission_agent_new']) && empty($usernotice['commission_agent_new'])) {
				$message = $tm['commission_agent_new'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['childtime']), $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['commission_agent_newtitle']) ? $tm['commission_agent_newtitle'] : '新增下线通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_ORDER_PAY) && !empty($tm['bonus_order_pay']) && empty($usernotice['bonus_order_pay'])) {
				$message = $tm['bonus_order_pay'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['paytime']), $message);
				$message = str_replace('[订单编号]', $data['ordersn'], $message);
				$message = str_replace('[订单金额]', $data['price'], $message);
				$message = str_replace('[分红金额]', $data['commission'], $message);
				$message = str_replace('[商品详情]', $data['goods'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_order_paytitle']) ? $tm['bonus_order_paytitle'] : '股权代理下级付款通知"'),
					'keyword2' => array('value' => $message)
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_ORDER_FINISH) && !empty($tm['bonus_order_finish']) && empty($usernotice['bonus_order_finish'])) {
				$message = $tm['bonus_order_finish'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['finishtime']), $message);
				$message = str_replace('[订单编号]', $data['ordersn'], $message);
				$message = str_replace('[订单金额]', $data['price'], $message);
				$message = str_replace('[分红金额]', $data['commission'], $message);
				$message = str_replace('[商品详情]', $data['goods'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_order_finishtitle']) ? $tm['bonus_order_finishtitle'] : '股权代理下级确认收货通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_ORDER_AREA_PAY) && !empty($tm['bonus_order_area_pay']) && empty($usernotice['bonus_order_area_pay'])) {
				$message = $tm['bonus_order_area_pay'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['paytime']), $message);
				$message = str_replace('[订单编号]', $data['ordersn'], $message);
				$message = str_replace('[订单金额]', $data['price'], $message);
				$message = str_replace('[分红金额]', $data['commission'], $message);
				$message = str_replace('[商品详情]', $data['goods'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_order_area_paytitle']) ? $tm['bonus_order_area_paytitle'] : '区域代理下级付款通知"'),
					'keyword2' => array('value' => $message)
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_ORDER_AREA_FINISH) && !empty($tm['bonus_order_area_finish']) && empty($usernotice['bonus_order_area_finish'])) {
				$message = $tm['bonus_order_area_finish'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['finishtime']), $message);
				$message = str_replace('[订单编号]', $data['ordersn'], $message);
				$message = str_replace('[订单金额]', $data['price'], $message);
				$message = str_replace('[分红金额]', $data['commission'], $message);
				$message = str_replace('[商品详情]', $data['goods'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_order_area_finishtitle']) ? $tm['bonus_order_area_finishtitle'] : '区域代理下级确认收货通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_PAY) && !empty($tm['bonus_pay']) && empty($usernotice['bonus_pay'])) {
				$message = $tm['bonus_pay'];
				$message = str_replace('[昵称]', $member['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
				$message = str_replace('[金额]', $data['commission'], $message);
				$message = str_replace('[打款方式]', $data['type'], $message);
				$message = str_replace('[代理等级]', $data['levelname'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_paytitle']) ? $tm['bonus_paytitle'] : '代理分红打款通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_PAY_AREA) && !empty($tm['bonus_pay_area']) && empty($usernotice['bonus_pay_area'])) {
				$message = $tm['bonus_pay_area'];
				$message = str_replace('[昵称]', $member['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
				$message = str_replace('[金额]', $data['commission'], $message);
				$message = str_replace('[打款方式]', $data['type'], $message);
				$message = str_replace('[地区等级]', $data['levelname'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_paytitle_area']) ? $tm['bonus_paytitle_area'] : '地区分红打款通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_GLOBAL_PAY) && !empty($tm['bonus_global_pay']) && empty($usernotice['bonus_global_pay'])) {
				$message = $tm['bonus_global_pay'];
				$message = str_replace('[昵称]', $member['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
				$message = str_replace('[金额]', $data['commission'], $message);
				$message = str_replace('[打款方式]', $data['type'], $message);
				$message = str_replace('[代理等级]', $data['levelname'], $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['bonus_global_paytitle']) ? $tm['bonus_global_paytitle'] : '全球分红打款通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_BONUS_UPGRADE) && !empty($tm['bonus_upgrade']) && empty($usernotice['bonus_upgrade'])) {
				$message = $tm['bonus_upgrade'];

				if (!empty($data['newlevel']['msgcontent'])) {
					$message = $data['newlevel']['msgcontent'];
				}

				$message = str_replace('[昵称]', $member['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
				$old_level_name = ($data['oldlevel']['levelname'] ? $data['oldlevel']['levelname'] : '普通等级');
				$message = str_replace('[旧等级]', $old_level_name, $message);
				$_obf_DQY1FzsZN1szCi4CKxIhAQ0JFisUEAE_ = floatval($data['oldlevel']['agent_money']);
				$message = str_replace('[旧分红比例]', $_obf_DQY1FzsZN1szCi4CKxIhAQ0JFisUEAE_ . '%', $message);
				$message = str_replace('[新等级]', $data['newlevel']['levelname'], $message);
				$message = str_replace('[新分红比例]', $data['newlevel']['agent_money'] . '%', $message);
				$tm['bonus_upgradetitle'] = !empty($tm['bonus_upgradetitle']) ? $tm['bonus_upgradetitle'] : '代理商等级升级通知';
				$msg = array(
					'keyword1' => array('value' => !empty($data['newlevel']['msgtitle']) ? $data['newlevel']['msgtitle'] : $tm['bonus_upgradetitle'], 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
				return NULL;
			}

			if (($message_type == TM_COMMISSION_BECOME) && !empty($tm['commission_become']) && empty($usernotice['commission_become'])) {
				$message = $tm['commission_become'];
				$message = str_replace('[昵称]', $data['nickname'], $message);
				$message = str_replace('[时间]', date('Y-m-d H:i:s', $data['agenttime']), $message);
				$msg = array(
					'keyword1' => array('value' => !empty($tm['commission_becometitle']) ? $tm['commission_becometitle'] : '成为分销商通知', 'color' => '#73a68d'),
					'keyword2' => array('value' => $message, 'color' => '#73a68d')
					);

				if (!empty($templateid)) {
					m('message')->sendTplNotice($openid, $templateid, $msg);
					return NULL;
				}

				m('message')->sendCustomNotice($openid, $msg);
			}
		}

		public function perms()
		{
			return array(
	'bonus' => array(
		'text'     => $this->getName(),
		'isplugin' => true,
		'child'    => array(
			'cover'    => array('text' => '入口设置'),
			'agent'    => array('text' => '代理商管理', 'view' => '浏览', 'edit' => '修改-log', 'user' => '推广下线', 'order' => '查看推广订单(还需有订单权限)', 'goods_rank' => '推广商品', 'changeagent' => '设置代理商'),
			'level'    => array('text' => '代理商等级', 'view' => '浏览', 'add' => '添加-log', 'edit' => '修改-log', 'delete' => '删除-log'),
			'send'     => array('text' => '团队分红-log', 'view' => '浏览', 'bont' => '分红按钮'),
			'sendarea' => array('text' => '地区分红-log', 'view' => '浏览', 'bont' => '分红按钮'),
			'sendall'  => array('text' => '全球分红-log', 'view' => '浏览', 'bont' => '分红按钮'),
			'detail'   => array('text' => '分红明细-log', 'view' => '浏览', 'afresh' => '重发分红'),
			'notice'   => array('text' => '通知设置-log'),
			'set'      => array('text' => '基础设置-log')
			)
		)
	);
		}

		public function autosend($uniacid = 0, $filesn)
		{
			global $_W;
			global $_GPC;

			if (empty($uniacid)) {
				return NULL;
			}

			$_W['uniacid'] = $uniacid;
			$time = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$islog = false;
			$set = $this->getSet();
			$setshop = m('common')->getSysset('shop');
			$day_times = intval($set['settledays']) * 3600 * 24;
			$daytime = strtotime(date('Y-m-d 00:00:00'));

			if (empty($set['sendmonth'])) {
				$endtime = $daytime - $day_times;
				$sendtime = strtotime(date('Y-m-d ' . $set['senddaytime'] . ':00:00'));
			}
			else {
				if ($set['sendmonth'] == 1) {
					$now_endtime = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 1, date('Y')));
					$endtime = $now_endtime - $day_times;
					$interval_day = (empty($set['interval_day']) ? 1 : 1 + $set['interval_day']);
					$sendtime = strtotime(date('Y-' . date('m') . '-' . $interval_day . ' ' . $set['senddaytime'] . ':00:00'));
				}
			}

			if ($time < $sendtime) {
				return false;
			}

			$sql = 'select distinct cg.mid from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 left join ' . tablename('sz_yi_member') . ' m on cg.mid=m.id where 1 and m.id!=0 and o.status>=3 and o.uniacid=' . $_W['uniacid'] . ' and (' . $time . ' - o.finishtime > ' . $day_times . ') and cg.bonus_area=0 ORDER BY o.finishtime DESC,o.status DESC';
			$list = pdo_fetchall($sql);

			if (empty($list)) {
				return false;
			}

			$levelnames = pdo_fetchall('select id, levelname from ' . tablename('sz_yi_bonus_level') . ' where uniacid=:uniacid', array(':uniacid' => $_W['uniacid']), 'id');
			$totalmoney = 0;
			$send_bonus_sn = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$real_total = 0;
			$islog = false;
			$insert_log_data = array();
			$insert_log_key = 'INSERT INTO ' . tablename('sz_yi_bonus_log') . ' (openid, uid, money, uniacid, paymethod, sendpay, goodids, status, ctime, send_bonus_sn) VALUES ';
			$update_log_data = '';
			$update_log_key = 'UPDATE ' . tablename('mc_members') . ' SET credit2 = CASE uid';
			$insert_member_log_data = array();
			$insert_member_log_key = 'INSERT INTO ' . tablename('mc_credits_record') . ' (uid, credittype, uniacid, num, createtime, operator, remark) VALUES ';
			load()->model('account');

			if (!empty($_W['acid'])) {
				$account = WeAccount::create($_W['acid']);
			}
			else {
				$acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid LIMIT 1', array(':uniacid' => $_W['uniacid']));
				$account = WeAccount::create($acid);
			}

			$uids = array();
			$senddata = array();

			foreach ($list as $key => $value) {
				$member = pdo_fetch('select id, avatar, nickname, realname, mobile, openid, bonuslevel, uid from ' . tablename('sz_yi_member') . ' where id=' . $value['mid'] . ' and uniacid=' . $_W['uniacid']);

				if (!empty($member)) {
					if (0 < floatval($set['consume_withdraw'])) {
						$myorder = $this->myorder($member['openid']);

						if ($myorder['ordermoney'] < floatval($set['consume_withdraw'])) {
							unset($list[$key]);
							continue;
						}
					}

					$send_money = pdo_fetchcolumn('select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4  and o.status<>5 and o.status<>6 and o.uniacid=:uniacid and cg.mid = :mid and (' . $time . ' - o.finishtime > ' . $day_times . ')  and cg.bonus_area=0 ORDER BY o.createtime DESC,o.status DESC', array(':uniacid' => $_W['uniacid'], ':mid' => $member['id']));

					if (0 < $send_money) {
						++$sql_num;
						++$real_total;
						$totalmoney += $send_money;

						if (!empty($member['bonuslevel'])) {
							$value['levelname'] = $levelnames[$member['bonuslevel']]['levelname'];
						}
						else {
							$value['levelname'] = $set['levelname'];
						}

						$sendpay = 1;
						$islog = true;

						if (empty($set['paymethod'])) {
							if (0 < $member['uid']) {
								$uid = $member['uid'];
							}
							else {
								$uid = pdo_fetchcolumn('SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND openid=\'' . $member['openid'] . '\'');
							}

							if (!empty($uid)) {
								$update_log_data .= ' WHEN ' . $uid . ' THEN credit2+' . $send_money;
								$insert_member_log_data[] = ' (\'' . $uid . '\', \'credit2\', \'' . $_W['uniacid'] . '\', \'' . $send_money . '\', \'' . TIMESTAMP . '\', 0, \'团队分红\')';
								$uids[] = $uid;
							}
							else {
								pdo_query('update ' . tablename('sz_yi_member') . ' set credit2=credit2+' . $send_money . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $member['openid'] . '\'');
							}

							if (($sql_num % 500) == 0) {
								if (!empty($update_log_data)) {
									pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
									$update_log_data = '';
									$uids = array();
								}

								if (!empty($insert_member_log_data)) {
									pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
									$insert_member_log_data = array();
								}
							}
						}
						else {
							$logno = m('common')->createNO('bonus_log', 'logno', 'RB');
							$result = m('finance')->pay($member['openid'], 1, $send_money * 100, $logno, '【' . $setshop['name'] . '】' . $value['levelname'] . '团队分红');

							if (is_error($result)) {
								$sendpay = 0;
								$sendpay_error = 1;
							}
						}
					}

					$ids = pdo_fetchall('select cg.id from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and cg.mid=:mid and cg.status=0 and o.status>=3 and o.uniacid=:uniacid and (' . $time . ' - o.finishtime > ' . $day_times . ') and cg.bonus_area=0', array(':mid' => $member['id'], ':uniacid' => $_W['uniacid']), 'id');
					$insert_log_data[] = ' (\'' . $member['openid'] . '\', \'' . $member['uid'] . '\', \'' . $send_money . '\', \'' . $_W['uniacid'] . '\', \'' . $set['paymethod'] . '\', \'' . $sendpay . '\', \'' . iserializer($ids) . '\', 1, ' . TIMESTAMP . ', ' . $send_bonus_sn . ')';

					if (($sql_num % 500) == 0) {
						if (!empty($insert_log_data)) {
							pdo_query($insert_log_key . implode(',', $insert_log_data));
							$insert_log_data = array();
						}
					}

					pdo_query('update ' . tablename('sz_yi_bonus_goods') . ' set status=3, applytime=' . $time . ', checktime=' . $time . ', paytime=' . $time . ', invalidtime=' . $time . ' where id in( ' . implode(',', array_keys($ids)) . ') and uniacid=' . $_W['uniacid']);

					if ($sendpay == 1) {
						$templateid = $set['tm']['templateid'];
						$message = $set['tm']['bonus_pay'];
						$send_type = (empty($set['paymethod']) ? '余额' : '微信钱包');
						$message = str_replace('[昵称]', $member['nickname'], $message);
						$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
						$message = str_replace('[金额]', $send_money, $message);
						$message = str_replace('[打款方式]', $send_type, $message);
						$message = str_replace('[代理等级]', $value['levelname'], $message);
						$msg = array(
							'keyword1' => array('value' => !empty($set['tm']['bonus_paytitle']) ? $set['tm']['bonus_paytitle'] : '代理分红打款通知', 'color' => '#73a68d'),
							'keyword2' => array('value' => $message, 'color' => '#73a68d')
							);
						$senddata[] = array('openid' => $member['openid'], 'templateid' => $set['tm']['templateid'], 'msg' => $msg);
					}
				}
			}

			if (!empty($insert_log_data)) {
				pdo_query($insert_log_key . implode(',', $insert_log_data));
			}

			if (!empty($update_log_data)) {
				pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
			}

			if (!empty($insert_member_log_data)) {
				pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
			}

			if ($islog) {
				$log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 2, 'ctime' => time(), 'type' => 2, 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $real_total);
				pdo_insert('sz_yi_bonus', $log);
				plog('bonus.send', '自动发放团队分红，共计' . $real_total . '人 金额' . $totalmoney . '元');

				if ($senddata) {
					$filedata = array();
					$file_path = IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log';

					if (file_exists($file_path)) {
						$filedata = unserialize(file_get_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log'));
					}

					$data = serialize(array_merge($senddata, $filedata));
					file_put_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log', $data, FILE_APPEND);
				}
			}
		}

		public function autosendarea($uniacid = 0, $filesn)
		{
			global $_W;
			global $_GPC;

			if (empty($uniacid)) {
				return NULL;
			}

			$_W['uniacid'] = $uniacid;
			$time = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$set = $this->getSet();
			$setshop = m('common')->getSysset('shop');
			$day_times = intval($set['settledays']) * 3600 * 24;
			$daytime = strtotime(date('Y-m-d 00:00:00'));

			if (empty($set['sendmonth'])) {
				$endtime = $daytime - $day_times;
				$sendtime = strtotime(date('Y-m-d ' . $set['senddaytime'] . ':00:00'));
			}
			else {
				if ($set['sendmonth'] == 1) {
					$now_endtime = date('Y-m-d', mktime(0, 0, 0, date('m') - 1, 1, date('Y')));
					$endtime = $now_endtime - $day_times;
					$interval_day = (empty($set['interval_day']) ? 1 : 1 + $set['interval_day']);
					$sendtime = strtotime(date('Y-' . date('m') . '-' . $interval_day . ' ' . $set['senddaytime'] . ':00:00'));
				}
			}

			if ($time < $sendtime) {
				return false;
			}

			$sql = 'select distinct cg.mid from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 left join ' . tablename('sz_yi_member') . ' m on cg.mid=m.id where 1 and m.id!=0 and o.status>=3 and o.uniacid=' . $_W['uniacid'] . ' and (' . $time . ' - o.finishtime > ' . $day_times . ') and cg.bonus_area!=0 ORDER BY o.finishtime DESC,o.status DESC';
			$list = pdo_fetchall($sql);

			if (empty($list)) {
				return false;
			}

			$totalmoney = 0;
			$send_bonus_sn = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$real_total = 0;
			$islog = false;
			$insert_log_data = array();
			$insert_log_key = 'INSERT INTO ' . tablename('sz_yi_bonus_log') . ' (openid, uid, money, uniacid, paymethod, sendpay, goodids, status, ctime, send_bonus_sn) VALUES ';
			$update_log_data = '';
			$update_log_key = 'UPDATE ' . tablename('mc_members') . ' SET credit2 = CASE uid';
			$insert_member_log_data = array();
			$insert_member_log_key = 'INSERT INTO ' . tablename('mc_credits_record') . ' (uid, credittype, uniacid, num, createtime, operator, remark) VALUES ';
			load()->model('account');

			if (!empty($_W['acid'])) {
				$account = WeAccount::create($_W['acid']);
			}
			else {
				$acid = pdo_fetchcolumn('SELECT acid FROM ' . tablename('account_wechats') . ' WHERE `uniacid`=:uniacid LIMIT 1', array(':uniacid' => $_W['uniacid']));
				$account = WeAccount::create($acid);
			}

			$uids = array();
			$senddata = array();

			foreach ($list as $key => $value) {
				$member = pdo_fetch('select id, avatar, nickname, realname, mobile, openid, bonus_area, uid from ' . tablename('sz_yi_member') . ' where id=' . $value['mid'] . ' and uniacid=' . $_W['uniacid']);

				if (!empty($member)) {
					if (0 < floatval($set['consume_withdraw'])) {
						$myorder = $this->myorder($member['openid']);

						if ($myorder['ordermoney'] < floatval($set['consume_withdraw'])) {
							unset($list[$key]);
							continue;
						}
					}

					$send_money = pdo_fetchcolumn('select sum(money) as money from ' . tablename('sz_yi_order') . ' o left join  ' . tablename('sz_yi_bonus_goods') . '  cg on o.id=cg.orderid and cg.status=0 left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3  and o.status<>4  and o.status<>5 and o.status<>6 and o.uniacid=:uniacid and cg.mid = :mid and (' . $time . ' - o.finishtime > ' . $day_times . ')  and cg.bonus_area!=0 ORDER BY o.createtime DESC,o.status DESC', array(':uniacid' => $_W['uniacid'], ':mid' => $member['id']));

					if (0 < $send_money) {
						++$sql_num;
						++$real_total;
						$totalmoney += $send_money;

						if (empty($level)) {
							if ($member['bonus_area'] == 1) {
								$levelname = '省级代理';
							}
							else if ($member['bonus_area'] == 2) {
								$levelname = '市级代理';
							}
							else if ($member['bonus_area'] == 3) {
								$levelname = '区级代理';
							}
							else {
								if ($member['bonus_area'] == 4) {
									$levelname = '街级代理';
								}
							}
						}

						$sendpay = 1;
						$islog = true;

						if (empty($set['paymethod'])) {
							if (0 < $member['uid']) {
								$uid = $member['uid'];
							}
							else {
								$uid = pdo_fetchcolumn('SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND openid=\'' . $member['openid'] . '\'');
							}

							if (!empty($uid)) {
								$update_log_data .= ' WHEN ' . $uid . ' THEN credit2+' . $send_money;
								$insert_member_log_data[] = ' (\'' . $uid . '\', \'credit2\', \'' . $_W['uniacid'] . '\', \'' . $send_money . '\', \'' . TIMESTAMP . '\', 0, \'地区分红\')';
								$uids[] = $uid;
							}
							else {
								pdo_query('update ' . tablename('sz_yi_member') . ' set credit2=credit2+' . $send_money . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $member['openid'] . '\'');
							}

							if (($sql_num % 500) == 0) {
								if (!empty($update_log_data)) {
									pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
									$update_log_data = '';
									$uids = array();
								}

								if (!empty($insert_member_log_data)) {
									pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
									$insert_member_log_data = array();
								}
							}
						}
						else {
							$logno = m('common')->createNO('bonus_log', 'logno', 'RB');
							$result = m('finance')->pay($member['openid'], 1, $send_money * 100, $logno, '【' . $setshop['name'] . '】' . $levelname . '地区分红');

							if (is_error($result)) {
								$sendpay = 0;
								$sendpay_error = 1;
							}
						}
					}

					$ids = pdo_fetchall('select cg.id from ' . tablename('sz_yi_bonus_goods') . ' cg left join  ' . tablename('sz_yi_order') . '  o on o.id=cg.orderid left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and cg.mid=:mid and cg.status=0 and o.status>=3 and o.uniacid=:uniacid and (' . $time . ' - o.finishtime > ' . $day_times . ') and cg.bonus_area!=0', array(':mid' => $member['id'], ':uniacid' => $_W['uniacid']), 'id');
					$insert_log_data[] = ' (\'' . $member['openid'] . '\', \'' . $member['uid'] . '\', \'' . $send_money . '\', \'' . $_W['uniacid'] . '\', \'' . $set['paymethod'] . '\', \'' . $sendpay . '\', \'' . iserializer($ids) . '\', 1, ' . TIMESTAMP . ', ' . $send_bonus_sn . ')';

					if (($sql_num % 500) == 0) {
						if (!empty($insert_log_data)) {
							pdo_query($insert_log_key . implode(',', $insert_log_data));
							$insert_log_data = array();
						}
					}

					pdo_query('update ' . tablename('sz_yi_bonus_goods') . ' set status=3, applytime=' . $time . ', checktime=' . $time . ', paytime=' . $time . ', invalidtime=' . $time . ' where id in( ' . implode(',', array_keys($ids)) . ') and uniacid=' . $_W['uniacid']);

					if ($sendpay == 1) {
						$templateid = $set['tm']['templateid'];
						$message = $set['tm']['bonus_pay'];
						$send_type = (empty($set['paymethod']) ? '余额' : '微信钱包');
						$message = $set['tm']['bonus_pay_area'];
						$message = str_replace('[昵称]', $member['nickname'], $message);
						$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
						$message = str_replace('[金额]', $send_money, $message);
						$message = str_replace('[打款方式]', $send_type, $message);
						$message = str_replace('[地区等级]', $levelname, $message);
						$msg = array(
							'keyword1' => array('value' => !empty($set['tm']['bonus_paytitle_area']) ? $set['tm']['bonus_paytitle_area'] : '地区分红打款通知', 'color' => '#73a68d'),
							'keyword2' => array('value' => $message, 'color' => '#73a68d')
							);
						$senddata[] = array('openid' => $member['openid'], 'templateid' => $set['tm']['templateid'], 'msg' => $msg);
					}
				}
			}

			if (!empty($insert_log_data)) {
				pdo_query($insert_log_key . implode(',', $insert_log_data));
			}

			if (!empty($update_log_data)) {
				pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
			}

			if (!empty($insert_member_log_data)) {
				pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
			}

			if ($islog) {
				$log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 2, 'ctime' => time(), 'type' => 3, 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $real_total);
				pdo_insert('sz_yi_bonus', $log);
				plog('bonus.sendarea', '自动发放地区分红，共计' . $real_total . '人 金额' . $totalmoney . '元');

				if ($senddata) {
					$filedata = array();
					$file_path = IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log';

					if (file_exists($file_path)) {
						$filedata = unserialize(file_get_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log'));
					}

					$data = serialize(array_merge($senddata, $filedata));
					file_put_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log', $data, FILE_APPEND);
				}
			}
		}

		public function autosendall($uniacid = 0, $filesn)
		{
			global $_W;
			global $_GPC;

			if (empty($uniacid)) {
				return NULL;
			}

			$_W['uniacid'] = $uniacid;
			$time = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$totalmoney = 0;
			$islog = false;
			$set = $this->getSet();
			$setshop = m('common')->getSysset('shop');
			$day_times = intval($set['settledays']) * 3600 * 24;
			$daytime = strtotime(date('Y-m-d 00:00:00'));

			if (empty($set['sendmonth'])) {
				$stattime = $daytime - $day_times - 86400;
				$endtime = $daytime - $day_times;
				$sendtime = strtotime(date('Y-m-d ' . $set['senddaytime'] . ':00:00'));
			}
			else {
				if ($set['sendmonth'] == 1) {
					$now_stattime = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));
					$stattime = $now_stattime - $day_times;
					$now_endtime = mktime(0, 0, 0, date('m'), 1, date('Y'));
					$endtime = $now_endtime - $day_times;
					$_obf_DQI2NT8GNTU3KRNbWxQ8GyIaGzADLgE_ = (empty($set['interval_day']) ? 1 : 1 + $set['interval_day']);
					$sendtime = strtotime(date('Y-' . date('m') . '-' . $interval_day . ' ' . $set['senddaytime'] . ':00:00'));
				}
			}

			if ($time < $sendtime) {
				return false;
			}

			$ordermoney = pdo_fetchcolumn('select sum(o.price) from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid=' . $_W['uniacid'] . ' and  o.finishtime >=' . $stattime . ' and o.finishtime < ' . $endtime);
			$premierlevels = pdo_fetchall('select id, pcommission, levelname from ' . tablename('sz_yi_bonus_level') . ' where uniacid=' . $_W['uniacid'] . ' and premier=1');
			$leveldcounts = pdo_fetchall('select count(*) as levelnum, bonuslevel from ' . tablename('sz_yi_member') . ' where uniacid=:uniacid and bonuslevel!=0 GROUP BY bonuslevel', array(':uniacid' => $_W['uniacid']), 'bonuslevel');
			$levelmoneys = array();
			$totalmoney = 0;
			$levelnames = array();

			foreach ($premierlevels as $key => $value) {
				$leveldcount = $leveldcounts[$value['id']]['levelnum'];

				if (0 < $leveldcount) {
					$levelnames[$value['id']] = $value['levelname'];
					$levelmembermoney = round(($ordermoney * $value['pcommission']) / 100, 2);

					if (0 < $levelmembermoney) {
						$membermoney = round($levelmembermoney / $leveldcount, 2);

						if (0 < $membermoney) {
							$levelmoneys[$value['id']] = $membermoney;
							$totalmoney += $levelmembermoney;
						}
					}
				}
			}

			unset($value);

			if (!empty($levelnames)) {
				$where_uid = implode(',', array_keys($levelnames));
				$total = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_member') . ' where uniacid=' . $_W['uniacid'] . ' and bonuslevel in(' . $where_uid . ')');
				$sql = 'select id, openid, bonuslevel, uid, nickname from ' . tablename('sz_yi_member') . ' where uniacid=' . $_W['uniacid'] . ' and bonuslevel in(' . $where_uid . ')';
				$list = pdo_fetchall($sql);
			}
			else {
				$list = array();
			}

			if ($totalmoney <= 0) {
				return false;
			}

			$send_bonus_sn = time();
			$sendpay_error = 0;
			$bonus_money = 0;
			$member_logs = array();
			$uids = array();
			$sql_num = 0;
			$insert_log_data = array();
			$insert_log_key = 'INSERT INTO ' . tablename('sz_yi_bonus_log') . ' (openid, uid, money, uniacid, paymethod, sendpay, isglobal, status, ctime, send_bonus_sn) VALUES ';
			$insert_member_log_data = array();
			$insert_member_log_key = 'INSERT INTO ' . tablename('mc_credits_record') . ' (uid, credittype, uniacid, num, createtime, operator, remark) VALUES ';
			$update_log_data = '';
			$update_log_key = 'UPDATE ' . tablename('mc_members') . ' SET credit2 = CASE uid';
			$paymethod = (empty($set['paymethod']) ? 0 : 1);
			$senddata = array();
			$send_title = (!empty($set['tm']['bonus_global_paytitle']) ? $set['tm']['bonus_global_paytitle'] : '全球分红打款通知');

			foreach ($list as $key => $value) {
				$send_money = $levelmoneys[$value['bonuslevel']];
				$levelname = $levelnames[$value['bonuslevel']];
				++$sql_num;

				if (!empty($set['consume_withdraw'])) {
					$myorder = $this->myorder($value['opneid']);

					if ($myorder['ordermoney'] < floatval($set['consume_withdraw'])) {
						$totalmoney -= $send_money;
						continue;
					}
				}

				if ($send_money <= 0) {
					continue;
				}

				$islog = true;
				$sendpay = 1;

				if ($paymethod == 0) {
					if (0 < $value['uid']) {
						$uid = $value['uid'];
					}
					else {
						$uid = pdo_fetchcolumn('SELECT uid FROM ' . tablename('mc_mapping_fans') . ' WHERE uniacid=' . $_W['uniacid'] . ' AND openid=\'' . $value['openid'] . '\'');
					}

					if (!empty($uid)) {
						$update_log_data .= ' WHEN ' . $uid . ' THEN credit2+' . $send_money;
						$insert_member_log_data[] = ' (\'' . $uid . '\', \'credit2\', \'' . $_W['uniacid'] . '\', \'' . $send_money . '\', \'' . TIMESTAMP . '\', 0, \'全球分红\')';
						$uids[] = $uid;
					}
					else {
						pdo_query('update ' . tablename('sz_yi_member') . ' set credit2=credit2+' . $send_money . ' where uniacid=' . $_W['uniacid'] . ' and openid=\'' . $value['openid'] . '\'');
					}

					if (($sql_num % 500) == 0) {
						if (!empty($update_log_data)) {
							pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
							$update_log_data = '';
							$uids = array();
						}

						if (!empty($insert_member_log_data)) {
							pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
							$insert_member_log_data = array();
						}
					}
				}
				else {
					$logno = m('common')->createNO('bonus_log', 'logno', 'RB');
					$result = m('finance')->pay($value['openid'], 1, $send_money * 100, $logno, '【' . $setshop['name'] . '】' . $levelname . '全球分红');

					if (is_error($result)) {
						$sendpay = 0;
						$sendpay_error = 1;
					}
				}

				$insert_log_data[] = ' (\'' . $value['openid'] . '\', ' . $value['uid'] . ', \'' . $send_money . '\', ' . $_W['uniacid'] . ', ' . $paymethod . ', ' . $sendpay . ', 1, 1, ' . TIMESTAMP . ', ' . $send_bonus_sn . ')';

				if (($sql_num % 500) == 0) {
					if (!empty($insert_log_data)) {
						pdo_query($insert_log_key . implode(',', $insert_log_data));
						$insert_log_data = array();
					}
				}

				if ($sendpay == 1) {
					$templateid = $set['tm']['templateid'];
					$send_type = (empty($set['paymethod']) ? '余额' : '微信钱包');
					$message = $set['tm']['bonus_global_pay'];
					$message = str_replace('[昵称]', $value['nickname'], $message);
					$message = str_replace('[时间]', date('Y-m-d H:i:s', time()), $message);
					$message = str_replace('[金额]', $send_money, $message);
					$message = str_replace('[打款方式]', $send_type, $message);
					$message = str_replace('[代理等级]', $levelname, $message);
					$msg = array(
						'keyword1' => array('value' => !empty($set['tm']['bonus_global_paytitle']) ? $set['tm']['bonus_global_paytitle'] : '全球分红打款通知', 'color' => '#73a68d'),
						'keyword2' => array('value' => $message, 'color' => '#73a68d')
						);
					$senddata[] = array('openid' => $value['openid'], 'templateid' => $set['tm']['templateid'], 'msg' => $msg);
				}
			}

			if (!empty($insert_log_data)) {
				pdo_query($insert_log_key . implode(',', $insert_log_data));
			}

			if (!empty($update_log_data)) {
				pdo_query($update_log_key . $update_log_data . ' END WHERE uid IN (' . implode(',', $uids) . ')');
			}

			if (!empty($insert_member_log_data)) {
				pdo_query($insert_member_log_key . implode(',', $insert_member_log_data));
			}

			if ($islog) {
				$orderids = pdo_fetchall('select o.id from ' . tablename('sz_yi_order') . ' o left join ' . tablename('sz_yi_order_refund') . ' r on r.orderid=o.id and ifnull(r.status,-1)<>-1 where 1 and o.status>=3 and o.uniacid=:uniacid and  o.finishtime >=' . $stattime . ' and o.finishtime < ' . $endtime, array(':uniacid' => $_W['uniacid']), 'id');
				$log = array('uniacid' => $_W['uniacid'], 'money' => $totalmoney, 'status' => 2, 'type' => 4, 'ctime' => TIMESTAMP, 'sendmonth' => $set['sendmonth'], 'paymethod' => $set['paymethod'], 'sendpay_error' => $sendpay_error, 'orderids' => iserializer($orderids), 'isglobal' => 1, 'utime' => $daytime, 'send_bonus_sn' => $send_bonus_sn, 'total' => $total);
				pdo_insert('sz_yi_bonus', $log);
				plog('bonus.sendall', '自动发放全球分红，共计' . $total . '人 金额' . $totalmoney . '元，订单总额' . $ordermoney . '元');

				if ($senddata) {
					$filedata = array();
					$file_path = IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log';

					if (file_exists($file_path)) {
						$filedata = unserialize(file_get_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log'));
					}

					$data = serialize(array_merge($senddata, $filedata));
					file_put_contents(IA_ROOT . '/addons/sz_yi/data/message/' . $filesn . '.log', $data, FILE_APPEND);
				}
			}
		}

		public function myorder($openid)
		{
			global $_W;
			$myorder = pdo_fetch('select sum(og.realprice) as ordermoney,count(distinct og.orderid) as ordercount from ' . tablename('sz_yi_order') . ' o ' . ' left join  ' . tablename('sz_yi_order_goods') . ' og on og.orderid=o.id ' . ' where o.openid=:openid and o.status>=3 and o.uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $openid));
			return $myorder;
		}

		public function autoexec($uniacid = 0)
		{
			global $_W;
			global $_GPC;

			if (empty($uniacid)) {
				return NULL;
			}

			$_W['uniacid'] = $uniacid;
			$daytime = strtotime(date('Y-m-d 00:00:00'));
			$isbonus = false;
			$bonus_set = $this->getSet($_W['uniacid']);

			if (!empty($bonus_set['sendmethod'])) {
				if ($bonus_set['sendmonth'] == 1) {
					$daytime = strtotime(date('Y-m-1 00:00:00'));
				}

				$bonus_data = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_bonus') . ' where ctime>' . $daytime . ' and isglobal=0 and uniacid=' . $_W['uniacid'] . ' and bonus_area=0  order by id desc');
				$bonus_data_area = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_bonus') . ' where ctime>' . $daytime . ' and isglobal=0 and uniacid=' . $_W['uniacid'] . ' and bonus_area!=0  order by id desc');
				$bonus_data_isglobal = pdo_fetchcolumn('select count(*) from ' . tablename('sz_yi_bonus') . ' where ctime>' . $daytime . ' and isglobal=1 and uniacid=' . $_W['uniacid'] . '  order by id desc');

				if (!empty($bonus_set['start'])) {
					$filesn = TIMESTAMP;

					if (empty($bonus_data)) {
						$this->autosend($_W['uniacid'], $filesn);
						$isbonus = true;
					}

					if (empty($bonus_data_area)) {
						$this->autosendarea($_W['uniacid'], $filesn);
						$isbonus = true;
					}

					if (empty($bonus_data_isglobal)) {
						$this->autosendall($_W['uniacid'], $filesn);
						$isbonus = true;
					}
				}
			}

			if ($isbonus) {
				return $filesn;
			}
		}
	}
}

?>
