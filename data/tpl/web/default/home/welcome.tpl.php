<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="welcome-container" id="js-home-welcome" ng-controller="WelcomeCtrl" ng-cloak>
	<div class="panel we7-panel account-stat">
		<div class="panel-heading">今日关键指标</div>
		<div class="panel-body we7-padding-vertical">
			<div class="col-sm-3 text-center">
				<div class="title">今日新关注</div>
				<div class="num"><?php  echo $today_add_num;?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">今日取消关注</div>
				<div class="num"><?php  echo $today_cancel_num;?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">今日净增关注</div>
				<div class="num"><?php  echo $today_jing_num;?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">累计关注</div>
				<div class="num"><?php  echo $today_total_num;?></div>
			</div>
		</div>
	</div>
	<div class="panel we7-panel account-stat">
		<div class="panel-heading">昨日关键指标</div>
		<div class="panel-body we7-padding-vertical">
			<div class="col-sm-3 text-center">
				<div class="title">昨日新关注</div>
				<div class="num"><?php  echo $yesterday_stat['new'];?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">昨日取消关注</div>
				<div class="num"><?php  echo $yesterday_stat['cancel'];?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">昨日净增关注</div>
				<div class="num"><?php  echo intval($yesterday_stat['new']) - intval($yesterday_stat['cancel'])?></div>
			</div>
			<div class="col-sm-3 text-center">
				<div class="title">累计关注</div>
				<div class="num"><?php  echo $yesterday_stat['cumulate'];?></div>
			</div>
		</div>
	</div>

	<div class="panel we7-panel notice">
		<div class="panel-heading">
			公告
			<a href="./index.php?c=article&a=notice-show" target="_blank" class="pull-right color-default">更多</a>
		</div>
		<ul class="list-group">
			<li class="list-group-item" ng-repeat="notice in notices" ng-if="notices">
				<a ng-href="{{notice.url}}" class="text-over" target="_blank" ng-bind="notice.title"></a>
				<span class="time" ng-bind="notice.createtime"></span>
			</li>
			<li class="list-group-item text-center" ng-if="!notices">
				<span>暂无数据</span>
			</li>
		</ul>
	</div>

</div>
<script>
	angular.module('homeApp').value('config', {
		notices: <?php echo !empty($notices) ? json_encode($notices) : 'null'?>,
		last_modules: <?php echo !empty($last_modules) ? json_encode($last_modules) : 'null'?>,
	});
	angular.bootstrap($('#js-home-welcome'), ['homeApp']);
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
