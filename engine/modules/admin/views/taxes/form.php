<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Zone */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Taxes',
        ]) . $model->name;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Taxes'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->tax_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
}else{
    $this->title = t('app', 'Create Tax');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Taxes'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="box box-primary taxes-<?= $action;?>">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin(); ?>
        <div class="taxes-form">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'name')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Name of tax'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'percent')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Tax percentage that will apply to the total amount of the order'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>

            <div class="row" id="taxes-select-zones-wrapper" data-url="<?=url(['taxes/get-country-zones']);?>" data-zone = <?=$model->zone_id;?>>
                <div class="col-lg-6">
                    <?= $form->field($model, 'country_id')->dropDownList(ArrayHelper::merge(['---'],ArrayHelper::map(\app\models\Country::find()->all(), 'country_id', 'name')),[
                            'class'=>'form-control',
                            'data-content'      => t('app', 'Country for which this tax will apply'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'

                    ]);?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'zone_id')->dropDownList(ArrayHelper::map([],['---']),['class'=>'form-control']);?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'is_global')->dropDownList(\app\models\Tax::getYesNoList(), [
                        'data-content'      => t('app', 'If set to yes, then it will apply to everything'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'status')->dropDownList(\app\models\Tax::getStatusesList()) ?>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>