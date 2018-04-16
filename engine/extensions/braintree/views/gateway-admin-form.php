<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Currency;
use app\extensions\braintree\models\Braintree;
?>
<div id="braintree" class="tab-pane fade in">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'merchantId');?>
                    <?= Html::textInput($model->formName().'[merchantId]', html_encode($model->merchantId), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'merchantId', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'privateKey');?>
                    <?= Html::textInput($model->formName().'[privateKey]', html_encode($model->privateKey), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'privateKey', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'publicKey');?>
                    <?= Html::textInput($model->formName().'[publicKey]', html_encode($model->publicKey), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'publicKey', ['class' => 'help-block']);?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'merchantCurrency');?>
                    <?= Html::dropDownList($model->formName().'[merchantCurrency]', html_encode($model->merchantCurrency), ArrayHelper::map(Currency::getActiveCurrencies(), 'code', 'name'),['class'=>'form-control','prompt' => 'Please select']);?>
                    <?= Html::error($model, 'merchantCurrency', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'mode');?>
                    <?= Html::dropDownList($model->formName().'[mode]', html_encode($model->mode), Braintree::getModeList() , ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'mode', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'status');?>
                    <?= Html::dropDownList($model->formName().'[status]', html_encode($model->status), Braintree::getStatusList() , ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'status', ['class' => 'help-block']);?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'description');?>
                    <?= Html::textarea($model->formName().'[description]', html_encode($model->description), [
                            'class' => 'form-control',
                            'data-content'      => t('app', 'This will be displayed when clients choose a payment gateway to pay ad posting'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]); ?>
                    <?= Html::error($model, 'description', ['class' => 'help-block']);?>
                </div>
            </div>
        </div>

            <div class="box-footer">
                <div class="pull-right">
                    <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
    </div>