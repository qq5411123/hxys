<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<title>用户中心_<?php  echo $this->yzShopSet['pctitle']?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <link href="../addons/sz_yi/static/css/font-awesome.min.css" rel="stylesheet">
    
    
    
    
<link href="../addons/sz_yi/static/font/iconfont.css" rel="stylesheet">

    <!-- <link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/mobile/default/static/css/style.css"> -->

    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/bootstrap.min.css">
    <link rel="stylesheet" href="../addons/sz_yi/template/pc/default/static/css/member-center.css">
    <link rel="stylesheet" type="text/css" href="../addons/sz_yi/template/pc/default/static/css/style.css">
    </head>
    <body>




    require(['core','tpl'],function(core,tpl){
        core.init({
            siteUrl: "<?php  echo $_W['siteroot'];?>",
            baseUrl: "<?php  echo $this->createMobileUrl('ROUTES')?>"
        });
    })
</script>
</head>
<body>
	<!--
	
    
    var _hmt = _hmt || [];</script>
    -->

    <div class="top-head fl wfs fz12">
        <div class="wrapper">
            <div class="left fl">尊敬的<?php  echo $this->yzShopSet['name']?>用户，欢迎登陆管理中心！</div>
                <div class="right fr" >
                您的账号：<?php  echo $_COOKIE['member_mobile']?>
                    <a href="<?php  echo $this->createMobileUrl('member/forget')?>">[修改密码]</a>
                    <a href="<?php  echo $this->createMobileUrl('member/logout')?>">[退出登录]</a>
                </div>
        </div>
    </div>


<div class="head fl wfs">
    <div class="wrapper">
        <a class="logo" href="<?php  echo $this->createMobileUrl('shop/index')?>">
            <?php  if($this->yzImages['pclogo']) { ?>
                <img src="<?php  echo $this->yzImages['pclogo']?>" style="width:270px;height:60px;" title="<?php  echo $this->yzShopSet['pctitle']?>">
            <?php  } else { ?>
                <img src="../addons/sz_yi/template/pc/default/static/images/logo.png" title="" alt="我是默认logo">
            <?php  } ?>
        </a>
        <div class="nav">
            <a class="index" href="<?php  echo $this->createMobileUrl('shop/index')?>">首页</a>
            <a class="member member-now" href="<?php  echo $this->createMobileUrl('order')?>">会员中心</a>
            <a class="order1 " href="<?php  echo $this->createMobileUrl('shop/cart')?>">购物车</a>

            <a class="account " href="<?php  echo $this->createMobileUrl('shop/favorite')?>">我的收藏</a>
            <a class="service " href="<?php  echo $this->createMobileUrl('shop/address')?>">收货地址</a>
        </div>
    </div>
</div>

