<?php
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use app\extensions\twocheckout\TwocheckoutAsset;

TwocheckoutAsset::register($this);

$mode = (options()->get('app.gateway.twocheckout.mode', 'sandbox') == 'sandbox') ? 'sandbox' : 'production';
?>
<div id="twocheckout-form-wrapper" data-mode="<?=$mode;?>" data-sid="<?=html_encode(options()->get('app.gateway.twocheckout.accountId', ''));?>" data-key="<?=html_encode(options()->get('app.gateway.twocheckout.publishableKey'));?>" style="display: none">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="separator-text">
                <span><?=t('app','Card information');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-6 col-md-push-3 col-sm-12 col-xs-12">
            <?= Html::hiddenInput('token', null,['class' => '', 'id' => 'token']); ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Html::label(t('app','Card Number'));?>
                    <div class="form-group">
                        <?= Html::textInput(null, null,['placeholder' => t('app','•••• •••• •••• ••••'), 'class' => '', 'id' => 'TwocheckoutCardNumber']); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">
                    <?= Html::label(t('app','Expiration'));?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12 hidden-xs">
                    <?= Html::label(t('app','CVC Code'));?>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <?= Html::dropDownList('Twocheckout[expireMonth]', null, DateTimeHelper::getStaticMonths(), ['prompt' => t('app','Month'), 'class' => '', 'id' => 'TwocheckoutExpireMonth']); ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <?= Html::dropDownList('Twocheckout[expireYear]', null, DateTimeHelper::getYears(date("Y")), ['prompt' => t('app','Year'), 'class' => '', 'id' => 'TwocheckoutExpireYear']); ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden-lg hidden-md hidden-sm">
                    <?= Html::label(t('app','CVC Code'));?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <?= Html::textInput(null, null,['placeholder' => t('app','•••'), 'class' => '', 'id' => 'TwocheckoutCvc']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>