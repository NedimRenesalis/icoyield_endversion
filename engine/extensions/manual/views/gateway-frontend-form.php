<?php
use yii\helpers\Html;
use app\helpers\DateTimeHelper;
use app\extensions\manual\ManualAsset;

ManualAsset::register($this);
?>
<div id="manual-form-wrapper" style="display: none">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="separator-text">
                <span><?=t('app','Manual Payment information');?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-push-3 col-md-6 col-md-push-3 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?= Html::label(t('app','Payment Reference'));?>
                    <div class="form-group">
                        <?= Html::textInput('Manual[ref]', null,['placeholder' => t('app','RX01234567'), 'class' => '']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>