<?php
use app\extensions\paypal\PaypalAsset;

PaypalAsset::register($this);
?>
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="item">
        <input type="radio" name="paymentGateway" value="paypal" id="payment_method_paypal"  />
        <label for="payment_method_paypal">
            <span class="graphic"><?=t('app', 'Paypal');?><i class="fa fa-cc-paypal" aria-hidden="true"></i></span>
            <span class="text"><?=html_encode($description);?></span>
        </label>
    </div>
</div>