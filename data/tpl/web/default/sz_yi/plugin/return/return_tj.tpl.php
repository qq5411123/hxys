<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
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
            <input type="hidden" name="p" value="return" />
            <input type="hidden" name="method" value="return_tj" />
            <input type="hidden" name="op" value="display" />
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
    <div class="panel-heading">总数：<?php  echo $total;?> 昨日成交点单金额：<?php  echo $ordermoney;?>元 昨日利润金额：<?php  echo $profit;?>元 总返单数：<?php  echo $totals;?></div>
    <div class="panel-body">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
            <tr>
                <th style='width:80px;'>会员ID</th>
                <th style='width:120px;'>会员姓名</th>
                <th style='width:120px;'>预计返现总金额</th>
                <th style='width:120px;'>实际返现总金额</th>
                <th style='width:120px;'>未返现金额</th>
                <th style='width:120px;'>操作</th>
                <!-- <th>操作</th> -->
            </tr>
            </thead>
            <tbody>
            <?php  if(is_array($asd)) { foreach($asd as $row) { ?>
            <tr>
                <td><?php  echo $row['mid'];?></td>

                <td>
                    <?php  if(!empty($row['avatar'])) { ?>
                    <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                    <?php  } ?>
                    <?php  if(!empty($row['realname'])) { ?><?php  echo $row['realname'];?><?php  } ?>

                </td>

                <td><?php  echo $row['money1'];?>元</td>
                <td><?php  echo $row['return_money1'];?>元</td>
                <td><?php  echo $row['unreturnmoney'];?>元</td>
                <td>
                 <a class='btn btn-default' href="<?php  echo $this->createPluginWebUrl('return/return_tj/detail',array('mid' => $row['mid']));?>">详情</a>        
<!--                     <?php  if($row['status']==1) { ?>
                    <label class='label label-success'>已返利完成</label>
                    <?php  } else { ?>
                    <label class='label label-default'>未返利完成</label>
                    <?php  } ?> -->
                </td>

            </tr>
            <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
<?php  } else if($operation=='detail') { ?>
<div class="panel panel-default">
    <div class="panel-heading">总数：<?php  echo $total;?></div>
    <div class="panel-body">
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner" >
            <tr>
                <th style='width:80px;'>编号</th>
                <th style='width:80px;'>会员ID</th>
                <th style='width:120px;'>会员姓名</th>
                <th style='width:120px;'>预计返现总金额</th>
                <th style='width:120px;'>实际返现总金额</th>
                <th style='width:120px;'>未返现金额</th>
                <th style='width:120px;'>返利开始时间</th>
                <th style='width:120px;'>状态</th>
                <th style='width:120px;'>操作</th>
                <!-- <th>操作</th> -->
            </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list_group)) { foreach($list_group as $row) { ?>
                    <tr>
                        <td><?php  echo $row['id'];?></td>
                        <td><?php  echo $row['mid'];?></td>
                        <td>
                            <?php  if(!empty($row['avatar'])) { ?>
                            <img src='<?php  echo $row['avatar'];?>' style='width:30px;height:30px;padding1px;border:1px solid #ccc' />
                            <?php  } ?>
                            <?php  if(!empty($row['realname'])) { ?><?php  echo $row['realname'];?><?php  } else if(!empty($row['nickname'])) { ?><?php  echo $row['nickname'];?><?php  } ?>

                        </td>
                        <td><?php  echo $row['money'];?>元</td>
                        <td><?php  echo $row['return_money'];?>元</td>
                        <td><?php  if($row['unreturnmoney']==0) { ?>已返完<?php  } else { ?><?php  echo $row['unreturnmoney'];?>元<?php  } ?></td>
                        <td><?php  echo $row['create_time'];?></td>
                        <td>
       
                             <?php  if($row['status']==1) { ?>
                            <label class='label label-success'>已完成返利</label>
                            <?php  } else { ?>
                            <label class='label label-default'>未完成返利</label>
                            <?php  } ?>
                        </td>
                        <td><a href="<?php  echo $this->createPluginWebUrl('return/return_tj', array('id' => $row['id'], 'op' => 'delete'))?>" onclick="return confirm('确认删除此返单？');return false;" class="btn btn-default  btn-sm" title="删除"><i class="fa fa-times"></i></a></td>
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

