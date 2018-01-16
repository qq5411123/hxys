<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<div class="w1200 m0a">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('tabs', TEMPLATE_INCLUDEPATH)) : (include template('tabs', TEMPLATE_INCLUDEPATH));?>
<div class="rightlist">
<style type="text/css">
.input-group{
    width: 40%;
    float: left;
    margin-left: 90px
}
.col-md-2 {
    width: 120px;
}
</style>
<?php  if($operation == "display") { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="bonus" />
            <input type="hidden" name="method" value="detail" />
            <input type="hidden" name="op" value="display" />
            <input type="hidden" name="sn" value="<?php  echo $_GPC['sn'];?>" />
            <input type="hidden" name="isglobal" value="<?php  echo $_GPC['isglobal'];?>" />
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
    <div class="panel-heading">分红明细<button type="button" name="send" value="1" onclick="send(this)" data-toggle="modal" data-target="#modal-confirmsend" data-original-title="" class="btn btn-primary" style="float:right">发送消息</button></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:5%;'>会员id</th>
                    <th style='width:10%;'>粉丝</th>
                    <th style='width:10%;'>姓名</th>
                    <th style='width:10%;'>电话</th>
                    <th style='width:10%;'>账户余额</th>
                    <th style='width:10%;'>分红金额</th>
                    <th style='width:10%;'>账户积分</th>
                    <th style='width:10%;'>分红积分</th>
                    <th style='width:10%;'>打款方式</th>
                    <th style='width:15%;'>时间</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($logs)) { foreach($logs as $row) { ?>
                <tr>
                    <td><?php  echo $row['member_id'];?></td>
                    <td><img style="width:30px;height:30px;padding1px;border:1px solid #ccc" src="<?php  echo $row['avatar'];?>">
<?php  echo $row['nickname'];?> </td>
                    <td><?php  echo $row['realname'];?></td>
                    <td><?php  echo $row['mobile'];?></td>
                    <td><?php  echo $row['credit2'];?></td>
                    <td><?php  echo $row['money'];?></td>
                    <td><?php  echo $row['credit1'];?></td>
                    <td><?php  echo $row['integral'];?></td>
                    <td><?php  if($row['paymethod']==1) { ?>微信钱包<?php  } else { ?>账户余额<?php  } ?><?php  if(empty($row['sendpay'])) { ?>(打款失败)<?php  } ?></td>
                    <td><?php  echo date("Y-m-d H:i:s", $row['ctime'])?></td>
                </tr>
                <?php  } } ?>
            </tbody>
        </table>
        <?php  echo $pager;?>
    </div>
</div>
<!--群发消息-->
<div id="modal-confirmsend" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="width:600px;margin:0px auto;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h3>群发消息</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-xs-10 col-sm-3 col-md-3 control-label">共计人数</label>
                        <div class="col-xs-12 col-sm-9 col-md-8 col-lg-8">
                            <?php  echo $total;?>人
                        </div>
                    </div>
                    <div id="module-menus"></div>
                </div>
                <div class="modal-footer">
                    <input type="button" name="button" value="发送" class="btn btn-primary col-lg-4"  onclick="sendmsg()"/>
                </div>
            </div>
        </div>
</div>
<script>
    $(function(){
        $(':radio[name=send1]').click(function(){
            var v = $(this).val();
             $(".choose").hide();
             $(".choose_"+v).show();
        })
    })
  
     var openids = [];
    function sendmsg(){
          var btn = $('input[type=button]');
        if(btn.attr('sending')=='1'){
            return;
        }
        
        var c = $('input[name=send1]:checked').val();
        var v = $('#value_'+c).val();
        if(c==1 && v==''){
            alert('请输入要群发的用户Openid!');
            return;
        }

        btn.removeClass('btn-primary').val('正在获取发送的用户Openid...').attr('sending',1);
        $.ajax({
            url: "<?php  echo $this->createPluginWebUrl('bonus/detail')?>",
            type:'post',
            dataType:'json',
            data: {'op':'getopenids',sn:"<?php  echo $_GPC['sn'];?>"},
            success:function(result){
                openids = result.openids;
                btn.val('共要发送给 ' + openids.length + " 个用户，准备发送!");
                sendmessage();
            }
        });
    }
    var current = 0;
    var failed = [];
    var failmsg = "";
    var succeed = 0;
    function sendmessage(){
       var btn = $('input[type=button]');
          
        if(current>openids.length-1){
            if(failed.length>0){
                var msg = '发送成功 ' + succeed + ' 个用户，失败 ' + failed.length + " 个用户:\r\n";
                msg+=failmsg;
                msg+="\r\n 是否继续发送失败的用户? ";
               if(confirm(msg)) {
                   current = 0 ;succeed=0;
                   openids = failed;
                   failed=[];
                   failmsg= "";
                   btn.val('共要发送给 ' + openids.length + " 个用户，准备发送!");
                   sendmessage();
                   return;
               }
               location.reload();
               
            } else{
                alert('发送成功 ' + succeed + ' 个用户!' );
                location.reload();
            }
        }
        var openid = openids[current];
        $.ajax({
            url: "<?php  echo $this->createPluginWebUrl('bonus/detail')?>",
            type:'post',
            data: {'op':'sendmessage','openid':openid,sn:"<?php  echo $_GPC['sn'];?>"},
            dataType:'json',
            success:function(result2){
              if(result2.result=='1'){
                   succeed++;
              }
              else{
                  failmsg+= result2.openid + "\r\n(错误信息: " + result2.message + ")\r\n\r\n";
                  failed.push(result2.openid);
              }
              btn.val('已经发送 ' + current + " / " + openids.length + " 个用户...");
              current++;
              sendmessage();
          }
        });
    }
</script>

<?php  } else { ?>
<div class="panel panel-info">
    <div class="panel-heading">筛选</div>
    <div class="panel-body">
        <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
            <input type="hidden" name="c" value="site" />
            <input type="hidden" name="a" value="entry" />
            <input type="hidden" name="m" value="sz_yi" />
            <input type="hidden" name="do" value="plugin" />
            <input type="hidden" name="p" value="bonus" />
            <input type="hidden" name="method" value="detail" />
            <input type="hidden" name="op" value="list" />
            <div class="form-group">
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">分红类型</label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <select name='type' class='form-control'>
                            <option value=''>全部分红</option>
                            <option value='2' <?php  if($_GPC['type']=='2') { ?>selected<?php  } ?>>团队分红</option>
                            <option value='3' <?php  if($_GPC['type']=='3') { ?>selected<?php  } ?>>地区分红</option>
                            <option value='1' <?php  if($_GPC['type']=='1') { ?>selected<?php  } ?>>全球分红</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label"></label>
                    <div class="col-sm-8 col-lg-9 col-xs-12">
                        <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">分红金额共：<?php  if(!empty($totalmoney)) { ?><?php  echo $totalmoney;?>元<?php  } else { ?>0元<?php  } ?></div>
    <div class="panel-body">
        <table class="table table-hover">
            <thead class="navbar-inner">
                <tr>
                    <th style='width:10%;'>id</th>
                    <th style='width:10%;'>分红金额</th>
                    <th style='width:10%;'>分红人数</th>
                    <th style='width:10%;'>分红方式</th>
                    <th style='width:15%;'>打款方式</th>
                    <th style='width:10%;'>分红类型</th>
                    <th style='width:20%;'>分红时间</th>
                    <th style='width:15%;'>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php  if(is_array($list)) { foreach($list as $row) { ?>
                <tr>
                    <td><?php  echo $row['id'];?></td>
                    <td><?php  echo $row['money'];?></td>
                    <td><?php  echo $row['total'];?></td>
                    <td>
                        <?php  if($row['status'] == 0) { ?>
                            手动
                        <?php  } else if($row['status'] == 2) { ?>
                            自动
                        <?php  } else { ?>
                            ———
                        <?php  } ?>
                    </td>
                    <td>
                        <?php echo empty($row['paymethod']) ? "账户余额" : "微信钱包"?>
                        <?php  if($row['sendpay_error']==1 && !empty($row['status'])) { ?>(打款失败)<?php  } ?>
                    </td>
                    <td>
                        <?php  if($row['isglobal']==1) { ?>
                            全球分红
                        <?php  } else { ?>
                            <?php  if($row['type'] == 2) { ?>
                                团队分红
                            <?php  } else if($row['type'] == 3) { ?>
                                地区分红
                            <?php  } else { ?>
                                团队地区分红
                            <?php  } ?>
                        <?php  } ?>
                    </td>
                    <td><?php  echo date("Y-m-d H:i:s", $row['ctime'])?></td>
                    <td>
                        <a class="btn btn-default" href="<?php  echo $this->createPluginWebUrl('bonus/detail', array('sn' => $row['send_bonus_sn'], 'isglobal'=> $row['isglobal']))?>" data-original-title="" title="">
                        详情
                        </a>
                        <?php  if($row['sendpay_error']==1 && !empty($row['paymethod'])) { ?>
                        <a class="btn btn-default" href="<?php  echo $this->createPluginWebUrl('bonus/detail', array('sn' => $row['send_bonus_sn'],"op" => "afresh"))?>" data-original-title="" title="">
                        重发
                        </a>
                        <?php  } ?>
                    </td>
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
