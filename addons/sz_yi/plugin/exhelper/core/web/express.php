<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!defined('IN_IA')) {
	print('Access Denied');
}

global $_W;
global $_GPC;
$op = $_GPC['op'];

if ($op == 'list') {
	$cate = $_GPC['cate'];
	$condition = '';
	if (p('supplier') && !$_W['isfounder']) {
		$condition .= ' and uid=' . $_W['uid'];
	}

	if (!empty($cate)) {
		if ($cate == 1) {
			ca('exhelper.exptemp1');
		}
		else {
			if ($cate == 2) {
				ca('exhelper.exptemp2');
			}
		}

		$list = pdo_fetchall('SELECT * FROM ' . tablename('sz_yi_exhelper_express') . ' WHERE type=:type and uniacid=:uniacid ' . $condition . ' ORDER BY isdefault desc , id DESC', array(':type' => $cate, ':uniacid' => $_W['uniacid']));
	}
}
else if ($op == 'delete') {
	$id = intval($_GPC['id']);
	$type = $_GPC['type'];

	if ($type == 1) {
		ca('exhelper.exptemp1.delete');
	}
	else {
		if ($type == 2) {
			ca('exhelper.exptemp2.delete');
		}
	}

	$item = pdo_fetch('SELECT id,type FROM ' . tablename('sz_yi_exhelper_express') . ' WHERE id=:id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));

	if (empty($item)) {
		message('抱歉，模不存在或是已经被删除！', $this->createPluginWebUrl('exhelper/express', array('op' => 'list' . $type)), 'error');
	}

	pdo_delete('sz_yi_exhelper_express', array('id' => $id));
	plog('exhelper.express.delete', '删除快递单 ID: ' . $id . ' 发件人: ' . $item['sendername'] . ' ');
	message('模删除成功！', $this->createPluginWebUrl('exhelper/express', array('op' => 'list', 'cate' => $type)), 'success');
}
else if ($op == 'setdefault') {
	$id = intval($_GPC['id']);
	$type = $_GPC['type'];

	if ($type == 1) {
		ca('exhelper.exptemp1.setdefault');
	}
	else {
		if ($type == 2) {
			ca('exhelper.exptemp2.setdefault');
		}
	}

	$cond = '';

	if (p('supplier')) {
		$cond = ',uid';
	}

	$item = pdo_fetch('SELECT id,expressname,type' . $cond . ' FROM ' . tablename('sz_yi_exhelper_express') . ' WHERE id=:id AND uniacid=:uniacid', array(':id' => $id, ':uniacid' => $_W['uniacid']));

	if (empty($item)) {
		message('抱歉，快递单不存在或是已经被删除！', $this->createPluginWebUrl('exhelper/express', array('op' => 'list' . $type)), 'error');
	}

	if (empty($item['uid'])) {
		pdo_update('sz_yi_exhelper_express', array('isdefault' => 0), array('type' => $type, 'uniacid' => $_W['uniacid']));
		pdo_update('sz_yi_exhelper_express', array('isdefault' => 1), array('id' => $id));
	}
	else {
		pdo_update('sz_yi_exhelper_express', array('isdefault' => 0), array('type' => $type, 'uniacid' => $_W['uniacid'], 'uid' => $item['uid']));
		pdo_update('sz_yi_exhelper_express', array('isdefault' => 1), array('id' => $id, 'uid' => $item['uid']));
	}

	plog('exhelper.express.delete', '设置快递单默认信息 ID: ' . $id . ' 快递单: ' . $item['expressname'] . ' ');
	message('设置成功！', $this->createPluginWebUrl('exhelper/express', array('op' => 'list', 'cate' => $type)), 'success');
}
else {
	if ($op == 'post') {
		$id = intval($_GPC['id']);
		$cate = intval($_GPC['cate']);

		if (empty($cate)) {
			exit();
		}

		$printset = pdo_fetch('SELECT * FROM ' . tablename('sz_yi_exhelper_sys') . ' WHERE uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid']));

		if (empty($id)) {
			if ($cate == 1) {
				ca('exhelper.exptemp1.add');
			}
			else {
				if ($cate == 2) {
					ca('exhelper.exptemp2.add');
				}
			}
		}
		else if ($cate == 1) {
			ca('exhelper.exptemp1.edit|exhelper.exptemp1.view');
		}
		else {
			if ($cate == 2) {
				ca('exhelper.exptemp2.edit|exhelper.exptemp2.view');
			}
		}

		if ($_W['isajax'] && $_W['ispost']) {
			$data = array('uniacid' => $_W['uniacid'], 'expressname' => trim($_GPC['expressname']), 'datas' => trim($_POST['datas']), 'isdefault' => intval($_GPC['isdefault']), 'width' => intval($_GPC['width']), 'height' => intval($_GPC['height']), 'bg' => $_GPC['bg']);

			if (p('supplier')) {
				$data['uid'] = $_W['uid'];
			}

			if ($cate == 1) {
				$data['express'] = $_GPC['express'];
				$data['expresscom'] = $_GPC['expresscom'];
			}

			if (!empty($id)) {
				pdo_update('sz_yi_exhelper_express', $data, array('id' => $id));

				if ($cate == 1) {
					plog('exhelper.exptemp1.edit', '修改快递单 ID: ' . $id);
				}
				else {
					plog('exhelper.exptemp2.edit', '修改发货单单 ID: ' . $id);
				}
			}
			else {
				$data['type'] = $cate;
				pdo_insert('sz_yi_exhelper_express', $data);
				$id = pdo_insertid();

				if ($cate == 1) {
					plog('exhelper.exptemp1.add', '添加快递单 ID: ' . $id);
				}
				else {
					plog('exhelper.exptemp2.add', '添加发货单 ID: ' . $id);
				}
			}

			if (!empty($data['isdefault'])) {
				if (empty($data['uid'])) {
					pdo_update('sz_yi_exhelper_express', array('isdefault' => 0), array('type' => $cate, 'uniacid' => $_W['uniacid']));
					pdo_update('sz_yi_exhelper_express', array('isdefault' => 1), array('type' => $cate, 'id' => $id));
				}
				else {
					pdo_update('sz_yi_exhelper_express', array('isdefault' => 0), array('type' => $cate, 'uniacid' => $_W['uniacid'], 'uid' => $data['uid']));
					pdo_update('sz_yi_exhelper_express', array('isdefault' => 1), array('type' => $cate, 'id' => $id, 'uid' => $data['uid']));
				}
			}

			exit(json_encode(array('id' => $id)));
		}

		$item = pdo_fetch('select * from ' . tablename('sz_yi_exhelper_express') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));

		if (!empty($item)) {
			$datas = json_decode($item['datas'], true);
		}
	}
}

load()->func('tpl');
include $this->template('express');

?>
