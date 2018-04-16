<?php
use app\extensions\manual\ManualAsset;

ManualAsset::register($this);
?>
<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
    <div class="item">
        <input type="radio" name="paymentGateway" value="manual" id="payment_method_manual"  />
        <label for="payment_method_manual">
            <span class="graphic"><?=t('app', 'Manual Payment');?><i class="fa fa-money" aria-hidden="true"></i></span>
            <span class="text"><?=html_encode($description);?></span>
        </label>
    </div>
</div>