<?php
use yii\helpers\Html;
use app\extensions\paypal\models\Paypal;
?>
<div id="paypal" class="tab-pane fade in">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'email');?>
                    <?= Html::textInput($model->formName().'[email]', html_encode($model->email), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'email', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'username');?>
                    <?= Html::textInput($model->formName().'[username]', html_encode($model->username), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'username', ['class' => 'help-block']);?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'password');?>
                    <?= Html::textInput($model->formName().'[password]', html_encode($model->password), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'password', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'signature');?>
                    <?= Html::textInput($model->formName().'[signature]', html_encode($model->signature), ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'signature', ['class' => 'help-block']);?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'mode');?>
                    <?= Html::dropDownList($model->formName().'[mode]', html_encode($model->mode), Paypal::getModeList() , ['class' => 'form-control']); ?>
                    <?= Html::error($model, 'mode', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'status');?>
                    <?= Html::dropDownList($model->formName().'[status]', html_encode($model->status), Paypal::getStatusList() , ['class' => 'form-control']); ?>
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