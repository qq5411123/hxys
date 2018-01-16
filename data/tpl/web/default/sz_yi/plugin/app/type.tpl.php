<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
    <!--/wwwroot/addons/sz_yi/template/web/sysset/-->
    <div class="main rightlist">
        <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data" >
            <input type='hidden' name='setid' value="<?php  echo $set['id'];?>" />
            <input type='hidden' name='op' value="type" />
            <div class="panel panel-default">
                <div class='panel-body'>
                    <div class="alert alert-danger">
                        此插件为第三方插件,出现资金安全问题本商城不承担任何责任.
                    </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">微信支付</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if(cv('sysset.save.pay')) { ?>
                        <label class='radio-inline'><input type='radio' name='pay[app_weixin]' value='1' <?php  if($set['pay']['app_weixin']==1) { ?>checked<?php  } ?>/> 开启</label>
                        <label class='radio-inline'><input type='radio' name='pay[app_weixin]' value='0' <?php  if($set['pay']['app_weixin']==0) { ?>checked<?php  } ?> /> 关闭</label>
                        <?php  } else { ?>
                        <input type="hidden" name="pay[app_weixin]" value="<?php  echo $set['pay']['app_weixin'];?>"/>
                        <div class='form-control-static'> <?php  if($set['pay']['app_weixin']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?></div>
                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label">支付宝支付</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if(cv('sysset.save.pay')) { ?>
                        <label class='radio-inline'><input type='radio' name='pay[app_alipay]' value='1' <?php  if($set['pay']['app_alipay']==1) { ?>checked<?php  } ?>/> 开启</label>
                        <label class='radio-inline'><input type='radio' name='pay[app_alipay]' value='0' <?php  if($set['pay']['app_alipay']==0) { ?>checked<?php  } ?> /> 关闭</label>
                        <?php  } else { ?>
                        <input type="hidden" name="pay[app_alipay]" value="<?php  echo $set['pay']['app_alipay'];?>"/>
                        <div class='form-control-static'> <?php  if($set['pay']['app_alipay']==1) { ?>开启<?php  } else { ?>关闭<?php  } ?></div>
                        <?php  } ?>
                    </div>
                </div>

                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">Ping++应用ID</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="ping[partner]" class="form-control" value="<?php  echo $pay['ping']['partner'];?>" autocomplete="off">
                            <span class="help-block">Ping++ 系统中你的应用标识。<br>如果您还未签约，<a href="https://dashboard.pingxx.com/register" target="_blank">请点击这里签约</a>；如果已签约,<a href="https://dashboard.pingxx.com/list" target="_blank">请点击这里获取应用ID</a></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-2 control-label">Ping++交易密钥</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" name="ping[secret]" class="form-control" value="<?php  echo $pay['ping']['secret'];?>" autocomplete="off">
                            <span class="help-block">Ping++Server端交易密钥,分为测试环境和真实环境2种类型交易密钥</span>
                        </div>
                    </div>

                <div class="form-group"></div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if(cv('sysset.save.pay')) { ?>
                        <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                    </div>
                </div>

            </div>
            <script language="javascript">
                $(function () {
                    $(":radio[name='pay[weixin]']").click(function () {
                        if ($(this).val() == 1) {
                            $("#certs").show();
                        }
                        else {
                            $("#certs").hide();
                        }
                    })

                })
            </script>
    </div>
    </form>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>     
