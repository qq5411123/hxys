<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace Pingpp;

class AttachedObject extends PingppObject
{
	public function replaceWith($properties)
	{
		$removed = array_diff(array_keys($this->_values), array_keys($properties));

		foreach ($removed as $k) {
			$this->$k = null;
		}

		foreach ($properties as $k => $v) {
			$this->$k = $v;
		}
	}
}

?>
