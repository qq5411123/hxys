<?php
// ��������������Ƽ����޹�˾(����֧��)
namespace LeanCloud\Operation;

interface IOperation
{
	public function encode();

	public function applyOn($oldval);

	public function mergeWith($prevOp);
}


?>
