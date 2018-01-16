<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs"> 
    <?php if(cv('return.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/set')?>">全返设置</a></li><?php  } ?>

    <?php if(cv('return.level')) { ?><li <?php  if($_GPC['method']=='level') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/level')?>">会员等级及分销商等级返现比例 </a></li><?php  } ?>

    <?php if(cv('return.return_tj')) { ?><li <?php  if($_GPC['method']=='return_tj') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_tj')?>">全返统计</a></li><?php  } ?>
    <?php if(cv('return.queue')) { ?><li <?php  if($_GPC['method']=='queue') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/queue')?>">排列统计</a></li><?php  } ?>
	<?php if(cv('return.return_log')) { ?><li <?php  if($_GPC['method']=='return_log') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/return_log')?>" target="_blank">返现记录</a></li><?php  } ?>
    <?php if(cv('return.notice')) { ?><li <?php  if($_GPC['method']=='notice') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('return/notice')?>">通知设置 </a></li><?php  } ?>
</ul>
</div>