<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs"> 
    <?php if(cv('return.return_log')) { ?><li <?php  if($_GPC['returntype']=='1') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_log',array('returntype'=>'1'))?>">会员等级返现</a></li><?php  } ?>
    <?php if(cv('return.return_log')) { ?><li <?php  if($_GPC['returntype']=='2') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_log',array('returntype'=>'2'))?>">单笔订单返现</a></li><?php  } ?>
    <?php if(cv('return.return_log')) { ?><li <?php  if($_GPC['returntype']=='3') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_log',array('returntype'=>'3'))?>">订单累计金额返现</a></li><?php  } ?>
    <?php if(cv('return.return_log')) { ?><li <?php  if($_GPC['returntype']=='4') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_log',array('returntype'=>'4'))?>">队列排列返现</a></li><?php  } ?>
</ul>
</div>