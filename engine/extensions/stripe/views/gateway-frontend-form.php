<?php
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use app\extensions\stripe\StripeAsset;

StripeAsset::register($this);
?>
<div id="stripe-form-wrapper" style="display: none">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="separator-text">
                <span><?=t('app','Card information');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-6 col-md-push-3 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Html::label(t('app','Card Number'));?>
                    <div class="form-group">
                        <?= Html::textInput('Stripe[cardNumber]', null,['placeholder' => t('app','•••• •••• •••• ••••'), 'class' => '']); ?>
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
                        <?= Html::dropDownList('Stripe[expireMonth]', null, DateTimeHelper::getStaticMonths(), ['prompt' => t('app','Month'), 'class' => '']); ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <?= Html::dropDownList('Stripe[expireYear]', null, DateTimeHelper::getYears(date("Y")), ['prompt' => t('app','Year'), 'class' => '']); ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden-lg hidden-md hidden-sm">
                    <?= Html::label(t('app','CVC Code'));?>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-12">
                    <div class="form-group">
                        <?= Html::textInput('Stripe[cvc]', null,['placeholder' => t('app','•••'), 'class' => '']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>