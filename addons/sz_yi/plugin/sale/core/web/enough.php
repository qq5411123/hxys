<?php
// 唐上美联佳网络科技有限公司(技术支持)
global $_W;
global $_GPC;
ca('sale.enough.view');
$set = $this->getSet();

if (checksubmit('submit')) {
	ca('sale.enough.save');
	$data = (is_array($_GPC['data']) ? $_GPC['data'] : array());
	$set['enoughfree'] = intval($data['enoughfree']);
	$set['enoughorder'] = round(floatval($data['enoughorder']), 2);
	$set['enoughareas'] = $data['enoughareas'];
	$set['enoughmoney'] = round(floatval($data['enoughmoney']), 2);
	$set['enoughdeduct'] = round(floatval($data['enoughdeduct']), 2);
	$enoughs = array();
	$postenoughs = (is_array($_GPC['enough']) ? $_GPC['enough'] : array());

	foreach ($postenoughs as $key => $value) {
		$enough = floatval($value);

		if (0 < $enough) {
			$enoughs[] = array('enough' => floatval($_GPC['enough'][$key]), 'give' => floatval($_GPC['give'][$key]));
		}
	}

	$set['enoughs'] = $enoughs;
	$this->updateSet($set);
	plog('sale.enough.save', '修改满额优惠');
	message('满额优惠设置成功!', referer(), 'success');
}

$areas = m('cache')->getArray('areas', 'global');

if (!is_array($areas)) {
	require_once SZ_YI_INC . 'json/xml2json.php';
	$file = IA_ROOT . '/addons/sz_yi/static/js/dist/area/Area.xml';
	$content = file_get_contents($file);
	$json = xml2json::transformXmlStringToJson($content);
	$areas = json_decode($json, true);
	m('cache')->set('areas', $areas, 'global');
}

load()->func('tpl');
include $this->template('enough');

?>
