<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="rightlist">
<form id="setform"  action="" method="post" class="form-horizontal form">
    <div class='panel panel-default'>
        <div class='panel-heading'>
           单笔订单返现 会员等级分销商等级返现比例
        </div>

        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="radio-inline"><input type="radio"  name="setdata[islevels]" value="0" <?php  if($set['islevels'] ==0) { ?> checked="checked"<?php  } ?> /> 禁用</label>
                    <label class="radio-inline"><input type="radio"  name="setdata[islevels]" value="1" <?php  if($set['islevels'] ==1) { ?> checked="checked"<?php  } ?> /> 启用</label>
                    <span class='help-block'>如禁用则使用默认比例。</span>
                </div>
            </div>
        </div>
        <div class='panel-heading'>
           单笔订单返现 会员等级返现比例
        </div>

        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="radio-inline"><input type="radio"  name="setdata[islevel]" value="1" <?php  if($set['islevel'] ==1) { ?> checked="checked"<?php  } ?> /> 启用</label>
                </div>
            </div>
        </div>
        <?php  if(is_array($member_levels)) { foreach($member_levels as $level) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-6 col-xs-6">
                    <div class='input-group'>
                       <div class='input-group-addon'><?php  echo $level['levelname'];?></div>
                       <input type='text' name='setdata[member][level<?php  echo $level['id'];?>]' class="form-control"  value="<?php  echo $set['member']['level'.$level['id']]?>" />
                       <div class='input-group-addon'>%</div>

                   </div>
                </div>
            </div>   
        <?php  } } ?>

        <div class='panel-heading'>
           单笔订单返现 分销商等级返现比例
        </div>
        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="radio-inline"><input type="radio"  name="setdata[islevel]" value="2" <?php  if($set['islevel'] ==2) { ?> checked="checked"<?php  } ?> /> 启用</label>
                </div>
            </div>
        </div>

        <?php  if(is_array($distributor_levels)) { foreach($distributor_levels as $level) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-6 col-xs-6">
                    <div class='input-group'>
                       <div class='input-group-addon'><?php  echo $level['levelname'];?></div>
                       <input type='text' name='setdata[commission][level<?php  echo $level['id'];?>]' class="form-control"  value="<?php  echo $set['commission']['level'.$level['id']]?>" />
                       <div class='input-group-addon'>%</div>

                   </div>
                </div>
            </div>   
        <?php  } } ?>
            <div class="form-group"></div>
               <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
            <div class="col-sm-9">
                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            </div>

        </div>
        
    </div>
</form>
</div>
</div>


<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
