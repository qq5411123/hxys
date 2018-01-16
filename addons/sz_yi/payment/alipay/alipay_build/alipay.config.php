<?php
// 唐上美联佳网络科技有限公司(技术支持)
$alipay_config['sign_type'] = strtoupper('MD5');
$alipay_config['input_charset'] = strtolower('utf-8');
$alipay_config['cacert'] = $_SERVER['SERVER_NAME'] . '/addons/sz_yi/cert/cacert.pem';
$alipay_config['transport'] = 'http';
echo "\n\n";

?>
