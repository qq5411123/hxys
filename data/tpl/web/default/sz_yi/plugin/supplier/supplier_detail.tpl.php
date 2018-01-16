<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="rightlist">
<form <?php  if('member.member.edit') { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
    <input type="hidden" name="id" value="<?php  echo $member['id'];?>">
    <input type="hidden" name="op" value="detail">
    <input type="hidden" name="c" value="site" />
    <input type="hidden" name="a" value="entry" />
    <input type="hidden" name="m" value="sz_yi" />
    <input type="hidden" name="do" value="member" />
    <div class='panel panel-default'>
        <?php  if($diyform_flag == 1) { ?>
        <div class='panel-heading'>
            详细信息
        </div>
        <div class='panel-body'>
            <?php  $datas = iunserializer($supplier['diymemberdata'])?>
            <?php  if(!empty($avatar)) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">头像</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static">
                    <img src="<?php  echo $avatar;?>" style="width:100px;height:100px;border:1px solid #ccc;padding:1px" /><br/>
                </div>
            </div>
            <?php  } ?>
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
                <input type="text" name="data[realname]" class="form-control" value="<?php  echo $supplier['realname'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[realname]" class="form-control" value="<?php  echo $supplier['realname'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['realname'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码</label>
            <div class="col-sm-9 col-xs-12">
                   <?php if(cv('commission.supplier.edit')) { ?>
                <input type="text" name="data[mobile]" class="form-control" value="<?php  echo $supplier['mobile'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[mobile]" class="form-control" value="<?php  echo $supplier['mobile'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['mobile'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">产品名称</label>
            <div class="col-sm-9 col-xs-12">
                   <?php if(cv('commission.supplier.edit')) { ?>
                <input type="text" name="data[productname]" class="form-control" value="<?php  echo $supplier['productname'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[productname]" class="form-control" value="<?php  echo $supplier['productname'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['productname'];?></div>
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
                <input type="text" name="data[banknumber]" class="form-control" value="<?php  echo $supplier['banknumber'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[banknumber]" class="form-control" value="<?php  echo $supplier['banknumber'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['banknumber'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户名</label>
            <div class="col-sm-9 col-xs-12">
                   <?php if(cv('commission.supplier.edit')) { ?>
                <input type="text" name="data[accountname]" class="form-control" value="<?php  echo $supplier['accountname'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[accountname]" class="form-control" value="<?php  echo $supplier['accountname'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['accountname'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">开户银行</label>
            <div class="col-sm-9 col-xs-12">
                   <?php if(cv('commission.supplier.edit')) { ?>
                <input type="text" name="data[accountbank]" class="form-control" value="<?php  echo $supplier['accountbank'];?>"  />
                   <?php  } else { ?>
                   <input type="hidden" name="data[accountbank]" class="form-control" value="<?php  echo $supplier['accountbank'];?>"  />
                <div class='form-control-static'><?php  echo $supplier['accountbank'];?></div>
                <?php  } ?>
            </div>
        </div>
        <?php  } ?>
        <div class='panel-body'>
          <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-9 col-xs-12">
                <input type="button" class="btn btn-default" name="submit" onclick="history.go(-1)" value="返回列表" <?php if(cv('member.member.edit')) { ?>style='margin-left:10px;'<?php  } ?> />
                </div>
            </div>
         </div>

    </div>   
    
</form>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>

