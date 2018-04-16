<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\mail\template\TemplateType;

/* @var $this yii\web\View */
/* @var $model app\models\MailAccount */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Mail Account',
        ]) . $model->username;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Mail Accounts'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->account_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
} else {
    $this->title = t('app', 'Create Mail Account');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Mail Accounts'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>

<div class="box box-primary mail-accounts-<?= $action; ?>">

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
                <div class="col-lg-12">
                    <?= $form->field($model, 'account_name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'hostname')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'port')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'encryption')->dropDownList($model->getEncryptionsList()); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'timeout')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'from')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'reply_to')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'used_for')->dropDownList(TemplateType::getTypesList(), [
                        'multiple' => 'multiple',
                        'options' => $model::getListOfUsedTemplateTypes($model->account_id),
                        'size'      => count(TemplateType::getTypesList()) + 3,
                        'data-content'      => t('app', 'Handles on which email template should this account be used, you can select multiple options.'),
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