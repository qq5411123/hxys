<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
    
    
    <?php if(cv('commission.agent')) { ?><li <?php  if($_GPC['method']=='agent') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/agent')?>">分销商管理</a></li><?php  } ?>
    <?php if(cv('commission.level')) { ?><li <?php  if($_GPC['method']=='level') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/level')?>">分销商等级</a></li><?php  } ?>
    <?php if(cv('commission.apply.view1')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==1 || $apply['status']==1)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/apply',array('status'=>1))?>">待审核提现</a></li><?php  } ?>
    <?php if(cv('commission.apply.view2')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==2 || $apply['status']==2)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/apply',array('status'=>2))?>">待打款提现</a></li><?php  } ?>
    <?php if(cv('commission.apply.view3')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==3 || $apply['status']==3)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/apply',array('status'=>3))?>">已打款提现</a></li><?php  } ?>    
    <?php if(cv('commission.apply.view3')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==4 || $apply['status']==4)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/apply',array('status'=>4))?>">已到款提现</a></li><?php  } ?>
    <?php if(cv('commission.apply.view_1')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==-1 || $apply['status']==-1)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/apply',array('status'=>-1))?>">无效提现</a></li><?php  } ?>
    <?php if(cv('commission.increase')) { ?><li  <?php  if($_GPC['method']=='increase') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/increase')?>">趋势统计</a></li><?php  } ?>
	<?php if(cv('commission.notice')) { ?><li  <?php  if($_GPC['method']=='notice') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/notice')?>">通知设置</a></li><?php  } ?>
    <?php if(cv('commission.cover')) { ?><li <?php  if($_GPC['method']=='cover') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/cover')?>">入口设置</a></li><?php  } ?>
    <?php if(cv('commission.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('commission/set')?>">基础设置</a></li><?php  } ?>
    
</ul>
</div>
