<?php
// 唐上美联佳网络科技有限公司(技术支持)
function Qiniu_Encode($str)
{
	$find = array('+', '/');
	$replace = array('-', '_');
	return str_replace($find, $replace, base64_encode($str));
}

function Qiniu_Decode($str)
{
	$find = array('-', '_');
	$replace = array('+', '/');
	return base64_decode(str_replace($find, $replace, $str));
}


?>
