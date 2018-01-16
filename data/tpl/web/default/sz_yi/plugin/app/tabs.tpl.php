<?php defined('IN_IA') or exit('Access Denied');?><div class="ulleft-nav">
    <ul class="nav nav-tabs">
        <?php if(cv('app.index')) { ?><li <?php  if(empty($_GPC['method']) || $_GPC['method']=='index') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('app/index')?>">基本设置</a></li><?php  } ?>
        <?php if(cv('app.slider')) { ?><li <?php  if($_GPC['method']=='slider') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('app/slider')?>">广告图片设置</a></li><?php  } ?>
        <?php if(cv('app.push')) { ?><li <?php  if($_GPC['method']=='push') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('app/push')?>">推送消息</a></li><?php  } ?>
        <?php if(cv('app.type')) { ?><li <?php  if($_GPC['method']=='type') { ?>class="active"<?php  } ?>><a href="<?php  echo $this->createPluginWebUrl('app/type')?>">支付方式</a></li><?php  } ?>
    </ul>
</div>