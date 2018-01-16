<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace api\controller\user;

class Login extends \api\YZ
{
	public function __construct()
	{
		parent::__construct();
		$this->validate('username', 'password');
	}

	public function index()
	{
		load()->model('user');
		global $_W;
		global $_GPC;
		$para = $this->getPara();
		$para['username'] = trim($para['username']);
		$record = user_single($_GPC);

		if (empty($record)) {
			$this->returnError('登录失败，请检查您输入的用户名和密码！');
		}

		if ($record['status'] == 1) {
			$this->returnError('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！');
		}

		$_W['isfounder'] = $this->isFonder();
		if (!empty($_W['siteclose']) && empty($_W['isfounder'])) {
			$this->returnSuccess('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
		}

		$record['isfounder'] = $_W['isfounder'];
		$status = array();
		$status['uid'] = $record['uid'];
		$status['lastvisit'] = TIMESTAMP;
		$status['lastip'] = CLIENT_IP;
		user_update($status);
		if (isset($para['mobile']) && !empty($para['mobile'])) {
			$user_model = new \api\model\user();
			$user_model->saveProfile($record['uid'], array('mobile' => $para['mobile']));
		}

		$profile = pdo_fetch('SELECT * FROM ' . tablename('users_profile') . ' WHERE `uid` = :uid LIMIT 1', array(':uid' => $record['uid']));
		$record = array_part('uid,username', $record) + array_part('avatar,realname,nickname,qq,mobile', $profile);
		$this->returnSuccess($record);
	}
}

?>
