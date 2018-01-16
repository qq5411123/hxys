<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace LeanCloud;

class LeanRole extends LeanObject
{
	static protected $className = '_Role';

	public function __construct($name, $acl)
	{
		parent::__construct();
		$this->set('name', $name);
		$this->setACL($acl);
	}

	public function getName()
	{
		return $this->get('name');
	}

	public function getUsers()
	{
		return $this->getRelation('users');
	}

	public function getRoles()
	{
		return $this->getRelation('roles');
	}
}

?>
