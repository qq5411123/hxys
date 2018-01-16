<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
	<style type='text/css'>
		.trhead td {  background:#efefef;text-align: center}
		.trbody td {  text-align: center; vertical-align:top;border-left:1px solid #ccc;overflow: hidden;}
		.goods_info{position:relative;width:60px;}
		.goods_info img {width:50px;background:#fff;border:1px solid #CCC;padding:1px;}
		.goods_info:hover {z-index:1;position:absolute;width:auto;}
		.goods_info:hover img{width:320px; height:320px;}
	</style>

	<div class="main">
		<form id="baseform" method="post" class="form-horizontal form">
			<div class="rightlist">
				<div class="panel panel-default">
					<div class="panel-heading">客户端</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否启用</label>
							<div class="col-sm-9 col-xs-12">
								<label class="radio-inline">
									<input type="radio" name="app[switch]" value="1" <?php  if($set['app']['base']['switch'] == 1) { ?> checked <?php  } ?>> 开启
								</label>

								<label class="radio-inline">
									<input type="radio" name="app[switch]" value="0" <?php  if(empty($set['app']['base']['switch']) || $set['app']['base']['switch'] == 0) { ?> checked <?php  } ?>> 关闭
								</label>
							</div>
						</div>
					</div>
					<div class="panel-heading">微信</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否隐藏分销功能</label>
							<div class="col-sm-9 col-xs-12">
								<label class="radio-inline">
									<input type="radio" name="wx[switch]" value="1" <?php  if($set['app']['base']['wx']['switch'] == 1) { ?> checked <?php  } ?>> 开启
								</label>

								<label class="radio-inline">
									<input type="radio" name="wx[switch]" value="0" <?php  if(empty($set['app']['base']['wx']['switch']) || $set['app']['base']['wx']['switch'] == 0) { ?> checked <?php  } ?>> 关闭
								</label>
							</div>
						</div>
					</div>

					<div class="panel-heading">推送消息</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否启用</label>
							<div class="col-sm-9 col-xs-12">
								<label class="radio-inline">
									<input type="radio" name="leancloud[switch]" value="1" <?php  if($set['app']['base']['leancloud']['switch'] == 1) { ?> checked <?php  } ?>> 开启
								</label>
								<label class="radio-inline">
									<input type="radio" name="leancloud[switch]" value="0" <?php  if(empty($set['app']['base']['leancloud']['switch']) || $set['app']['base']['leancloud']['switch'] == 0) { ?> checked <?php  } ?>> 关闭
								</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">LeanCloud App ID</label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="leancloud[id]" class="form-control" value="<?php  echo $set['app']['base']['leancloud']['id'];?>" autocomplete="off">
								<span class="help-block">每个 app 有一个唯一 ID，不可变更</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">LeanCloud App Key </label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="leancloud[key]" class="form-control" value="<?php  echo $set['app']['base']['leancloud']['key'];?>" autocomplete="off">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">LeanCloud Master Key</label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="leancloud[master]" class="form-control" value="<?php  echo $set['app']['base']['leancloud']['master'];?>" autocomplete="off">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">LeanCloud Notify Action </label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="leancloud[notify]" class="form-control" value="<?php  echo $set['app']['base']['leancloud']['notify'];?>" autocomplete="off">
								<span class="help-block"></span>
							</div>
						</div>
					</div>

					<div class="panel-heading">推荐码</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否启用</label>
							<div class="col-sm-9 col-xs-12">
								<label class="radio-inline">
									<input type="radio" name="app[accept]" value="1" <?php  if($set['app']['base']['accept'] == 1) { ?> checked <?php  } ?>> 开启
								</label>

								<label class="radio-inline">
									<input type="radio" name="app[accept]" value="0" <?php  if(empty($set['app']['base']['accept']) || $set['app']['base']['accept'] == 0) { ?> checked <?php  } ?>> 关闭
								</label>
							</div>
                        </div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否必填</label>
							<div class="col-sm-9 col-xs-12">
								<label class="radio-inline">
									<input type="radio" name="app[useing]" value="1" <?php  if($set['app']['base']['useing'] == 1) { ?> checked <?php  } ?>> 是
								</label>
								<label class="radio-inline">
									<input type="radio" name="app[useing]" value="0" <?php  if(empty($set['app']['base']['useing']) || $set['app']['base']['useing'] == 0) { ?> checked <?php  } ?>> 否
								</label>
							</div>
						</div>
					</div>

					<div class="panel-heading">下载地址</div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">Android </label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="app[android_url]" class="form-control" value="<?php  echo $set['app']['base']['android_url'];?>" autocomplete="off">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label">IOS</label>
							<div class="col-sm-9 col-xs-12">
								<input type="text" name="app[ios_url]" class="form-control" value="<?php  echo $set['app']['base']['ios_url'];?>" autocomplete="off">
								<span class="help-block"></span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
							<div class="col-sm-9 col-xs-12">
								<input type="submit" name="submit" value="保存设置" class="btn btn-primary" data-original-title="" title="">
								<input type="hidden" name="token" value="e784fadb">
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</form>
	</div>


	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>