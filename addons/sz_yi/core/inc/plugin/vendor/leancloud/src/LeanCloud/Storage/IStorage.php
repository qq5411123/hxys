<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace LeanCloud\Storage;

interface IStorage
{
	public function set($key, $val);

	public function get($key);

	public function remove($key);

	public function clear();
}


?>
