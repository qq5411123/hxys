<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace LeanCloud\Operation;

class SetOperation implements IOperation
{
	private $key;
	private $value;

	public function __construct($key, $val)
	{
		$this->key = $key;
		$this->value = $val;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function encode()
	{
		return \LeanCloud\LeanClient::encode($this->value);
	}

	public function applyOn($oldval)
	{
		return $this->value;
	}

	public function mergeWith($prevOp)
	{
		return $this;
	}
}

?>
