<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


$this->title = t('app', 'License Settings');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="callout callout-warning">
    <h4><i class="icon fa fa-warning"></i> Warning!</h4>
    <p>Please be very careful when editing the license info, any wrong param will render your application unusable!</p>
</div>


<div class="box box-primary">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
        </div>
    </div>


    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="email-settings-form">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'firstName')->textInput([
                        'data-content'      => t('app', 'Please provide your first name for license activation'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]); ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'lastName')->textInput([
                        'data-content'      => t('app', 'Please provide your last name for license activation'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'email')->textInput([
                        'data-content'      => t('app', 'Please provide your email for license activation'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]); ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'purchaseCode')->textInput([
                        'data-content'      => t('app', 'Please provide your purchase code for license activation'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]); ?>
                </div>
            </div>

        </div>

        <div class="box-footer">
            <div class="pull-right">
                <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>