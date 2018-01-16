<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="rightlist">
<form id="setform"  action="" method="post" class="form-horizontal form">
    <div class='panel panel-default'>
        <div class='panel-heading'>
           排列全返系统
        </div>

        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                     <label class="radio-inline queue"><input type="radio"  name="setdata[isqueue]" value="0" <?php  if($set['isqueue'] ==0) { ?> checked="checked"<?php  } ?> /> 关闭</label>
                    <label class="radio-inline queue"><input type="radio"  name="setdata[isqueue]" value="1" <?php  if($set['isqueue'] ==1) { ?> checked="checked"<?php  } ?> /> 开启</label>
                </div>
            </div>
        </div>

        <div class="form-group" id="return_queue" <?php  if($set['isqueue'] ==0) { ?> style="display:none;"<?php  } ?>>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">队列间隔</label>
            <div class="col-sm-8 col-xs-12">
                <input type="text" name="setdata[queue]" class="form-control" value="<?php  echo $set['queue'];?>"  />
                <span class='help-block'></span>
            </div>
        </div>

        <div class='panel-heading'>
           会员等级返现
        </div>

        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                     <label class="radio-inline"><input type="radio"  name="setdata[islevelreturn]" value="0" <?php  if($set['islevelreturn'] ==0) { ?> checked="checked"<?php  } ?> /> 关闭</label>
                    <label class="radio-inline"><input type="radio"  name="setdata[islevelreturn]" value="1" <?php  if($set['islevelreturn'] ==1) { ?> checked="checked"<?php  } ?> /> 开启</label>
                    <span class='help-block'>会员购物时 按会员等级进行返现。需要在商品里设置等级返现额度。</span>
                </div>
            </div>
        </div>

        <div class='panel-heading'>
           订单全返系统
        </div>
        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否开启</label>
                <div class="col-sm-8 col-xs-12">
                     <label class="radio-inline"><input type="radio"  name="setdata[isreturn]" value="0" <?php  if($set['isreturn'] ==0) { ?> checked="checked"<?php  } ?> /> 关闭</label>
                    <label class="radio-inline"><input type="radio"  name="setdata[isreturn]" value="1" <?php  if($set['isreturn'] ==1) { ?> checked="checked"<?php  } ?> /> 开启</label>
                </div>
            </div>
        </div>

        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现规则</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="radio-inline rule"><input type="radio"   name="setdata[returnrule]" value="1" <?php  if($set['returnrule'] !=2) { ?> checked="checked"<?php  } ?> /> 按单笔订单返现</label>
                    <label class="radio-inline rule"><input type="radio"   name="setdata[returnrule]" value="2" <?php  if($set['returnrule'] ==2) { ?> checked="checked"<?php  } ?> /> 按订单累计金额返现</label>
                    <span class='help-block'>按单笔订单返现是按订单金额加入返现队列，返现时是按照订单金额的百分比返现</br>
                    按订单累计金额返现是按用户累计订单金额达到指定额度时加入返现队列，返现时是按照前一天总待返金额的百分比返现<br>
                    </span>
                </div>

            </div>
        </div>      
<div id="return_rule" <?php  if($set['returnrule'] ==1) { ?> style="display:none;"<?php  } ?>>
        <div class="form-group"  >
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">订单累计额度</label>

            <div class="col-sm-8">
                <div class="input-group">
                    <!-- <div class="input-group-addon">提现到微信</div> -->
                    <input type="text" name="setdata[orderprice]" class="form-control" value="<?php  echo $set['orderprice'];?>"  />
                    <div class="input-group-addon">元</div>
                </div>
                <span class='help-block'>用户完成的订单总额累计达到此金额后加入全返队列</span>
            </div>
        </div> 

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现金额</label>
            <div class="col-sm-8 col-xs-12">
                 <label class="radio-inline"><input type="radio"  name="setdata[isprofit]" value="0" <?php  if($set['isprofit'] ==0) { ?> checked="checked"<?php  } ?> /> 营业额</label>
                <label class="radio-inline"><input type="radio"  name="setdata[isprofit]" value="1" <?php  if($set['isprofit'] ==1) { ?> checked="checked"<?php  } ?> /> 利润</label>
                <label class="radio-inline"><input type="radio"  name="setdata[isprofit]" value="2" <?php  if($set['isprofit'] ==2) { ?> checked="checked"<?php  } ?> />待返金额</label>
                <span class='help-block'>返现金额默认为上一个周期的营业额，开启利润为上一个周期的利润进行累计,开启待返金额则根据订单累计满额(如1000)，按照一定比例(万分之五)返现，超过但不足满额(如1000)的部分，等到累计满额(如1000)再返现，直到返完为止。</span>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">累计金额</label>
            <div class="col-sm-8 col-xs-12">
                 <label class="radio-inline"><input type="radio"  name="setdata[iscumulative]" value="0" <?php  if($set['iscumulative'] ==0) { ?> checked="checked"<?php  } ?> /> 订单金额</label>
                <label class="radio-inline"><input type="radio"  name="setdata[iscumulative]" value="1" <?php  if($set['iscumulative'] ==1) { ?> checked="checked"<?php  } ?> /> 排除赠送积分</label>
                <span class='help-block'>累计金额方式默认为订单金额，开启排除赠送积分为订单金额减去赠送的积分。</span>
            </div>
        </div>
</div>

        <div class="form-group"  id="return_degression" <?php  if($set['returnrule'] ==2) { ?> style="display:none;"<?php  } ?>>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">单笔订单递减返 是否开启</label>
                <div class="col-sm-8 col-xs-12">
                     <label class="radio-inline"><input type="radio"  name="setdata[degression]" value="0" <?php  if($set['degression'] ==0) { ?> checked="checked"<?php  } ?> /> 关闭</label>
                    <label class="radio-inline"><input type="radio"  name="setdata[degression]" value="1" <?php  if($set['degression'] ==1) { ?> checked="checked"<?php  } ?> /> 开启</label>
                    <span class='help-block'>如 ：返现金额为100元，第一次返现为（100*返现比例），第二次为（100-已返现金额*返现比例），以此类推。</br>当未返现金剩余0.5元时，将一次性返现给用户。</span>
                </div>

        </div> 

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">默认比例</label>

            <div class="col-sm-8">
                <div class="input-group">
                    <!-- <div class="input-group-addon">提现到微信</div> -->
                    <input type="text" name="setdata[percentage]" class="form-control" value="<?php  echo $set['percentage'];?>"  />
                    <div class="input-group-addon">%</div>
                </div>
                <span class='help-block'>开启单笔订单时按订单金额的百分比返现，开启订单累计金额返现时按前一天总营业额的百分比返现 </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">商家报单扣除比例</label>

            <div class="col-sm-8">
                <div class="input-group">
                    <!-- <div class="input-group-addon">提现到微信</div> -->
                    <input type="text" name="setdata[noadd]" class="form-control" value="<?php  echo $set['noadd'];?>"  />
                    <div class="input-group-addon">%</div>
                </div>
                <span class='help-block'>手工报单商家扣除比例 </span>
            </div>
        </div>
        <div class='panel-heading'>
           基础设置
        </div>
        <div class='panel-body'>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现规律</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="radio-inline law"><input type="radio"   name="setdata[returnlaw]" value="1" <?php  if($set['returnlaw'] ==1) { ?> checked="checked"<?php  } ?> /> 按天返现</label>

                    <label class="radio-inline law"><input type="radio"   name="setdata[returnlaw]" value="3" <?php  if($set['returnlaw'] ==3) { ?> checked="checked"<?php  } ?> /> 按周返现</label>

                    <label class="radio-inline law"><input type="radio"   name="setdata[returnlaw]" value="2" <?php  if($set['returnlaw'] ==2) { ?> checked="checked"<?php  } ?> /> 按月返现</label>
                    <span class='help-block'>按天返现会在返单加入队列后每天定时返现一次。按月返现会在返单加入队列后每月返现一次。</span>
                </div>
            </div>
        </div>

        <div class="form-group" id="return_time" <?php  if($set['returnlaw'] !=1) { ?> style="display:none;"<?php  } ?>>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现时间</label>
            <div class="col-sm-8 col-xs-12">
                <select id="returntime" class="form-control tpl-category-parent" name="setdata[returntime]">
                <?php  for ($i=0; $i <= 23; $i++) { if($set['returntime']==$i){?>
                    <option value="<?php  echo $i;?>" selected>每天<?php  echo $i;?>点执行返利</option>
                <?php  }else{?>
                    <option value="<?php  echo $i;?>" >每天<?php  echo $i;?>点执行返利</option>
                <?php  } }?>
                </select>
                 <span class='help-block'>返利会有延迟 最多延迟10分钟</span>
            </div>
        </div>

        <div class="form-group" id="return_time1" <?php  if($set['returnlaw'] !=3) { ?> style="display:none;"<?php  } ?>>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现时间</label>
            <div class="col-sm-8 col-xs-12">
                <select class="form-control tpl-category-parent" name="setdata[returntimezhou]">
                <?php  for ($i=1; $i <= 7; $i++) {  ?>
                <?php  $m=array('一','二','三','四','五','六','日');?>
                <?php  if($set['returntimezhou']==$i){ ?>
                    <option value="<?php  echo $i;?>" selected>每周<?php  echo $m[$i-1];?>执行返利</option>
                <?php  }else{?>
                    <option value="<?php  echo $i;?>" >每周<?php  echo $m[$i-1];?>执行返利</option>
                <?php  } }?>
                </select>
                 <span class='help-block'>返利会有延迟 最多延迟10分钟</span>
            </div>
        </div>
 
<script type="text/javascript">
    $('.queue').click(function(){
        if($(this).find('input').val() == 1)
        {
            $("#return_queue").show();
        }else
        {
            $("#return_queue").hide();
        }
    });
    $('.law').click(function(){
        if($(this).find('input').val() == 1)
        {
            $("#return_time").show();
        }else
        {
            $("#return_time").hide();
        }

        if($(this).find('input').val() == 3)
        {
            $("#return_time1").show();
        }else
        {
            $("#return_time1").hide();
        }
    });
    $('.rule').click(function(){
        if($(this).find('input').val() == 2)
        {
            $("#return_rule").show();
            $("#return_degression").hide();
            
        }else
        {
            $("#return_rule").hide();
            $("#return_degression").show();
        }
    });
</script>

<!--             <div class='panel-body'>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">返现到余额与积分</label>
                    <div class="col-sm-8 col-xs-12">
                        <label class="radio-inline"><input type="radio"  name="setdata[credit]" value="credit2" <?php  if($set['credit'] =='credit2') { ?> checked="checked"<?php  } ?> /> 余额</label>
                        <label class="radio-inline"><input type="radio"  name="setdata[credit]" value="credit1" <?php  if($set['credit'] =='credit1') { ?> checked="checked"<?php  } ?> /> 积分</label>
                        <span class='help-block'></span>
                    </div>
                </div>
            </div> -->


            <div class="form-group"></div>
               <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
            <div class="col-sm-9">
                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" onclick='return formcheck()' />
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            </div>
        </div>
        
    </div>
</form>
</div>
</div>

<script type="text/javascript">
    
    function formcheck(){
        var returnrule = $('input[name="setdata[returnrule]"]:checked ').val();
        if(returnrule == '2')
        {
            var orderprice = $('input[name="setdata[orderprice]"]').val();
            if(orderprice <= '0')
            {
                alert('订单累计额度不能为空！');
                return false;
            }
           
        }

    }
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
