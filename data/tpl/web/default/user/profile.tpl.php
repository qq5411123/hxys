<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="we7-page-title">我的账户</div>
<ul class="we7-page-tab"></ul>
<div id="js-user-profile" ng-controller="UserProfileDisplay" ng-cloak>
	<table class="table we7-table table-hover table-form">
		<col width="140px " />
		<col />
		<col width="100px" />
		<tr>
			<th class="text-left" colspan="3">账户设置设置</th>
		</tr>
		<tr>
			<td class="table-label">头像</td>
			<td><img ng-src="{{profile.avatar}}" class="img-circle" width="65px" height="65px" /></td>
			<td><a href="javascript:;" class="color-default" ng-click="changeAvatar()">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">用户名</td>
			<td ng-bind="user.username"></td>
			<td><a href="#name" class="color-default" data-toggle="modal" data-target="" ng-click="editInfo('username', user.username)">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">密码</td>
			<td>******</td>
			<td><a href="javascript:;" class="color-default" data-toggle="modal" data-target="#pass">修改</a></td>
		</tr>
	</table>
	<div class="modal fade" id="name" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改用户名</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="text" ng-model="userOriginal.username" class="form-control" placeholder="用户名" />
						<span class="help-block"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('username')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="pass" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改密码</div>
				</div>
				<div class="modal-body text-center">
					<div class="we7-form" style="width: 450px; margin: 0 auto;">
						<div class="form-group">
							<label for="" class="control-label col-sm-2">原密码</label>
							<div class="form-controls col-sm-10">
								<input type="password" value="" class="form-control old-password">
							
							</div>
						</div>
						<div class="form-group">
							<label for="" class="control-label col-sm-2">新密码</label>
							<div class="form-controls col-sm-10">
								<input type="password" value="" class="form-control new-password">
								
							</div>
						</div>
						<div class="form-group">
							<label for="" class="control-label col-sm-2">确认新密码</label>
							<div class="form-controls col-sm-10">
								<input type="password" value="" class="form-control renew-password">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('password')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<table class="table we7-table table-hover table-form">
		<col width="140px " />
		<col />
		<col width="100px" />
		<tr>
			<th class="text-left" colspan="3">基础信息</th>
		</tr>      
		<tr>     
			<td class="table-label">真实姓名</td>
			<td ng-bind="profile.realname"></td>
			<td><a href="javascript:;" class="color-default" data-toggle="modal" data-target="#realname" ng-click="editInfo('realname', profile.realname)">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">出生年月日</td>
			<td ng-bind="profile.births"></td>
			<td><a href="javascript:;" class="color-default" data-toggle="modal" data-target="#birth">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">邮寄地址</td>
			<td ng-bind="profile.address"></td>
			<td><a href="javascript:;" class="color-default" data-toggle="modal" data-target="#address" ng-click="editInfo('address', profile.address)">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">居住地址</td>
			<td ng-bind="profile.resides"></td>
			<td><a href="javascript:;" class="color-default" data-toggle="modal" data-target="#reside">修改</a></td>
		</tr>
		<tr>
			<td class="table-label">上次登录时间</td>
			<td ng-bind="user.last_visit"></td>
			<td></td>
		</tr>
		<tr>
			<td class="table-label">上次登录IP</td>
			<td ng-bind="user.lastip"></td>
			<td></td>
		</tr>
	</table>
	<div class="modal fade" id="realname" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改真实姓名</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input type="text" class="form-control" ng-model="userOriginal.realname">
						<span class="help-block">请填写真实姓名</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('realname')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="birth" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改出生年月日</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<?php  echo tpl_fans_form('birth',$profile['birth']);?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('birth')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="address" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改邮寄地址</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input class="form-control" type="text" ng-model="userOriginal.address">
						<span class="help-block">请填写详细地址</span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('address')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="reside" role="dialog">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">修改居住地址</div>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<?php  echo tpl_fans_form('reside',$profile['reside']);?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="httpChange('reside')">确定</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
require(['underscore'], function(){
	angular.module('userProfile').value('config', {
		user: <?php echo !empty($user) ? json_encode($user) : 'null'?>,
		profile: <?php echo !empty($profile) ? json_encode($profile) : 'null'?>,
		links: {
			userPost: "<?php  echo url('user/profile/post')?>",
		},
	});

	angular.bootstrap($('#js-user-profile'), ['userProfile']);
});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>