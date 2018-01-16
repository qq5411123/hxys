<?php

error_reporting(0);
require '../../framework/bootstrap.inc.php';
require '../../addons/sz_yi/defines.php';
require '../../addons/sz_yi/core/inc/functions.php';
require '../../addons/sz_yi/core/inc/plugin/plugin_model.php';
global $_W;
global $_GPC;
set_time_limit(0);
$sql = 'SELECT * FROM ' . tablename('uni_account') . ' as a LEFT JOIN' . tablename('account') . ' as b ON a.default_acid = b.acid WHERE a.default_acid <> 0';
$sets = pdo_fetchall($sql);
$cdir = IA_ROOT . '/addons/sz_yi/data/message';

if (!is_dir($cdir)) {
	mkdir($cdir, 511, true);
}

$filedatas = array();

foreach ($sets as $k => $set) {
	m('order')->autoexec($set['uniacid']);
	m('member')->autoexec($set['uniacid']);
	$pbonus = p('bonus');

	if (!empty($pbonus)) {
		$filesn = $pbonus->autoexec($set['uniacid']);

		if (!empty($filesn)) {
			$filedatas[] = array('uniacid' => $set['uniacid'], 'filesn' => $filesn);
		}
	}

	$preturn = p('return');

	if (!empty($preturn)) {
		$preturn->autoexec($set['uniacid']);
	}

	$pyunbi = p('yunbi');

	if (!empty($pyunbi)) {
		$pyunbi->autoexec($set['uniacid']);
	}
}

if ($filedatas) {
	foreach ($filedatas as $key => $value) {
		m('message')->sendmsg($value['filesn'], $value['uniacid']);
	}
}

echo date('Y-m-d H:i:s');
echo '<pre>';
print_r('ok...');
echo "\r\n";
exit();

?>
