<?php
use yii\helpers\Html;
use app\extensions\manual\models\Manual;
?>
<div id="manual" class="tab-pane fade in">
    <div class="box-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <?= Html::activeLabel($model, 'status');?>
                    <?= Html::dropDownList($model->formName().'[status]', html_encode($model->status), Manual::getStatusList() , ['class' => 'form-control']); ?>
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