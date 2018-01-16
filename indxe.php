<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
require './framework/bootstrap.inc.php';
$host = $_SERVER['HTTP_HOST'];

if($host == "www.myhxys.com" || $host == "guanli.myhxys.com"){
	
}else{
	if($_W['os'] == 'mobile' && $host =="hxys.laiweiqu.com"){
		header('Location: ./app/index.php?i=2&c=entry&m=sz_yi&do=shop');
	}
	echo "<div style='width:100%;height:100%;text-align:center;line-height:50%;font-size:36px;color:red;'>你的域名不合法！！！</div>";
	exit;
}
if (!empty($host)) {
	$bindhost = pdo_fetch("SELECT * FROM ".tablename('site_multi')." WHERE bindhost = :bindhost", array(':bindhost' => $host));
	if (!empty($bindhost)) {
		//header("Location: ". $_W['siteroot'] . 'app/index.php?i='.$bindhost['uniacid'].'&t='.$bindhost['id']);
		header("Location: ". $_W['siteroot'] . 'app/index.php?i='.$bindhost['uniacid'].'&c=entry&m=sz_yi&do=shop');
		exit;
	}
}

if($_W['os'] == 'mobile' && (!empty($_GPC['i']) || !empty($_SERVER['QUERY_STRING']))) {
	header('Location: ./app/index.php?' . $_SERVER['QUERY_STRING']);
} else {
	if($host == 'guanli.myhxys.com'){
		header('Location: ./web/index.php?' . $_SERVER['QUERY_STRING']);
	}else{
		header('Location: ./app/index.php?i=2&c=entry&m=sz_yi&do=shop');
	}
	
}