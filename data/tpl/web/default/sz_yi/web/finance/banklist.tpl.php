<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/finance/tabs', TEMPLATE_INCLUDEPATH)) : (include template('web/finance/tabs', TEMPLATE_INCLUDEPATH));?>
 
<div class="rightlist">
<div class="panel panel-default">
    <div class="panel-heading">银联支持列表</div>
    <div class="panel-body ">
        <a class='btn btn-default' href="<?php  echo $this->createWebUrl('finance/banklist',array('op'=>'adds'));?>" style="margin-bottom: 2px">添加银行</a>
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:8%;'></th>
                    <th style='width:12%;'>支持银行</th>
                    <th style='width:13%;'>银行LOGO</th>
                    <th style='width:13%;'>是否显示</th>
                    <th style='width:12%;'>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td></td>
                    <td><?php  echo $row['bank_name'];?></td>
                    <td   class='hidden-xs'><img src='../attachment/<?php  echo $row['bank_logo'];?>' style='width:200px;height:60px;padding:1px;' /></td>
                    <?php  if($row['is_show'] == 1) { ?>
                    <td>可用</td>
                    <?php  } else { ?>
                    <td>不可用</td>
                    <?php  } ?>
                    <td>
                        <a class='btn btn-default' href="<?php  echo $this->createWebUrl('finance/banklist',array('op'=>'adds','id' => $row['id']));?>" style="margin-bottom: 2px">修改</a>
                        <a class='btn btn-default' href="<?php  echo $this->createWebUrl('finance/banklist',array('op'=>'delete','id' => $row['id']));?>" style="margin-bottom: 2px">删除</a>	
                    </td>
                </tr>
                <?php  } } ?>
            </tbody>
        </table>

           <?php  echo $pager;?>
    </div>
</div>
</div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
