<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<?php  if($trade['is_street'] == 1) { ?>
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade_street.js"></script>
<?php  } else { ?>
<script type="text/javascript" src="../addons/sz_yi/static/js/dist/area/cascade.js"></script>
<?php  } ?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="rightlist">
<?php  if($operation=='display') { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="bonus" />
            <input type="hidden" name="method" value="agent" />
            <input type="hidden" name="op" value="display" />
            <div class="form-group">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">ID</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">会员信息</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder='可搜索昵称/名称/手机号'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">是否关注</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <select name='followed' class='form-control'>
                            <option value=''></option>
                            <option value='0' <?php  if($_GPC['followed']=='0') { ?>selected<?php  } ?>>未关注</option>
                            <option value='1' <?php  if($_GPC['followed']=='1') { ?>selected<?php  } ?>>已关注</option>
                            <option value='2' <?php  if($_GPC['followed']=='2') { ?>selected<?php  } ?>>取消关注</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">推荐人</label>
                    <div class="col-sm-3">
                        <select name='parentid' class='form-control'>
                            <option value=''></option>
                            <option value='0' <?php  if($_GPC['parentid']=='0') { ?>selected<?php  } ?>>总店</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text"  class="form-control" name="parentname" value="<?php  echo $_GPC['parentname'];?>" placeholder='推荐人昵称/姓名/手机号'/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">代理商等级</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <select name='agentlevel' class='form-control'>
                            <option value=''></option>
                            <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                            <option value='<?php  echo $level['id'];?>' <?php  if($_GPC['agentlevel']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                            <?php  } } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">区域代理</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <select name='bonus_area' id="bonus_area" class='form-control'>
                            <option value=''>全部等级</option>
                            <option value='1' <?php  if($_GPC['bonus_area']=='1') { ?>selected<?php  } ?>>省级代理</option>
                            <option value='2' <?php  if($_GPC['bonus_area']=='2') { ?>selected<?php  } ?>>市级代理</option>
                            <option value='3' <?php  if($_GPC['bonus_area']=='3') { ?>selected<?php  } ?>>区级代理</option>
                            <?php  if($trade['is_street'] == 1) { ?>
                            <option value='4' <?php  if($_GPC['bonus_area']=='4') { ?>selected<?php  } ?>>街级代理</option>
                            <?php  } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group" id="form-area" style="display:none;">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">区域选择</label>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <select class="form-control tpl-province" id="sel-provance" onchange="selectCity();" name="reside[province]">
                            <option value="" selected="true">所在省份</option>
                        </select>
                    </div>
          
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <select class="form-control tpl-city" id="sel-city" onchange="selectcounty()" name="reside[city]"><option value="" selected="true">所在城市</option></select>
                    </div>
            
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <select class="form-control tpl-district" id="sel-area" <?php  if($trade['is_street'] == 1) { ?>onchange="selectstreet()"<?php  } ?> name="reside[district]"><option value="" selected="true">所在地区</option></select>
                    </div>
                    <?php  if($trade['is_street'] == 1) { ?>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <select class="form-control tpl-street" id="sel-street" name="reside[street]"><option value="" selected="true">所在街道</option></select>
                    </div>
                    <?php  } ?>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label"></label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php if(cv('bonus.agent.export')) { ?>
                        <!-- <button type="submit" name="export" value="1" class="btn btn-primary">导出 Excel</button> -->
                        <?php  } ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="panel-body">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
            <tr>
                <th style='width:5%;'>会员ID</th>
                <th style='width:8%;text-align: center;'>推荐人</th>
                <th style='width:8%;text-align: center;'>粉丝</th>
                <th style='width:12%;'>姓名<br/>手机号码</th>
                <th style='width:10%;'>代理商等级</th>
                <th style='width:15%;text-align: center;'>区域代理</th>
                <th style='width:12%;'>代理累计分红<br/>全部已发放分红</th>
                <th style='width:7%;;'>下级总数</th>
                <th style='width:7%;'>关注</th>
                <?php  if(p('love')) { ?>
                <th style='width:7%;'>审核</th>
                <?php  } ?>
                <th style='width:10%;'>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($list)) { foreach($list as $row) { ?>
            <tr>
                <td><?php  echo $row['id'];?></td>
                <td  <?php  if(!empty($row['agentid'])) { ?>title='ID: <?php  echo $row['agentid'];?>'<?php  } ?> style="text-align: center;">
                <?php  if(empty($row['agentid'])) { ?>
                <label class='label label-primary'>总店</label>
                <?php  } else { ?>
                <img src='<?php  echo $row['parentavatar'];?>' style='width:30px;height:30px;padding:1px;border:1px solid #ccc' /><br/><?php  echo $row['parentname'];?>
                <?php  } ?>
                </td>
                <td style="text-align: center;">
                    <?php  if(!empty($row['avatar'])) { ?>
                    <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding:1px;border:1px solid #ccc' /><br/>
                    <?php  } ?>
                    <?php  if(empty($row['nickname'])) { ?>未更新<?php  } else { ?><?php  echo $row['nickname'];?><?php  } ?>

                </td>

                <td><?php  echo $row['realname'];?> <br/> <?php  echo $row['membermobile'];?></td>
                <td><?php echo empty($row['levelname'])?'-':$row['levelname']?></td>
                <td  style="text-align: center;">
                    <?php  if($row['bonus_area'] == 0) { ?>无<?php  } ?>
                    <?php  if($row['bonus_area'] == 1) { ?>省级代理：<?php  echo $row['bonus_province'];?><?php  } ?>
                    <?php  if($row['bonus_area'] == 2) { ?>市级代理：<?php  echo $row['bonus_city'];?><?php  } ?>
                    <?php  if($row['bonus_area'] == 3) { ?>区级代理：<?php  echo $row['bonus_district'];?><?php  } ?>
                    <?php  if($row['bonus_area'] == 4) { ?>街级代理：<?php  echo $row['bonus_street'];?><?php  } ?>
                </td>
                <td><label class="label label-primary"><?php  echo $row['commission_total'];?></label><br/><label class="label label-danger"><?php  echo $row['commission_pay'];?></label></td>
                <td>
                    总计：<?php  echo $row['levelcount'];?> 人
                </td>
                <td>  <?php  if(empty($row['followed'])) { ?>
                    <?php  if(empty($row['uid'])) { ?>
                    <label class='label label-default'>未关注</label>
                    <?php  } else { ?>
                    <label class='label label-warning'>取消关注</label>
                    <?php  } ?>
                    <?php  } else { ?>
                    <label class='label label-success'>已关注</label>
                    <?php  } ?></td>
                <?php  if(p('love')) { ?>
                <td>  
                    <?php  if($row['isagency']==1) { ?>
                        <?php  if($row['agentblack']==1) { ?>
                        <span class="label label-default" style='color:#fff;background:black'>黑名单</span>
                        <?php  } else { ?>
                            <span class="label label-warning">已申请</span>
                        <?php  } ?>
                    <?php  } ?>
                    <?php  if($row['isagency']==2) { ?>
                    <span class="label label-success">已审批</span>
                    <?php  } ?>
                    <?php  if($row['isagency']==0) { ?>
                        <span class="label label-default">未申请</span>
                    <?php  } ?>
                </td>
                <?php  } ?>
                <td  style="overflow:visible;">

                    <div class="btn-group btn-group-sm">
                        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="javascript:;">操作 <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-menu-left" role="menu" style='z-index: 99999'>
                            <?php if(cv('member.member.view')) { ?><li><a href="<?php  echo $this->createWebUrl('member',array('op'=>'detail', 'id' => $row['id']));?>" title='会员信息'><i class='fa fa-user'></i> 会员信息</a></li>	<?php  } ?>
                            <?php if(cv('bonus.agent.view')) { ?><li><a href="<?php  echo $this->createPluginWebUrl('bonus/agent/detail',array('id' => $row['id']));?>" title='详细信息'><i class='fa fa-edit'></i> 详细信息</a>	</li>	<?php  } ?>
                            <?php if(cv('bonus.agent.order')) { ?><li><a  href="<?php  echo $this->createPluginWebUrl('bonus/order',array('op'=>'display','bonusagentid' => $row['id']));?>" title='推广订单'><i class='fa fa-list'></i> 分红订单</a></li><?php  } ?>
                            <?php if(cv('bonus.agent.user')) { ?><li><a  href="<?php  echo $this->createPluginWebUrl('bonus/agent/user',array('id' => $row['id']));?>"  title='推广下线'><i class='fa fa-users'></i> 推广下线</a></li><?php  } ?>
                            <?php if(cv('bonus.agent.user')) { ?><li><a  href="<?php  echo $this->createPluginWebUrl('bonus/goods_rank',array('id' => $row['id']));?>"  title='推广商品'><i class='fa fa-users'></i> 推广商品</a></li><?php  } ?>
                        </ul>
                    </div>
                </td>
            </tr>
            <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
      $("#bonus_area").change(function(){
        changearea();
      });
    });
    function changearea(){
        var area_val = $("#bonus_area").val();
        if(area_val==0){
            $("#form-area").hide();
        }else if(area_val==1){            
            $("#form-area").show();
            $("#sel-province").show();
            $("#sel-city").hide();
            $("#sel-area").hide();
            <?php  if($trade['is_street'] == 1) { ?>
                $("#sel-street").hide();
            <?php  } ?>
        }else if(area_val==2){            
            $("#form-area").show();
            $("#sel-province").show();
            $("#sel-city").show();
            $("#sel-area").hide();
            <?php  if($trade['is_street'] == 1) { ?>
                $("#sel-street").hide();
            <?php  } ?>
        }else if(area_val==3){            
            $("#form-area").show();
            $("#sel-province").show();
            $("#sel-city").show();
            $("#sel-area").show();  
            <?php  if($trade['is_street'] == 1) { ?>
                $("#sel-street").hide();
            <?php  } ?>
        }
        <?php  if($trade['is_street'] == 1) { ?>
            if(area_val==4){            
                $("#form-area").show();
                $("#sel-province").show();
                $("#sel-city").show();
                $("#sel-area").show();
                $("#sel-street").show();  
            }
        <?php  } ?>

    }
    changearea();
    cascdeInit("<?php  echo $_GPC['reside']['province'];?>", "<?php  echo $_GPC['reside']['city'];?>", "<?php  echo $_GPC['reside']['district'];?>");
</script>
<?php  } else if($operation=='detail') { ?>

<form <?php if(cv('bonus.agent.edit|bonus.agent.check')) { ?>action="" method='post'<?php  } ?> class='form-horizontal'>
<input type="hidden" name="id" value="<?php  echo $member['id'];?>">
<input type="hidden" name="op" value="detail">
<input type="hidden" name="c" value="site" />
<input type="hidden" name="a" value="entry" />
<input type="hidden" name="m" value="sz_yi" />
<input type="hidden" name="p" value="bonus" />
<input type="hidden" name="method" value="agent" />
<input type="hidden" name="op" value="detail" />
<div class='panel panel-default'>
    <div class='panel-heading'>
        代理商详细信息
    </div>
    <div class='panel-body'>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">粉丝</label>
            <div class="col-sm-9 col-xs-12">
                <img src='<?php  echo $member['avatar'];?>' style='width:100px;height:100px;padding:1px;border:1px solid #ccc' />
                <?php  echo $member['nickname'];?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">OPENID</label>
            <div class="col-sm-9 col-xs-12">
                <div class="form-control-static"><?php  echo $member['openid'];?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">真实姓名</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <input type="text" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[realname]" class="form-control" value="<?php  echo $member['realname'];?>"  />
                <div class='form-control-static'><?php  echo $member['realname'];?></div>
                <?php  } ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <input type="text" name="data[membermobile]" class="form-control" value="<?php  echo $member['membermobile'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[membermobile]" class="form-control" value="<?php  echo $member['membermobile'];?>"  />
                <div class='form-control-static'><?php  echo $member['membermobile'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">绑定手机</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <div class='form-control-static'><?php  echo $member['mobile'];?></div>
                <?php  } else { ?>
                <div class='form-control-static'><?php  echo $member['mobile'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">微信号</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <input type="text" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                <?php  } else { ?>
                <input type="hidden" name="data[weixin]" class="form-control" value="<?php  echo $member['weixin'];?>"  />
                <div class='form-control-static'><?php  echo $member['weixin'];?></div>
                <?php  } ?>
            </div>
        </div>
        <?php  if(p('love') && !empty($check_imgs)) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">申请信息</label>
            <div class="col-sm-9 col-xs-12">
                <?php  if(is_array($check_imgs)) { foreach($check_imgs as $img) { ?>
                <?php  if(!empty($img)) { ?>
                <a href="<?php  echo $img;?>" target="_blank"><img src="<?php  echo $img;?>" style="height:100px;width:100px;"></a>
                <?php  } ?>
                <?php  } } ?>
            </div>
        </div>
        <?php  if($member['isagency']==1) { ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">审核</label>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                    <label class="radio-inline"><input type="radio" class="isglobal" name="isagency" value="0" checked="checked" /> 驳回</label>
                    <label class="radio-inline"><input type="radio" class="isglobal"  name="isagency" value="1" /> 通过</label>
                </label>
            </div>
        </div>
        <?php  } ?>
        <?php  } ?>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理商等级</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <select name='data[bonuslevel]' class='form-control'>
                    <option value='0'><?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?></option>
                    <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                    <option value='<?php  echo $level['id'];?>' <?php  if($member['bonuslevel']==$level['id']) { ?>selected<?php  } ?>><?php  echo $level['levelname'];?></option>
                    <?php  } } ?>
                </select>
                <?php  } else { ?>
                <input type="hidden" name="data[bonuslevel]" class="form-control" value="<?php  echo $member['bonuslevel'];?>"  />

                <?php  if(empty($member['agentlevel'])) { ?>
                <?php echo empty($this->set['levelname'])?'普通等级':$this->set['levelname']?>
                <?php  } else { ?>
                <?php  echo pdo_fetchcolumn('select levelname from '.tablename('sz_yi_bonus_level').' where id=:id limit 1',array(':id'=>$member['bonuslevel']))?>
                <?php  } ?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">区域代理</label>
            <div class="col-sm-4">
                <?php if(cv('bonus.agent.edit')) { ?>
                <select  class="form-control" id="bonus_area" name="data[bonus_area]">
                    <option value="0" <?php  if(empty($member['bonus_area'])) { ?>selected<?php  } ?>>不选择</option>
                    <option value="1" <?php  if($member['bonus_area']==1) { ?>selected<?php  } ?>>省级代理</option>
                    <option value="2" <?php  if($member['bonus_area']==2) { ?>selected<?php  } ?> >市级代理</option>
                    <option value="3" <?php  if($member['bonus_area']==3) { ?>selected<?php  } ?> >区级代理</option>
                    <?php  if($trade['is_street'] == 1) { ?>
                    <option value="4" <?php  if($member['bonus_area']==4) { ?>selected<?php  } ?> >街级代理</option>
                    <?php  } ?>
                </select>
                <?php  } else { ?>
                <input type="hidden" name="data[bonus_area]" class="form-control" value="<?php  echo $member['bonus_area'];?>"  />
                <div class='form-control-static'><?php  if(empty($member['bonus_area'])) { ?>无<?php  } ?><?php  if($member['bonus_area']==1) { ?>省级代理<?php  } ?><?php  if($member['bonus_area']==2) { ?>市级代理<?php  } ?><?php  if($member['bonus_area']==3) { ?>区级代理<?php  } ?><?php  if($member['bonus_area']==4) { ?>街级代理<?php  } ?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group form-area" <?php  if(empty($member['bonus_area'])) { ?>style="display:none;"<?php  } ?>>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">代理区域</label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit')) { ?>
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <select class="form-control tpl-province" id="sel-provance" onchange="selectCity();" name="reside[province]">
                        <option value="" selected="true">所在省份</option>
                    </select>
                </div>
      
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <select class="form-control tpl-city" id="sel-city" onchange="selectcounty()" name="reside[city]"><option value="" selected="true">所在城市</option></select>
                </div>
        
                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <select class="form-control tpl-district" id="sel-area" <?php  if($trade['is_street'] == 1) { ?>onchange="selectstreet()"<?php  } ?> name="reside[district]"><option value="" selected="true">所在地区</option></select>
                </div>

                <?php  if($trade['is_street'] == 1) { ?>
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                        <select class="form-control tpl-street" id="sel-street" name="reside[street]"><option value="" selected="true">所在街道</option></select>
                    </div>
                <?php  } ?>

                <?php  } else { ?>
                <input type="hidden" name="reside[province]" class="form-control" value="<?php  echo $member['bonus_province'];?>"  />
                <div class='form-control-static'><?php  echo $member['bonus_province'];?></div>
                <input type="hidden" name="reside[city]" class="form-control" value="<?php  echo $member['bonus_city'];?>"  />
                <div class='form-control-static'><?php  echo $member['bonus_city'];?></div>
                <input type="hidden" name="reside[district]" class="form-control" value="<?php  echo $member['bonus_area'];?>"  />
                <div class='form-control-static'><?php  echo $member['bonus_area'];?></div>
                <input type="hidden" name="reside[street]" class="form-control" value="<?php  echo $member['bonus_street'];?>"  />
                <div class='form-control-static'><?php  echo $member['bonus_street'];?></div>
                <?php  } ?>    
            </div>
        </div>
        <div class="form-group form-area">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                <div class="col-sm-4">
                    <?php if(cv('bonus.agent.edit')) { ?>
                    <div class="input-group">
                        <div class="input-group-addon">区域代理</div>
                        <input type="text" name="data[bonus_area_commission]" class="form-control" value="<?php  echo $member['bonus_area_commission'];?>"  />
                        <div class="input-group-addon">%</div>
                    </div>
                    <span class='help-block'>如不设置，则为基础设置中默认比例值</span>
                    <?php  } else { ?>
                    <input type="hidden" name="data[bonus_area_commission]" class="form-control" value="<?php  echo $member['bonus_area_commission'];?>"  />
                    <div class='form-control-static'><?php  echo $member['bonus_area_commission'];?></div>
                    <?php  } ?>
                    
                </div>
            </div>
        <script type="text/javascript">
            $(document).ready(function(){
              $("#bonus_area").change(function(){
                changearea();
              });
            });
            function changearea(){
                var area_val = $("#bonus_area").val();
                if(area_val==0){
                    $(".form-area").hide();
                }else if(area_val==1){            
                    $(".form-area").show();
                    $(".form-province").show();
                    $(".tpl-city").hide();
                    $(".tpl-district").hide();
                    <?php  if($trade['is_street'] == 1) { ?>
                        $(".tpl-street").hide(); 
                    <?php  } ?>
                }else if(area_val==2){            
                    $(".form-area").show();
                    $(".form-province").show();
                    $(".tpl-city").show();
                    $(".tpl-district").hide();
                    <?php  if($trade['is_street'] == 1) { ?>
                        $(".tpl-street").hide(); 
                    <?php  } ?>
                }else if(area_val==3){            
                    $(".form-area").show();
                    $(".form-province").show();
                    $(".tpl-city").show();
                    $(".tpl-district").show();  
                    <?php  if($trade['is_street'] == 1) { ?>
                        $(".tpl-street").hide(); 
                    <?php  } ?>
                }

                <?php  if($trade['is_street'] == 1) { ?>
                    if(area_val==4){            
                        $(".form-area").show();
                        $(".form-province").show();
                        $(".tpl-city").show();
                        $(".tpl-district").show(); 
                        $(".tpl-street").show();  
                    }
                <?php  } ?>
        
            }
            changearea();
            cascdeInit("<?php  echo $member['bonus_province'];?>", "<?php  echo $member['bonus_city'];?>", "<?php  echo $member['bonus_district'];?>", "<?php  echo $member['bonus_street'];?>");
        </script>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">累计分红佣金</label>
            <div class="col-sm-9 col-xs-12">
                <div class='form-control-static'> <?php  echo $member['commission_total'];?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">发放分红佣金</label>
            <div class="col-sm-9 col-xs-12">
                <div class='form-control-static'> <?php  echo $member['commission_pay'];?></div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">注册时间</label>
            <div class="col-sm-9 col-xs-12">
                <div class='form-control-static'><?php  echo date('Y-m-d H:i:s', $member['createtime']);?></div>
            </div>
        </div>
    </div>

    <?php  if($diyform_flag == 1) { ?>
    <div class='panel-heading'>
        自定义表单信息
    </div>
    <div class='panel-body'>
        <!--<span>diyform</span>-->

        <?php  $datas = iunserializer($member['diybonusdata'])?>
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
    <?php  } ?>

    <div class='panel-body'>

        <div class="form-group"></div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
            <div class="col-sm-9 col-xs-12">
                <?php if(cv('bonus.agent.edit|bonus.agent.check')) { ?>
                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <?php  } ?>
                <input type="button" name="back" onclick='history.back()' <?php if(cv('bonus.agent.edit|bonus.agent.check')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
            </div>
        </div>
    </div>
</div>
</form>
<?php  } ?>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>

