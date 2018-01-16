<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
<ul class="nav nav-tabs">
	<?php if(cv('bonus.agent')) { ?><li <?php  if($_GPC['method']=='agent') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/agent')?>">代理商管理</a></li><?php  } ?>
	<?php if(cv('bonus.level')) { ?><li <?php  if($_GPC['method']=='level') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/level')?>">代理商等级</a></li><?php  } ?>
	<?php  if(p('love')) { ?>
	 <?php if(cv('bonus.apply.view1')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==1 || $apply['status']==1)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/apply',array('status'=>1))?>">待审核提现</a></li><?php  } ?>
    <?php if(cv('bonus.apply.view2')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==2 || $apply['status']==2)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/apply',array('status'=>2))?>">待打款提现</a></li><?php  } ?>
    <?php if(cv('bonus.apply.view3')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==3 || $apply['status']==3)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/apply',array('status'=>3))?>">已打款提现</a></li><?php  } ?>    
    <?php if(cv('bonus.apply.view3')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==4 || $apply['status']==4)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/apply',array('status'=>4))?>">已到款提现</a></li><?php  } ?>
    <?php if(cv('bonus.apply.view_1')) { ?><li <?php  if($_GPC['method']=='apply' && ($_GPC['status']==-1 || $apply['status']==-1)) { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/apply',array('status'=>-1))?>">无效提现</a></li><?php  } ?>
    <?php  } else { ?>
	<?php if(cv('bonus.send')) { ?><li <?php  if($_GPC['method']=='send') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/send')?>">团队分红</a></li><?php  } ?>
    <?php if(cv('bonus.sendarea')) { ?><li <?php  if($_GPC['method']=='sendarea') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/sendarea')?>">地区分红</a></li><?php  } ?>
	<?php if(cv('bonus.sendall')) { ?><li <?php  if($_GPC['method']=='sendall') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/sendall')?>">全球分红</a></li><?php  } ?>
	<?php if(cv('bonus.detail')) { ?><li <?php  if($_GPC['method']=='detail') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/detail/list')?>">分红明细</a></li><?php  } ?>
    <?php if(cv('bonus.notice')) { ?><li  <?php  if($_GPC['method']=='notice') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/notice')?>">通知设置</a></li><?php  } ?>
    <?php  } ?>
    <?php if(cv('bonus.cover')) { ?><li <?php  if($_GPC['method']=='cover') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/cover')?>">分红中心入口设置</a></li><?php  } ?>
    <?php if(cv('bonus.set')) { ?><li <?php  if($_GPC['method']=='set') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('bonus/set')?>">基础设置</a></li><?php  } ?>
</ul>
</div>