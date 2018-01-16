<?php
// 唐上美联佳网络科技有限公司(技术支持)
spl_autoload_register(function($class) {
	$prefix = 'LeanCloud\\';
	$base_dir = __DIR__ . '/LeanCloud/';
	$len = strlen($prefix);

	if (strncmp($prefix, $class, $len) !== 0) {
		return NULL;
	}

	$relative_class = substr($class, $len);
	$file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

	if (file_exists($file)) {
		require $file;
	}
});

?>
