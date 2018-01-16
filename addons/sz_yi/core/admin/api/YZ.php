<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace admin\api;

require_once __DIR__ . '/base.php';
class YZ extends base
{
	protected $_W;
	protected $_GPC;
	protected $uid;
	protected $uniacid;

	public function __construct()
	{
		global $_W;
		global $_GPC;
		parent::__construct();
		$this->uid = $this->para['uid'];

		if (isset($this->uniacid)) {
			$this->uniacid = $this->para['uniacid'];
		}

		$this->set_WAnd_GPC();
		require IA_ROOT . '/web/common/bootstrap.sys.inc.php';
	}

	protected function set_WAnd_GPC()
	{
		global $_W;
		global $_GPC;
		$this->_W = $_W;
		$this->_GPC = $_GPC;

		if (is_array($this->para)) {
			$_GPC = array_merge($_GPC, $this->para);
		}

		$_W['uid'] = $this->para['uid'];
		$_GPC['__uid'] = $this->para['uid'];

		if (isset($this->para['uniacid'])) {
			$_W['uniacid'] = $this->para['uniacid'];
			$_GPC['__uniacid'] = $this->para['uniacid'];
		}
	}

	public function isFonder()
	{
		$founders = explode(',', $this->_W['config']['setting']['founder']);
		return in_array($this->para['uid'], $founders);
	}

	public function ca($permtypes)
	{
		if (!cv($permtypes)) {
			$this->returnError('您没有权限操作，请联系管理员!');
		}
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function getUniacid()
	{
		return $this->uniacid;
	}

	public function isSupplier($uid = false)
	{
		if (!$uid) {
			$uid = $this->uid;
		}

		if (!p('supplier')) {
			return false;
		}

		if (!p('supplier')->verifyUserIsSupplier($uid)) {
			return false;
		}

		return true;
	}

	public function m($name = '')
	{
		static $_modules = array();

		if (isset($_modules[$name])) {
			return $_modules[$name];
		}

		$model = SZ_YI_CORE . 'model/api/' . strtolower($name) . '.php';

		if (!is_file($model)) {
			exit(' Model ' . $name . ' Not Found!');
		}

		require $model;
		$class_name = '\\api\\model\\' . strtolower($name);
		$_modules[$name] = new $class_name();
		return $_modules[$name];
	}
}

?>
