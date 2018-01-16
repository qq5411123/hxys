<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace LeanCloud\Operation;

interface IOperation
{
	public function encode();

	public function applyOn($oldval);

	public function mergeWith($prevOp);
}


?>
