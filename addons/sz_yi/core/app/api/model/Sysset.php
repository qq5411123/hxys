<?php
// 唐上美联佳网络科技有限公司(技术支持)
namespace app\api\model;

class Sysset extends BaseModel
{
	protected $tableName = 'sz_yi_sysset';

	public function appReferral($uniacid)
	{
		$where['uniacid'] = $uniacid;
		$set_data = $this->where($where)->find();
		$set = unserialize($set_data['sets']);
		$_obf_DTwxGSsxERgDGAopAhYrOSYBKxE_JwE_ = $set['app']['base']['accept'];
		return (bool) $_obf_DTwxGSsxERgDGAopAhYrOSYBKxE_JwE_;
	}
}

?>
