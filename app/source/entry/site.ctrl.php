<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
 
defined('IN_IA') or exit('Access Denied');

$site = WeUtility::createModuleSite($entry['module']);
// echo '<pre>';

if(!is_error($site)) {
	$method = 'doMobile' . ucfirst($entry['do']);
	// echo $method;
	exit($site->$method());
}
exit();