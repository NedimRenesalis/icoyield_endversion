<?php
use app\extensions\twocheckout\TwocheckoutAsset;

TwocheckoutAsset::register($this);
?>
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="item">
        <input type="radio" name="paymentGateway" value="twocheckout" id="payment_method_twocheckout"  />
        <label for="payment_method_twocheckout">
            <span class="graphic"><?=t('app', '2checkout');?><i class="fa fa-credit-card " aria-hidden="true"></i></span>
            <span class="text"><?=html_encode($description);?></span>
        </label>
    </div>
</div>