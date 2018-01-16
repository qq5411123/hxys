<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<title><?php  if($shopset['credit']) { ?><?php  echo $shopset['credit'];?><?php  } else { ?>余额<?php  } ?>提现</title>
<style type="text/css">
body {margin:0px; background:#efefef; -moz-appearance:none;}
input {-webkit-appearance:none; outline:none;}
.balance_img {height:80px; width:80px; margin:70px auto 0px; background:#ffb400; border-radius:40px; color:#fff; font-size:70px; text-align:center; line-height:90px;}
.balance_text {height:20px; width:100%; margin-top:16px; text-align:center; line-height:20px; font-size:16px; color:#666;}
.balance_num {height:24px; width:100%; margin-top:10px; text-align:center; line-height:24px; font-size:22px; color:#444;}
.balance_list {height:auto; width:100%; text-align:center; color:#92b5d6; font-size:16px; margin-top:80px;}
.balance_sub1 {height:44px; margin:14px 5px; background:#31cd00; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
.balance_sub2 {height:44px; margin:14px 5px; background:#f49c06; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
.balance_sub3 {height:44px; margin:14px 5px;background:#e2cb04; border-radius:4px; text-align:center; font-size:16px; line-height:44px; color:#fff;}
.page_poundage {position: relative;
height: 45px;
background: #fff;
border-bottom: 1px solid #e8e8e8;
font-size: 16px;
line-height: 45px;padding-left:10px;}
.select { border:1px solid #ccc;height:25px;width: 100px;}
.cash-inputs {
    width: 60%;
    border: 0;
    line-height: 30px;
    font-size: 24px;
    color: #333;
    box-sizing: border-box;
    padding-left: 10px;
}
</style>
<div id="container"></div>
<script id="tpl_main" type="text/html">
<div class="page_topbar">
<a href="javascript:;" class="back" onclick="history.back()"><i class='iconfont icon-chevron-left'></i></a>
<div class="title">余额提现</div>
</div>
<div class="cash-box">
    <div class="balance_price">您的当前<?php  if($shopset['credit']) { ?><?php  echo $shopset['credit'];?><?php  } else { ?>余额<?php  } ?>
        <span id="credit">￥<%credit%></span>
    </div>
    <div class="balance_price">请选择提现方式
        <select class="select" id="select_style">
            <option value="0">请选择</option>
            <option value="1">支付宝</option>
            <option value="2">微信</option>
            <option value="3">银联</option>
        </select> 
    </div>
    <div class="balance_price" id="alipay1" style="display:none;">支付宝账号<input type="text" value='' id="alipay_account" class="cash-inputs"/></div>  
    <div class="balance_price" id="alipay2" style="display:none;">支付宝名称<input type="text" value='' id="alipay_name" class="cash-inputs"/></div>  
    <div class="balance_price" id="wechat1" style="display:none;">微信号<input type="text" id="wechat_account" value='' class="cash-inputs"/></div>  
    <div class="balance_price" id="wechat2" style="display:none;">微信名称<input type="text" id="wechat_name" value='' class="cash-inputs"/></div>  
    <div class="balance_price" id="union1" style="display:none;">银行卡号<input type="text" id="bank_number" value='' class="cash-inputs"/></div>  
    <div class="balance_price" id="union2" style="display:none;">所属银行
        <select class="select" id="bank_id">
            <?php  if(is_array($bank_list)) { foreach($bank_list as $lists) { ?>
            <option value="<?php  echo $lists['id'];?>"><?php  echo $lists['bank_name'];?></option>
            <?php  } } ?>
        </select> 
    </div>  
    <div class="balance_price" id="union3" style="display:none;">银行预留姓名<input type="text" id="bank_name" value='' class="cash-inputs"/></div>
    <div class="cash-num"><span>￥</span><input type="text" id="money" value='' class="cash-inputs"/></div>    
</div>
<?php  if($set['trade']['poundage'] > 0) { ?>
<div class="page_poundage">
    提现手续费：<?php  echo $set['trade']['poundage'];?>%
</div>
<?php  } ?>
<div class="button balance_sub1">确认提现</div>
<div class="balance_sub3" onclick="location.href='<?php  echo $this->createMobileUrl('member/log',array('type'=>1))?>'">提现记录</div>
</script>
<script language="javascript">
    require(['tpl', 'core'], function (tpl, core) {
 
        core.json('member/withdraw', {}, function (json) {
            
            var result = json.result;
            if (json.status != 1) {
                core.tip.show(json.result);
                return;
            }
            
            $('#container').html(tpl('tpl_main', result));
            
            if(result.noinfo){
                core.message('请补充您的资料后才能申请提现!',result.infourl,'warning');
                return;
            }
         
            var withdrawmoney = <?php echo empty($set['trade']['withdrawmoney'])?0:floatval($set['trade']['withdrawmoney'])?>;
           
            if(result.credit<=0){
                core.message('无<?php  if($shopset['credit']) { ?><?php  echo $shopset['credit'];?><?php  } else { ?>余额<?php  } ?>，无法申请提现!',"<?php  echo $this->createMobileUrl('member')?>",'warning');
                return;
            }
     
              if(withdrawmoney>0 && result.credit<withdrawmoney){
                core.message('<?php  if($shopset['credit']) { ?><?php  echo $shopset['credit'];?><?php  } else { ?>余额<?php  } ?>不足 '+ withdrawmoney +' 元，无法申请提现!',"<?php  echo $this->createMobileUrl('member')?>",'warning');
                return;
            }
             
            
            $('.balance_sub1').click(function () {
                    if ($(this).attr('submitting') == '1') {
                        return;
                    }
                    var money = $('#money').val();
                    if (!$('#money').isNumber()) {
                       
                        core.tip.show('请输入数字金额!');
                        return;
                    }
                     if( parseFloat(money) < withdrawmoney){
                        core.tip.show('满 '+ withdrawmoney +' 元才能申请提现!');
                        return;
                    }
                   
                    if($('#select_style').val() == 0){
                        core.tip.show('请选择支付方式!');
                        return;    
                    }else if($('#select_style').val() == 1 ){
                        if( $('#alipay_account').val() == ''){
                            core.tip.show('支付宝账号不能为空!');
                            return;   
                        }
                        if( $('#alipay_name').val() == ''){
                            core.tip.show('支付宝名称不能为空!');
                            return;   
                        }
                    }else if($('#select_style').val() == 2 ){
                        if( $('#wechat_account').val() == ''){
                            core.tip.show('微信账号不能为空!');
                            return;   
                        }
                        if( $('#wechat_name').val() == ''){
                            core.tip.show('微信名称不能为空!');
                            return;   
                        }
                    }else if($('#select_style').val() == 3 ){
                        if( $('#bank_number').val() == ''){
                            core.tip.show('银行卡号不能为空!');
                            return;   
                        }
                        if( $('#bank_id').val() == ''){
                            core.tip.show('请选择银行!');
                            return;   
                        }
                        if( $('#bank_name').val() == ''){
                            core.tip.show('银行预留姓名不能为空!');
                            return;   
                        }
                    }
                    $(this).attr('submitting', 1);
                    core.json('member/withdraw', {op: 'submit', money: money , 'type':$('#select_style').val() , alipay_account:$('#alipay_account').val(),alipay_name:$('#alipay_name').val(),wechat_account:$('#wechat_account').val(),wechat_name:$('#wechat_name').val(),bank_number:$('#bank_number').val(),bank_id:$('#bank_id').val(),bank_name:$('#bank_name').val()}, function (rjson) {
                        if (rjson.status == 0) {
                            $(this).removeAttr('submitting');
                            core.tip.show(rjson.result);
                            return;
                        } else if (rjson.status == 1) {
                            core.message('免审核提现已成功，请尽快核实金额!',"<?php  echo $this->createMobileUrl('member')?>",'success');
                        } else if(rjson.status == 2) {
                            core.message('提现申请提交成功，请等待审核!',"<?php  echo $this->createMobileUrl('member')?>",'success');   
                        }
                        
                        
                    }, true, true);
 
            });
            $('#select_style').change(function(){
                var id = $(this).val();
                if( id == 1){
                    $('#alipay1').show();
                    $('#alipay2').show();
                    $('#wechat1').hide();
                    $('#wechat2').hide(); 
                    $('#union1').hide();
                    $('#union2').hide();
                    $('#union3').hide();   
                } else if( id == 2){
                    $('#wechat1').show();
                    $('#wechat2').show();  
                    $('#union1').hide();
                    $('#union2').hide();
                    $('#union3').hide(); 
                    $('#alipay1').hide();
                    $('#alipay2').hide();  
                } else if( id == 3){
                    $('#union1').show();
                    $('#union2').show();
                    $('#union3').show(); 
                    $('#alipay1').hide();
                    $('#alipay2').hide();   
                    $('#wechat1').hide();
                    $('#wechat2').hide(); 
                }
            });
        }, true);
    });

</script>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
