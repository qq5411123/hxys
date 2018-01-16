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
<?php  if($operation=='display') { ?>
<div class="rightlist">
<?php  if($ismerchant == false) { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="supplier" />
            <input type="hidden" name="method" value="supplier" />
            <input type="hidden" name="op" value="display" />
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">会员信息</label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                    <input type="text" class="form-control"  name="uid" value="<?php  echo $_GPC['uid'];?>" placeholder='搜索供货商ID/供应商名称'/> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label"></label>
                <div class="col-sm-8 col-lg-9 col-xs-12">
                       <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </div>
            </div>    
        </form>
    </div>
</div>

<div class="panel panel-default">
    <a class='btn btn-default' href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post'))?>">添加新供应商</a>   <a href="javascript:;" data-url="<?php  echo $_W['siteroot'];?>/app/index.php?c=entry&do=shop&m=sz_yi&p=login" class="btn btn-default js-clip" title="复制链接">复制链接(登录)</a> 
</div>
<?php  } ?>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="panel-body">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
                <tr>
                    <th style='width:150px;'>供应商ID</th>
                    <th style='width:150px;'>用户名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                    <?php  if(!empty($row['uid'])) { ?>
                        <tr>
                            <td><?php  echo $row['uid'];?></td>
                            <td><?php  echo $row['username'];?></td>
                            <td  style="overflow:visible;">
                                <div class="btn-group btn-group-sm">
                                    <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                                        <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
                                            <li><a href="<?php  if(!empty($row['openid'])) { ?><?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('openid' => $row['openid']));?><?php  } else { ?><?php  echo $this->createPluginWebUrl('supplier/supplier/detail',array('uid' => $row['uid']));?><?php  } ?>" title='供应商信息'><i class='fa fa-user'></i> 供应商信息</a></li>
                                            <?php  if(p('merchant')) { ?>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier/merchant', array('uid' => $row['uid']));?>" title='招商员'><i class='fa fa-th'></i> 招商员</a></li>
                                            <?php  } ?>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'post', 'id' => $row['id']))?>" title='修改密码'><i class='fa fa-edit'></i> 修改密码</a></li>
                                            <li><a  href="<?php  echo $this->createPluginWebUrl('supplier/supplier_list',array('supplier_uid' => $row['uid']));?>" title='我的订单'><i class='fa fa-list'></i> 我的订单</a></li>
                                            <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'delete', 'id' => $row['id']))?>" title="删除" onclick="return confirm('确定要删除该供应商吗？');"><i class='fa fa-remove'></i> &nbsp;删除供应商</a></li>
                                            <!-- <li><a href="<?php  echo $this->createPluginWebUrl('supplier/supplier_add', array('op' => 'income', 'id' => $row['id']))?>" title="充值"><i class='fa fa-remove'></i> &nbsp;供应商充值</a></li> -->
                                        </ul>
                                </div>
                            </td>
                        </tr>
                    <?php  } ?>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  if($ismerchant == true) { ?>
        <div class="col-sm-9 col-xs-12">
           <input type="button" name="back" onclick='history.back()' <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
        </div>
        <?php  } ?>
        <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } else if($operation=='detail') { ?>
<?php  if(empty($applyid)) { ?>
<div class="rightlist">
<form <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $supplierinfo['uid'];?>">
    <input type="hidden" name="op" value="detail">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="supplier" />
    <input type="hidden" name="method" value="supplier" />
    <input type="hidden" name="op" value="detail" />
    <div class='panel panel-default'>
        <div class='panel-heading'>
            供应商详细信息
        </div>
        <div class='panel-body'>
            <div class="form-group notice">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">微信角色</label>
                    <div class="col-sm-4">
                        <input type='hidden' id='noticeopenid' name='data[openid]' value="<?php  echo $supplierinfo['openid'];?>" />
                        <div class='input-group'>
                            <input type="text" name="saler" maxlength="30" value="<?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择角色</button>
                                <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#saler').val('');$('#saleravatar').hide()">清除选择</button>
                            </div> 
                        </div>
                        <span id="saleravatar" class='help-block' <?php  if(empty($saler)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/></span>
                        
                        <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择角色</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入昵称/姓名/手机号" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus-notice" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>
              
                    </div>
                </div>
<script language='javascript'>
  function search_members() {
     if( $.trim($('#search-kwd-notice').val())==''){
        $('#search-kwd-notice').attr('placeholder','请输入关键词');
         <!-- Tip.focus('#search-kwd-notice','请输入关键词'); -->
         return;
     }
    $("#module-menus-notice").html("正在搜索....")
    $.get('<?php  echo $this->createWebUrl('member/query')?>', {
      keyword: $.trim($('#search-kwd-notice').val())
    }, function(dat){
      $('#module-menus-notice').html(dat);
    });
  }
  function select_member(o) {
    $("#noticeopenid").val(o.openid);
    $("#saleravatar").show();
    $("#saleravatar").find('img').attr('src',o.avatar);
    $("#saler").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
    $("#modal-module-menus-notice .close").click();
  }
</script>
            
            <?php  if($diyform_flag == 1) { ?>
            <div class='panel-body'>
                <!--<span>diyform</span>-->

                <?php  $datas = iunserializer($supplierinfo['diymemberdata'])?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  if(!empty($avatar)) { ?>
                             <img src="<?php  echo $avatar;?>" style='width:30px;height:30px;padding:1px;border:1px solid #ccc' /><br/>
                           <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">金额</label>
                    <div class="col-sm-9 col-xs-12">
                    <span class='help-block'>累计提现金额：<span style='color:red'><?php  if(!empty($totalmoney)) { ?><?php  echo $totalmoney;?><?php  } else { ?>0<?php  } ?>元</span> 未提现金额：<span style='color:red'><?php  if(!empty($totalmoneyok)) { ?><?php  echo $totalmoneyok;?><?php  } else { ?>0<?php  } ?>元</span></span>
                    </div>
                </div>
                <?php  if(is_array($fields)) { foreach($fields as $key => $value) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo $value['tp_name']?></label>
                    <div class="col-sm-9 col-xs-12">
                        <div class="form-control-static">
                            <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
                            <?php  echo str_replace("\n","<br/>",$datas[$key])?>
                            <?php  } else if($value['data_type'] == 3) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <?php  echo $v1?>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 5) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 7) { ?>
                            <?php  echo $datas[$key]?>

                            <?php  } else if($value['data_type'] == 8) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <?php  echo $v1?>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 9) { ?>
                             <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
                            <?php  } ?>
                        </div>

                    </div>
                </div>

                <?php  } } ?>
            </div>
            <?php  } else { ?>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">真实姓名</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('commission.supplier.edit')) { ?>
                    <input type="text" name="data[realname]" class="form-control" value="<?php  echo $supplierinfo['realname'];?>"  />
                       <?php  } else { ?>
                       <input type="hidden" name="data[realname]" class="form-control" value="<?php  echo $supplierinfo['realname'];?>"  />
                    <div class='form-control-static'><?php  echo $supplierinfo['realname'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('commission.supplier.edit')) { ?>
                    <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $supplierinfo['mobile'];?>"  />
                       <?php  } else { ?>
                       <input type="hidden" name="data[mobile]" class="form-control" value="<?php  echo $supplierinfo['mobile'];?>"  />
                    <div class='form-control-static'><?php  echo $supplierinfo['mobile'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">金额</label>
                <div class="col-sm-9 col-xs-12">
                <span class='help-block'>累计金额：<span style='color:red'><?php  if(!empty($totalmoney)) { ?><?php  echo $totalmoney;?><?php  } else { ?>0<?php  } ?>元</span> 已结算金额：<span style='color:red'><?php  if(!empty($totalmoneyok)) { ?><?php  echo $totalmoneyok;?><?php  } else { ?>0<?php  } ?>元</span></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">银行卡号</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('commission.supplier.edit')) { ?>
                    <input type="text" name="data[banknumber]" class="form-control" value="<?php  echo $supplierinfo['banknumber'];?>"  />
                       <?php  } else { ?>
                       <input type="hidden" name="data[banknumber]" class="form-control" value="<?php  echo $supplierinfo['banknumber'];?>"  />
                    <div class='form-control-static'><?php  echo $supplierinfo['banknumber'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户名</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('commission.supplier.edit')) { ?>
                    <input type="text" name="data[accountname]" class="form-control" value="<?php  echo $supplierinfo['accountname'];?>"  />
                       <?php  } else { ?>
                       <input type="hidden" name="data[accountname]" class="form-control" value="<?php  echo $supplierinfo['accountname'];?>"  />
                    <div class='form-control-static'><?php  echo $supplierinfo['accountname'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户银行</label>
                <div class="col-sm-9 col-xs-12">
                       <?php if(cv('commission.supplier.edit')) { ?>
                    <input type="text" name="data[accountbank]" class="form-control" value="<?php  echo $supplierinfo['accountbank'];?>"  />
                       <?php  } else { ?>
                       <input type="hidden" name="data[accountbank]" class="form-control" value="<?php  echo $supplierinfo['accountbank'];?>"  />
                    <div class='form-control-static'><?php  echo $supplierinfo['accountbank'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <?php  } ?>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">充值金额(留空代表不充值)</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" name="data[income]" class="form-control" value=""  />
                </div>
            </div>
            <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
            </div>
         </div>
    </div>   
</form>
</div> 
<?php  } else { ?>
<div class="rightlist">
<form <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="applyid" value="<?php  echo $apply_info['id'];?>">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="supplier" />
    <input type="hidden" name="method" value="supplier_apply" />
    <input type="hidden" name="op" value="detail" />
    <div class='panel panel-default'>
        <div class='panel-heading'>
            详细信息
        </div>
        <div class='panel-body'>
            
            <?php  if($diyform_flag == 1) { ?>
            <div class='panel-body'>
                <?php  $datas = iunserializer($supplierinfo['diymemberdata'])?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">头像</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  if(!empty($avatar)) { ?>
                             <img src="<?php  echo $avatar;?>" style='width:100px;height:100px;padding:1px;border:1px solid #ccc' /><br/>
                           <?php  } ?>
                    </div>
                </div>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现单号</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  echo $apply_info['applysn'];?>
                    </div>
                </div>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现金额</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <button type="button" class="btn btn-danger">金额：<?php  echo $apply_info['apply_money'];?></button>
                    </div>
                </div>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现类型</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  if($apply_info['type']==1) { ?><button type="button" class="btn btn-warning">手动提现</button><?php  } else { ?><button type="button" class="btn btn-success">微信余额</button><?php  } ?>
                    </div>
                </div>
                <?php  if(empty($finish)) { ?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">申请时间</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  echo date('Y-m-d H:i:s', $apply_info['apply_time'])?>
                    </div>
                </div>
                <?php  } else { ?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">完成时间</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  echo date('Y-m-d H:i:s', $apply_info['finish_time'])?>
                    </div>
                </div>
                <?php  } ?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">状态</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="form-control-static">
                        <?php  if($apply_info['status'] == 0) { ?>
                        <button type="button" class="btn btn-info">申请中，等待审核</button>
                        <?php  } else { ?>
                        <button type="button" class="btn btn-danger">已打款，审核通过</button>
                        <?php  } ?>
                    </div>
                </div>
                <?php  if(is_array($fields)) { foreach($fields as $key => $value) { ?>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo $value['tp_name']?></label>
                    <div class="col-sm-9 col-xs-12">
                        <div class="form-control-static">
                            <?php  if($value['data_type'] == 0 || $value['data_type'] == 1 || $value['data_type'] == 2 || $value['data_type'] == 6) { ?>
                            <?php  echo str_replace("\n","<br/>",$datas[$key])?>
                            <?php  } else if($value['data_type'] == 3) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <?php  echo $v1?>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 5) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <a target="_blank" href="<?php  echo tomedia($v1)?>"><img style='width:100px;padding:1px;border:1px solid #ccc'  src="<?php  echo tomedia($v1)?>"></a>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 7) { ?>
                            <?php  echo $datas[$key]?>

                            <?php  } else if($value['data_type'] == 8) { ?>
                            <?php  if(!empty($datas[$key])) { ?>
                            <?php  if(is_array($datas[$key])) { foreach($datas[$key] as $k1 => $v1) { ?>
                            <?php  echo $v1?>
                            <?php  } } ?>
                            <?php  } ?>

                            <?php  } else if($value['data_type'] == 9) { ?>
                             <?php echo $datas[$key]['province']!='请选择省份'?$datas[$key]['province']:''?>-<?php echo $datas[$key]['city']!='请选择城市'?$datas[$key]['city']:''?>
                            <?php  } ?>
                        </div>

                    </div>
                </div>

                <?php  } } ?>
            </div>
            <?php  } else { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">头像</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($avatar)) { ?>
                         <img src="<?php  echo $avatar;?>" style='width:100px;height:100px;padding:1px;border:1px solid #ccc' /><br/>
                       <?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现单号</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  echo $apply_info['applysn'];?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现金额</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <a class="btn btn-danger" href="<?php  if($apply_info['status'] == 0) { ?><?php  echo $this->createPluginWebUrl('supplier/supplier_list',array('apply_id' => $apply_info['id']));?><?php  } else { ?>#<?php  } ?>">金额：<?php  echo $apply_info['apply_money'];?>元<?php  if($apply_info['status'] == 0) { ?>(点击查看订单)<?php  } ?></a>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">提现类型</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if($apply_info['type']==1) { ?><button type="button" class="btn btn-primary">银行卡</button><?php  } else { ?><button type="button" class="btn btn-success">微信余额</button><?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">申请时间</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  echo date('Y-m-d H:i:s', $apply_info['apply_time'])?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($supplierinfo['realname'])) { ?><?php  echo $supplierinfo['realname'];?><?php  } else { ?>未填写<?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">电话</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($supplierinfo['mobile'])) { ?><?php  echo $supplierinfo['mobile'];?><?php  } else { ?>未填写<?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">卡号</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($supplierinfo['banknumber'])) { ?><?php  echo $supplierinfo['banknumber'];?><?php  } else { ?>未填写<?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户名</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($supplierinfo['accountname'])) { ?><?php  echo $supplierinfo['accountname'];?><?php  } else { ?>未填写<?php  } ?>
                </div>
            </div>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户行</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <?php  if(!empty($supplierinfo['accountbank'])) { ?><?php  echo $supplierinfo['accountbank'];?><?php  } else { ?>未填写<?php  } ?>
                </div>
            </div>
            <?php  } ?>
            <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php  if($apply_info['status'] == 0) { ?>
                            <input type="submit" name="submit" value="通过" class="btn btn-primary col-lg-1"  />
                        <?php  } ?>
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <input type="button" name="back" onclick='history.back()' style='margin-left:10px;' value="返回列表" class="btn btn-default" />
                    </div>
            </div>
         </div>
    </div>   
</form>
<?php  } ?> 
<?php  } ?>
<?php  if($operation=='merchant') { ?>
<div class="rightlist">
<div class="panel panel-info">
    <div class="panel-heading"></div>
</div>
<div class="panel panel-default">
    <a class='btn btn-default' href="<?php  if(!empty($uid)) { ?><?php  echo $this->createPluginWebUrl('supplier/supplier', array('op' => 'merchant_post','uid' => $uid))?><?php  } else if(!empty($center_id)) { ?><?php  echo $this->createPluginWebUrl('supplier/supplier', array('op' => 'merchant_post','center_id' => $center_id))?><?php  } ?>">添加招商员</a> 
</div>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="panel-body">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
                <tr>
                    <th style='width:50px;'>ID</th>
                    <th style='width:100px;'>佣金比例</th>
                    <th style='width:50px;'>头像</th>
                    <th style='width:100px;'>昵称</th>
                    <th style='width:100px;'>真实姓名</th>
                    <?php  if(!empty($center_id)) { ?>
                    <th style='width:100px;'>供应商</th>
                    <?php  } ?>
                    <th style='width:150px;'>电话</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($merchants)) { foreach($merchants as $row) { ?>
                    <?php  if(!empty($row['supplier_uid'])) { ?>
                        <tr>
                            <td><?php  echo $row['id'];?></td>
                            <td><?php  echo $row['commissions'];?>%</td>
                            <td><img src="<?php  echo $row['avatar'];?>" style='width:30px;height:30px;padding:1px;border:1px solid #ccc' /><br/></td>
                            <td><?php  echo $row['nickname'];?></td>
                            <td><?php  echo $row['realname'];?></td>
                            <?php  if(!empty($center_id)) { ?>
                            <td><?php  echo $row['username'];?></td>
                            <?php  } ?>
                            <td><?php  echo $row['mobile'];?></td>
                            <td><a class='btn btn-default' href="<?php  echo $this->createPluginWebUrl('supplier/supplier', array('op' => 'merchant_delete','id' => $row['id']))?>">删除</a></td>
                        </tr>
                    <?php  } ?>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
</div>
<?php  } ?>
<?php  if($operation=='merchant_post') { ?>
<div class="rightlist">
<form <?php if(cv('commission.supplier.edit|commission.supplier.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $supplierinfo['uid'];?>">
    <input type="hidden" name="op" value="merchant_post">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="p" value="supplier" />
    <input type="hidden" name="method" value="supplier" />
    <div class='panel panel-default'>
        <div class='panel-heading'>
            招商员
        </div>
        <div class='panel-body'>
            <div class="form-group notice">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">微信角色</label>
                    <div class="col-sm-4">
                        <input type='hidden' id='noticeopenid' name='data[openid]' value="" />
                        <div class='input-group'>
                            <input type="text" name="saler" maxlength="30" value="<?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?>" id="saler" class="form-control" readonly />
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus-notice').modal();">选择角色</button>
                                <button class="btn btn-danger" type="button" onclick="$('#noticeopenid').val('');$('#saler').val('');$('#saleravatar').hide()">清除选择</button>
                            </div> 
                        </div>
                        <span id="saleravatar" class='help-block' <?php  if(empty($saler)) { ?>style="display:none"<?php  } ?>><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo $saler['avatar'];?>"/></span>
                        
                        <div id="modal-module-menus-notice"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择角色</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd-notice" placeholder="请输入昵称/姓名/手机号" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_members();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus-notice" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>
              
                    </div>
                </div>
<script language='javascript'>
  function search_members() {
     if( $.trim($('#search-kwd-notice').val())==''){
        $('#search-kwd-notice').attr('placeholder','请输入关键词');
         <!-- Tip.focus('#search-kwd-notice','请输入关键词'); -->
         return;
     }
    $("#module-menus-notice").html("正在搜索....")
    $.get('<?php  echo $this->createWebUrl('member/query')?>', {
      keyword: $.trim($('#search-kwd-notice').val())
    }, function(dat){
      $('#module-menus-notice').html(dat);
    });
  }
  function select_member(o) {
    $("#noticeopenid").val(o.openid);
    $("#saleravatar").show();
    $("#saleravatar").find('img').attr('src',o.avatar);
    $("#saler").val( o.nickname+ "/" + o.realname + "/" + o.mobile );
    $("#modal-module-menus-notice .close").click();
  }
</script>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">佣金比例</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="text" name="data[commissions]" class="form-control" value="" placeholder="请输入佣金比例(%)" />
                </div>
            </div>
            <?php  if(!empty($all_suppliers)) { ?>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">供应商</label>
                <div class="col-sm-9 col-xs-12">
                    <select name='data[supplier_uid]' class='form-control'>
                        <?php  if(is_array($all_suppliers)) { foreach($all_suppliers as $supplier) { ?>
                        <option value='<?php  echo $supplier['uid'];?>'><?php  echo $supplier['username'];?></option>
                        <?php  } } ?>
                    </select>
                </div>
            </div>
            <?php  } ?>
            <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                            <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                       <input type="button" name="back" onclick='history.back()' style='margin-left:10px;' value="返回列表" class="btn btn-default" />
                    </div>
            </div>
         </div>
    </div>   
</form>
</div> 
<?php  } ?>
</div> 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
