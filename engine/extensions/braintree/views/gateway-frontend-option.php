<?php
use app\extensions\braintree\BraintreeAsset;

BraintreeAsset::register($this);
?>

<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="item">
        <input type="radio" name="paymentGateway" value="braintree" id="payment_method_braintree"  />
        <label for="payment_method_braintree">
            <span class="graphic"><?=t('app', 'BrainTree');?><i class="fa fa-credit-card " aria-hidden="true"></i></span>
            <span class="text"><?=html_encode($description);?></span>
        </label>
    </div>
</div>