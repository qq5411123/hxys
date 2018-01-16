<?php defined('IN_IA') or exit('Access Denied');?><style>
    .fui-modal,
.verify-pop {
    position: fixed;
}
.verify-pop {
    bottom: 0;
    position: absolute;
    top: 0;
    width: 100%;
    z-index: 1001;
}

.verify-pop .qrcode {
    background: #fff none repeat scroll 0 0;
    height: 250px;
    left: 50%;
    margin-left: -125px;
    position: absolute;
    top: 100px;
    width: 250px;
    z-index: 1001;
}

.verify-pop.pop .qrcode {
    width: 70%;
    height: 15rem;
    margin-left: -35%;
    overflow: hidden;
    display: block;
}

.verify-pop.pop .qrcode .inner {
    padding: 0.5rem;
    height: 12rem;
}

.verify-pop.pop .qrcode .inner .title {
    text-align: center;
    font-size: 1rem;
    height: 1.5rem;
}

.verify-pop.pop .qrcode .inner .text {
    width: 100%;
    word-wrap: break-word;
    font-size: 0.7rem;
    color: #ef4f4f;
    line-height: 1rem;
    height: 10rem;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}
.order-weixinpay-hidden {
    position: absolute;width:100%;height:100%;
    top:2.2rem;left:0;
    background:rgba(255,255,255,1);
}


.order-pay-page .icon {
    font-size: 1.3rem;
    text-align: center;
    line-height: 1.8rem;
    width: 2rem;
    height: 2rem;
    border-radius: .2rem;
}
.verify-pop .qrcode .loading {
    position: absolute;
    top: 100px;
    width: 250px;
    text-align: center
}

.verify-pop .qrcode .qrimg {
    position: absolute;
    width: 250px;
    height: 250px;
    display: none
}

.verify-pop .tip {
    position: absolute;
    top: 380px;
    z-index: 1001;
    width: 100%;
    color: #f90;
    font-size: 1rem;
    text-align: center;
    word-break: break-all
}
.order-weixinpay-hidden .tip {
    color:#333;
}
.btn.btn-default {
    background: #f7f7f7 none repeat scroll 0 0;
    border: 1px solid #dfdfdf;
    color: #333;
}
.btn.btn-sm {
    font-size: 0.7rem;
    height: 1.4rem;
    line-height: 1.3rem;
    margin: 0;
}
.btn {
    -moz-appearance: none;
    -moz-user-select: none;
    background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: 1px solid #f90;
    border-radius: 0.25rem;
    box-sizing: border-box;
    cursor: pointer;
    display: inline-block;
    font-family: inherit;
    font-size: 0.8rem;
    height: 2rem;
    line-height: 1.9rem;
    margin: 0.5em;
    padding: 0 0.5rem;
    position: relative;
    text-align: center;
    text-decoration: none;
    text-overflow: ellipsis;
    transition-duration: 300ms;
    transition-property: background-color;
    white-space: nowrap;
}
.text-danger {
    color: #ef4f4f;
}
</style>
<div class="order-verify-hidden order-weixinpay-hidden" style="display: none;">
    <div class="verify-pop">

        <div class="qrcode" style="top:1rem;">
            <div class="loading"><i class="icon icon-qrcode1"></i> 正在生成二维码</div>
            <img class="qrimg" src="" />
        </div>
        <div class="tip" style="top:270px;">
            <p>支付金额: <span class='text-danger'>￥ <span  id="qrmoney">-</span></span></p>
        </div>
        <div class="tip" style="top:290px;">
            <p>&nbsp;</p>
            <p>长按或扫描二维码, 进行订单支付</p>
            <p>支付完成之后, 会自动跳转到支付成功页面</p>
            <p>&nbsp;</p>
            <p>
            <div class="btn btn-default btn-sm" id="btnWeixinJieCancel">取消支付 </div>
            </p>
        </div>
    </div>
</div>