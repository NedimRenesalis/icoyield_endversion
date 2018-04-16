<?php
use app\extensions\stripe\StripeAsset;

StripeAsset::register($this);
?>
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="item">
        <input type="radio" name="paymentGateway" value="stripe" id="payment_method_stripe"  />
        <label for="payment_method_stripe">
            <span class="graphic"><?=t('app', 'Stripe');?><i class="fa fa-cc-stripe" aria-hidden="true"></i></span>
            <span class="text"><?=html_encode($description);?></span>
        </label>
    </div>
</div>