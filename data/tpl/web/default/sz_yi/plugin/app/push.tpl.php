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
    <?php  if($operation == 'display') { ?>
    <div class="main">
        <form id="baseform" method="post" class="form-horizontal form">
            <div class="rightlist">
                <div class="panel panel-default">
                    <!--<div class="panel-heading">APP客户端设置</div>-->
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead class="navbar-inner">
                            <tr>
                                <th style="width:60px;">ID</th>
                                <th>标题</th>
                                <!--                     <th>内容</th>
                                 -->                    <th>时间</th>
                                <th >操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  if(is_array($list)) { foreach($list as $row) { ?>
                            <tr>
                                <td><?php  echo $row['id'];?></td>
                                <td><?php  echo $row['name'];?></td>
                                <!--                     <td><?php  echo $row['content'];?></td>
                                 -->                    <td><?php  echo $row['time'];?></td>


                                <td style="text-align:left;">
                                    <a href="<?php  echo $this->createWebUrl('plugin/app', array('method'=>'push','op' => 'post', 'id' => $row['id']))?>" class="btn btn-default btn-sm"
                                       title=""><i class="fa fa-edit"></i></a>
                                    <?php if(cv('shop.push.delete')) { ?><a href="<?php  echo $this->createWebUrl('plugin/app', array('method'=>'push','op' => 'delete', 'id' => $row['id']))?>"class="btn btn-default btn-sm" onclick="return confirm('确认删除此推送?')" title="删除"><i class="fa fa-times"></i></a><?php  } ?>
                                </td>
                            </tr>
                            <?php  } } ?>
                            <tr>
                                <td colspan='6'>
                                    <?php if(cv('shop.push.add')) { ?>
                                    <a class='btn btn-default' href="<?php  echo $this->createWebUrl('plugin/app',array('method'=>'push','op'=>'post'))?>"><i class='fa fa-plus'></i> 添加推送</a>
                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    <?php  } ?>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <?php  echo $pager;?>
                    </div>
                </div>
            </div>
        </form>
        <script>
            require(['bootstrap'], function ($) {
                $('.btn').hover(function () {
                    $(this).tooltip('show');
                }, function () {
                    $(this).tooltip('hide');
                });
            });
        </script>
    </div>
    <?php  } else if($operation == 'post') { ?>
    <div class="main">
        <form id="baseform" method="post" class="form-horizontal form">
            <div class="rightlist">
                <div class="panel panel-default">
                    <div class="panel-heading">推送设置</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>推送标题</label>
                            <div class="col-sm-9 col-xs-12">

                                <input type="text" id='name' name="name" class="form-control" value="<?php  echo $item['name'];?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">摘要</label>
                            <div class="col-sm-9 col-xs-12">

                                <textarea name="description" class="form-control richtext" cols="70"><?php  echo $item['description'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">推送详情</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php  echo tpl_ueditor('content',$item['content'])?>
                                <textarea id='detail' style='display:none'><?php  echo $item['content'];?></textarea>
                            </div>
                        </div>

                        <div class="form-group"></div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.push' ,$item) ) { ?>
                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                <?php  } ?>
                                <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.push.add|shop.push.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php  } ?>

    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>