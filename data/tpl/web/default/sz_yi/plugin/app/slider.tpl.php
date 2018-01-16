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
                   <!-- <div class="panel-heading">APP客户端设置</div>-->
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead class="navbar-inner">
                            <tr>
                                <th style="width:30px;">ID</th>
                                <th style='width:80px'>显示顺序</th>
                                <th>标题</th>
                                <th>连接</th>
                                <th>状态</th>
                                <th >操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  if(is_array($list)) { foreach($list as $row) { ?>
                            <tr>
                                <td><?php  echo $row['id'];?></td>
                                <td>
                                    <?php if(cv('shop.banner.edit')) { ?>
                                    <input type="text" class="form-control" name="displayorder[<?php  echo $row['id'];?>]" value="<?php  echo $row['displayorder'];?>">
                                    <?php  } else { ?>
                                    <?php  echo $row['displayorder'];?>
                                    <?php  } ?>
                                </td>

                                <td><?php  echo $row['advname'];?></td>
                                <td><?php  echo $row['link'];?></td>
                                <td>
                                    <?php  if($row['enabled']==1) { ?>
                                    <span class='label label-success'>显示</span>
                                    <?php  } else { ?>
                                    <span class='label label-danger'>隐藏</span>
                                    <?php  } ?>
                                </td>
                                <td style="text-align:left;">
                                    <?php if(cv('shop.banner.view|shop.banner.edit')) { ?><a href="<?php  echo $this->createWebUrl('plugin/app', array('method'=>'slider','op' => 'post', 'id' => $row['id']))?>" class="btn btn-default btn-sm"
                                                                                title="<?php if(cv('shop.banner.edit')) { ?>修改<?php  } else { ?>查看<?php  } ?>"><i class="fa fa-edit"></i></a><?php  } ?>
                                    <?php if(cv('shop.banner.delete')) { ?><a href="<?php  echo $this->createWebUrl('plugin/app', array('method'=>'slider','op' => 'delete', 'id' => $row['id']))?>"class="btn btn-default btn-sm" onclick="return confirm('确认删除此广告?')" title="删除"><i class="fa fa-times"></i></a><?php  } ?>
                                </td>
                            </tr>
                            <?php  } } ?>
                            <tr>
                                <td colspan='6'>
                                    <?php if(cv('shop.banner.add')) { ?>
                                    <a class='btn btn-default' href="<?php  echo $this->createWebUrl('plugin/app',array('method'=>'slider','op'=>'post'))?>"><i class='fa fa-plus'></i> 添加广告</a>
                                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                    <?php  } ?>
                                    <?php if(cv('shop.banner.edit')) { ?>
                                    <input name="submit" type="submit" class="btn btn-primary" value="提交排序">
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
                     <div class="panel-heading">广告设置</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">排序</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                                <span class='help-block'>数字越大，排名越靠前</span>
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
                                <?php  } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style="color:red">*</span>广告标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <input type="text" id='advname' name="advname" class="form-control" value="<?php  echo $item['advname'];?>" />
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $item['advname'];?></div>
                                <?php  } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">广告图片</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <?php  echo tpl_form_field_image('thumb', $item['thumb'])?>
                                <span class='help-block'>建议尺寸:1080 * 365, 大小<=4M , 请将所有广告图片尺寸保持一致</span>
                                <?php  } else { ?>
                                <?php  if(!empty($item['thumb'])) { ?>
                                <a href='<?php  echo tomedia($item['thumb'])?>' target='_blank'>
                                <img src="<?php  echo tomedia($item['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                                </a>
                                <?php  } ?>
                                <?php  } ?>
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">广告连接</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <input type="text" name="link" class="form-control" value="<?php  echo $item['link'];?>" />
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $item['link'];?></div>
                                <?php  } ?>
                            </div>
                        </div>
                        <div class="form-group" style="display: none">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否显示</label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <label class='radio-inline'>
                                    <input type='radio' name='enabled' value=1' checked  /> 是
                                </label>
                                <label class='radio-inline'>
                                    <input type='radio' name='enabled' value=0'  /> 否
                                </label>
                                <?php  } else { ?>
                                <div class='form-control-static'><?php  if(empty($item['enabled'])) { ?>否<?php  } else { ?>是<?php  } ?></div>
                                <?php  } ?>
                            </div>
                        </div>

                        <div class="form-group"></div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                            <div class="col-sm-9 col-xs-12">
                                <?php if( ce('shop.banner' ,$item) ) { ?>
                                <input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1"  />
                                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                                <?php  } ?>
                                <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.banner.add|shop.banner.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php  } ?>

    <?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>