<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('logtabs', TEMPLATE_INCLUDEPATH)) : (include template('logtabs', TEMPLATE_INCLUDEPATH));?>
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
            <input type="hidden" name="p" value="return" />
            <input type="hidden" name="method" value="return_log" />
            <input type="hidden" name="returntype" value="<?php  echo $_GPC['returntype'];?>" />
            
            <div class="form-group">
                
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">ID</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <input type="text" class="form-control"  name="mid" value="<?php  echo $_GPC['mid'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">会员信息</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <input type="text" class="form-control"  name="realname" value="<?php  echo $_GPC['realname'];?>" placeholder='可搜索昵称/名称/手机号'/>
                    </div>
                </div>
                
                <!-- <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">状态</label>
                    <div class="col-sm-4">
                        <select name='status' class='form-control'>
                            <option value=''>返现状态</option>
                            <option value='0' <?php  if($_GPC['status']=='0') { ?>selected<?php  } ?>>未完成</option>
                            <option value='1' <?php  if($_GPC['status']=='1') { ?>selected<?php  } ?>>已完成</option>
                        </select>
                    </div>
                   

                </div> -->
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        
                        
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
                <th style='width:5%;'>编号</th>
                <th style='width:5%;'>会员ID</th>
                <th style='width:10%;'>会员姓名</th>
                <th style='width:20%;'>手机</th>
                <th style='width:20%;'>返现总金额</th>
                <th style='width:20%;'>返现时间</th>
                <th style='width:15%;'>状态</th>
            </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list_group)) { foreach($list_group as $row) { ?>
                    <tr>
                        <td><?php  echo $row['id'];?></td>
                        <td><?php  echo $row['mid'];?></td>
                        <td><?php  echo $row['realname'];?></td>
                        <td><?php  echo $row['mobile'];?></td>
                        <td><?php  echo $row['money'];?></td>
                        <td><?php  echo $row['create_time'];?></td>
                        <td><?php  if($row['status']==0) { ?>等待返现<?php  } else { ?>已完成<?php  } ?></td>

                    </tr>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
<?php  } ?>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>

