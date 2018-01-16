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
<?php  if($operation == 'post') { ?>
<div class="main rightlist">
    <form id="dataform" action="" method="post" class="form-horizontal form" >
        <input type="hidden" name="id" value="<?php  echo $su_info['id'];?>" />
        <div class='panel panel-default'>
            <div class='panel-heading'>
                供应商设置
            </div>
            <div class='panel-body'>
                 <div class="form-group">
                     <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span> 供应商用户名</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('perm.user' ,$su_info) ) { ?>
                        <input type="text" name="username" class="form-control" value="<?php  echo $su_info['username'];?>" <?php  if(!empty($su_info)) { ?>readonly<?php  } ?>/>
                                    <span class='help-block'>您可以直接输入系统已存在用户，且保证用户密码正确才能添加</span>
                               <?php  } else { ?>
                               <div class='form-control-static'><?php  echo $su_info['username'];?></div>
                               <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span style='color:red'>*</span>  供应商密码</label>
                    <div class="col-sm-9 col-xs-12">
                              <?php if( ce('perm.user' ,$su_info) ) { ?>
                        <input type="password" name="password" class="form-control" value="" autocomplete="off" />
                          <?php  } else { ?>
                               <div class='form-control-static'>********</div>
                               <?php  } ?>
                    </div>
                </div>
                <div class="form-group"></div>
                 <div class="form-group">
                    <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                         <?php if( ce('perm.user' ,$su_info) ) { ?>
                            <input type="hidden" name="uid" value="<?php  echo $su_info['uid'];?>" />
                        <input type="button" name="submit" value="提交" class="btn btn-primary col-lg-1" />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' <?php if(cv('perm.user.add|perm.user.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                    </div>
                </div>
                
                
            </div>
        </div>
        <div class="form-group col-sm-12">
         
        </div>
    </form>
</div>

<?php  } ?>
</div>
<script language='javascript'>
 
   $(function(){
     
        $('#dataform').ajaxForm();
        
        $(':input[name=submit]').click(function(){
            if($(this).attr('submitting')=='1'){
                return;
            }
      
           if ($(':input[name=username]').isEmpty()) {
                Tip.focus($(':input[name=username]'), '请填写用户名!');
                return;
            }
            <?php  if(empty($su_info)) { ?>
              if ($(':input[name=password]').isEmpty()) {
                Tip.focus($(':input[name=password]'), '请输入用户密码!');
                return;
            }
            <?php  } ?> 
    
            $(this).attr('submitting','1').removeClass('btn-primary');
            $('#dataform').ajaxSubmit(function(data){
                 data = eval("(" +  data  +")");
                if(data.result!=1){
                      $(this).removeAttr('submitting').addClass('btn-primary');
                      Tip.select($(':input[name=username]'), data.message );
                      return;
                }
                location.href= "<?php  echo $this->createPluginWebUrl('supplier/supplier')?>";
            })
        })
           
   })
  
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>