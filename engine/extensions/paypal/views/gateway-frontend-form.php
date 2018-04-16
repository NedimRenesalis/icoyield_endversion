<?php
use app\extensions\paypal\PaypalAsset;

PaypalAsset::register($this);
?>
<div id="paypal-form-wrapper" style="display: none">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="separator-text">
                <span><?=t('app','PayPal Information');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-6 col-md-push-3 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="alert alert-warning" role="alert"><?=t('app', 'You will be redirected to PayPal to complete your purchase securely!');?></div>
                </div>
            </div>
        </div>
    </div>
</div>