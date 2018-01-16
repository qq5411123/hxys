<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace admin\api\controller\test;

class Test extends \admin\api\YZ
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$_obf_DQsCDS4OLQs_Ag8WPTYyCgQVNBMKKjI_ = new \Util\Push();
		$res = $_obf_DQsCDS4OLQs_Ag8WPTYyCgQVNBMKKjI_->send('标题', '内容', array());
		dump($res);
	}

	public function changePassword()
	{
		$this->validate('uid', 'old_password', 'new_password');
		$para = $this->getPara();
		$sql = 'SELECT username, password, salt, groupid, starttime, endtime FROM ' . tablename('users') . ' WHERE `uid` = \'' . $para['uid'] . '\'';
		$user = pdo_fetch($sql);

		if (empty($user)) {
			$this->returnError('抱歉，用户不存在或是已经被删除！');
		}

		if (empty($para['new_password']) || empty($para['old_password'])) {
			$this->returnError('密码不能为空，请重新填写！');
		}

		if ($para['new_password'] == $para['old_password']) {
			$this->returnError('新密码与原密码一致，请检查！');
		}

		$password_old = user_hash($para['old_password'], $user['salt']);

		if ($user['password'] != $password_old) {
			$this->returnError('原密码错误，请重新填写！');
		}

		$result = '';
		$members = array('password' => user_hash($para['new_password'], $user['salt']));
		$result = pdo_update('users', $members, array('uid' => $para['uid']));
		$this->returnSuccess('修改成功！');
	}

	public function editBaseInfo()
	{
		$this->validate('uid');
		$para = $this->getPara();
		$user_model = new \admin\api\model\user();
		$user_model->saveProfile($para['uid'], array_part('realname,nickname,qq,mobile', $para));
		$record = user_single($para);
		$profile = pdo_fetch('SELECT * FROM ' . tablename('users_profile') . ' WHERE `uid` = :uid LIMIT 1', array(':uid' => $record['uid']));
		$record = array_part('uid,username', $record) + array_part('avatar,realname,nickname,qq,mobile', $profile);
		$this->returnSuccess($record);
	}
}

?>
