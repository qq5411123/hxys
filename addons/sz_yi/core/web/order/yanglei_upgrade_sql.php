<?php
// 唐上美联佳网络科技有限公司(技术支持)
if (!pdo_fieldexists('sz_yi_member_log', 'poundage')) {
	pdo_fetchall('ALTER TABLE ' . tablename('sz_yi_member_log') . 'ADD `poundage` DECIMAL(10,2) NOT NULL AFTER `money`;');
}

if (!pdo_fieldexists('sz_yi_member_log', 'withdrawal_money')) {
	pdo_fetchall('ALTER TABLE ' . tablename('sz_yi_member_log') . 'ADD `withdrawal_money` DECIMAL(10,2) NOT NULL AFTER `poundage`;');
}

?>
