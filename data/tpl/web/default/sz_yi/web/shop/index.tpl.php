<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_header', TEMPLATE_INCLUDEPATH)) : (include template('web/_header', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript" src="../addons/sz_yi/static/js/ZeroClipboard.js"></script>
<link rel="stylesheet" type="text/css" href="../addons/sz_yi/static/css/shop.css">
<div class="w-shop">
	<div class="w-shoptit">
		<div class="shop-logo">
			<img src="<?php  echo tomedia('headimg_'.$_W['uniacid']. '.jpg')?>"/>
		</div>
		<div class="shop-intro">
			<h2 class="ng-binding"><?php  echo $_W['account']['name'];?></h2>
			<p class="ng-binding ng-scope">当前版本: <?php  echo SZ_YI_VERSION;?></p>
		</div>
		<div class="shop-shortcut">
			<a href="<?php  echo $this->createWebUrl('shop/goods', array('status'=>1))?>" class="btn btn-success btn-sm mr5 ng-scope"><i class="fa fa-plus"></i>发布商品</a>
<?php  if(is_array($plugins)) { foreach($plugins as $plugin) { ?>
	<?php  if($plugin['identity'] == 'designer') { ?>
		<?php if(cp($plugin['identity'])) { ?>
        <?php  if(p($plugin['identity'])) { ?>
            <a href="<?php  echo $this->createPluginWebUrl('designer')?>" class="btn btn-default btn-sm mr5 ng-scope"><i class="fa fa-gavel"></i>店铺装修</a>
        <?php  } ?>        
		<?php  } ?>
	<?php  } ?>
<?php  } } ?>
			<div class="btn btn-default btn-sm shop-code-btn">
			<i class="fa fa-mobile"></i> 访问店铺
				<div class="code-dov">
					<h1>手机扫码访问：</h1>
					<img width="120" src="<?php  echo tomedia($qrcode)?>">
					<div class="link-pos">
						<a class="show-shop-code ng-isolate-scope zeroclipboard-is-hover" id="copy_text">复制链接</a>
						<a class="ml10" href="<?php  echo $url;?>" target="_blank">电脑上查看</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="w-pricelist">
		<ul>
            <li>
                <h5>
                    <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">
                        <span ><i class="unit">￥</i><?php  echo $day_price;?></span>
                    </a>
                </h5>
                <h6>今日交易额</h6>
            </li>
            <li>
                <h5>
                    <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">
                        <span ><?php  echo $day_cnt;?></span>
                    </a>
                </h5>
                <h6>今日订单数</h6>
            </li>
            <li>
                <h5>
                    <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">
                        <span ><i class="unit">￥</i><?php  echo $day_nopay_price;?></span>
                    </a>
                </h5>
                <h6>待付款订单</h6>
            </li>
            <li>
                <h5>
                    <a href="<?php  echo $this->createWebUrl('order', array('op' => 'display'))?>">
                        <span ><i class="unit">￥</i><?php  echo $day_no_dispatch;?></span>
                    </a>
                </h5>
                <h6>待发货订单</h6>
            </li>
         </ul>
	</div>
	<div class="widget-mod">
        <div id="container" style="min-width: 300px; height: 300px; margin: 0 auto"></div>  
	</div>
	<div class="widget-mod">
		<div class="widget-head">
			<i class="iconfont icon-yuanquan"></i>销量排行
		</div>
		<div class="widget-body">
			<table class="table table-hover" style="margin-left: 0">
				<thead>
					<tr><th>商品名称</th><th>销量</th><th>销售金额</th></tr>
				</thead>
				<tbody>
                    <?php  if(is_array($goods_list)) { foreach($goods_list as $key => $row) { ?>
					<tr class="ng-scope"><td class="ng-binding"><?php  echo $row['title'];?></td><td class="ng-binding"><?php  echo $row['count'];?></td><td class="ng-binding"><?php  echo $row['money'];?></td></tr>
               <?php  } } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script language="JavaScript">
		var clip = null;
		
		function init() {
			clip = new ZeroClipboard.Client();
            console.log(clip);
			clip.setHandCursor( true );
            clip.setText('dddddddd')
			
			clip.addEventListener('load', my_load);
			clip.addEventListener('mouseOver', my_mouse_over);
			clip.addEventListener('complete', my_complete);
			
			clip.glue( 'copy_text' );
		}
		
		function my_load(client) {
			alert("Flash movie loaded and ready.");
		}
		
		function my_mouse_over(client) {
			// we can cheat a little here -- update the text on mouse over
			clip.setText( 'ssss' );
		}
		
		function my_complete(client, text) {
			alert("Copied text to clipboard: " + text );
		}
		
		function debugstr(msg) {
			var p = document.createElement('p');
			p.innerHTML = msg;
			$('d_debug').appendChild(p);
		}
init();
	</script>
<script language="javascript" src="<?php echo SZ_YI_STATIC;?>js/dist/highcharts/highcharts.js"></script>
<script type="text/javascript">
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: '订单趋势图',
            x: -20 //center
        },
        exporting:{
            enabled:false
        },
        credits: {
            enabled: false
        },
        subtitle: {
            text: '',
            x: -20
        },
        xAxis: {
            //categories: ['2016-08-01', '2016-08-02', '2016-08-03', '2016-08-04', '2016-08-05', '2016-08-01','2016-08-01', '2016-08-01']
            categories: [
                <?php  if(is_array($alllist)) { foreach($alllist as $key => $row) { ?>
                   <?php  if($key>0) { ?>,<?php  } ?>"<?php  echo $row['createdate'];?>"
               <?php  } } ?>
            ]
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'horizontal',
            align: 'center',
            verticalAlign: 'top',
            borderWidth: 0,
            y:20
        },
        series: [
            {
                name: '总订单',
                data: [
                  <?php  if(is_array($alllist)) { foreach($alllist as $key => $row) { ?>
                   <?php  if($key>0) { ?>,<?php  } ?><?php  echo $row['total'];?>
                  <?php  } } ?>
                ]
            }, {
                name: '已完成',
                data: [
                  <?php  if(is_array($finishlist)) { foreach($finishlist as $key => $row) { ?>
                   <?php  if($key>0) { ?>,<?php  } ?><?php  echo $row['total'];?>
                  <?php  } } ?>
                ]
            }, {
                name: '已发货',
                data: [
                  <?php  if(is_array($sendlist)) { foreach($sendlist as $key => $row) { ?>
                   <?php  if($key>0) { ?>,<?php  } ?><?php  echo $row['total'];?>
                  <?php  } } ?>
                ]
            }, 
            {
                name: '已付款',
                data: [
                  <?php  if(is_array($paylist)) { foreach($paylist as $key => $row) { ?>
                   <?php  if($key>0) { ?>,<?php  } ?><?php  echo $row['total'];?>
                  <?php  } } ?>
                ]
            }
        ]
    });
});</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('web/_footer', TEMPLATE_INCLUDEPATH)) : (include template('web/_footer', TEMPLATE_INCLUDEPATH));?>
