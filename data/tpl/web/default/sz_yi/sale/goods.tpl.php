<?php defined('IN_IA') or exit('Access Denied');?><?php  if(p('love')) { ?>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">爱心基金</label>
    <div class="col-sm-4">
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div class='input-group'>
            <input type="text" name="love_money"  value="<?php  echo $item['love_money'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0，则不贡献</span>
          <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if(empty($item['love_money'])) { ?>不贡献<?php  } else { ?>贡献金额<?php  echo $item['love_money'];?>元<?php  } ?>
         </div>
          <?php  } ?>
    </div>   
</div>
<?php  } ?>
<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label"><?php  echo SZ_YI_INTEGRAL?>抵扣</label>
    <div class="col-sm-4">
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div class='input-group'>
            <span class="input-group-addon">最多抵扣</span>
            <input type="text" name="deduct"  value="<?php  echo $item['deduct'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0，则不支持<?php  echo SZ_YI_INTEGRAL?>抵扣</span>
          <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if(empty($item['deduct'])) { ?>不支持<?php  echo SZ_YI_INTEGRAL?>抵扣<?php  } else { ?>最多抵扣 <?php  echo $item['deduct'];?> 元 <?php  } ?>
         </div>
          <?php  } ?>
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">余额抵扣</label>
    <div class="col-sm-4"> 
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div class='input-group'>
            <span class="input-group-addon">最多抵扣</span>
            <input type="text" name="deduct2"  value="<?php  echo $item['deduct2'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0，则支持全额抵扣，设置-1 不支持余额抵扣</span>
          <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if(empty($item['deduct2'])) { ?>
			  支持全额抵扣
			  <?php  } else if($item['deduct2']==-1) { ?>
			  不支持余额抵扣
			  <?php  } else { ?>
			  最多抵扣 <?php  echo $item['deduct2'];?> 元 <?php  } ?>
         </div>
          <?php  } ?>
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">单品满件包邮</label>
    <div class="col-sm-4">
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div class='input-group'>
            <span class="input-group-addon">满</span>
            <input type="text" name="ednum"  value="<?php  echo $item['ednum'];?>" class="form-control" />
            <span class="input-group-addon">件</span>
        </div>
       <span class="help-block">如果设置0或空，则不支持满件包邮</span>
          <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if(empty($item['ednum'])) { ?>不支持满件包邮<?php  } else { ?>支持 <?php  } ?>
         </div>
          <?php  } ?>
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">单品满额包邮</label>
    <div class="col-sm-4">
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div class='input-group'>
            <span class="input-group-addon">满</span>
            <input type="text" name="edmoney"  value="<?php  echo $item['edmoney'];?>" class="form-control" />
            <span class="input-group-addon">元</span>
        </div>
       <span class="help-block">如果设置0或空，则不支持满额包邮</span>
          <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if(empty($item['edmoney'])) { ?>不支持满额包邮<?php  } else { ?>支持 <?php  } ?>
         </div>
          <?php  } ?>
    </div>   
</div> 

<div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 control-label">不参与单品包邮地区</label>
    <div class="col-sm-9 col-xs-12">
       <?php if( ce('shop.goods' ,$item) ) { ?>
        <div id="areas" class="form-control-static"><?php  echo $item['edareas'];?></div>
                           <a href="javascript:;" class="btn btn-default" onclick="selectAreas()">添加不参加满包邮的地区</a>
                           <input type="hidden" id='selectedareas' name="edareas" value="<?php  echo $item['edareas'];?>" />
       <span class="help-block">如果设置0或空，则不支持满件包邮</span>
          <?php  } else { ?>
      
            <div class='form-control-static'><?php  echo $set['enoughareas'];?></div>
       
          <?php  } ?>
    </div>   
</div> 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/selectareas', TEMPLATE_INCLUDEPATH)) : (include template('sale/selectareas', TEMPLATE_INCLUDEPATH));?>