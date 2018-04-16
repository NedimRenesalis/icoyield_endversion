<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\MailTemplate */

$this->title = t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Mail Template',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => t('app', 'Mail Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = t('app', 'Update: ' . $model->name);
?>

<div class="box box-primary mail-template-update">

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
        <div class="mail-template-form">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-3">
                            <?= $form->field($model, 'isPlainContent', [
                                'options' => [
                                    'class' => 'checkbox',
                                    'data-content'      => t('app', 'This will remove all HTML code from Email Template Content'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                                ],
                            ])->checkbox(); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                                'options' => ['rows' => 6]
                            ]) ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">Available variables of template</div>
                                <ul class="list-group">
                                    <?php foreach ($vars as $key => $var) { ?>
                                        <li class="list-group-item"><?= $var ?> <span class="pull-right">{{<?= $key ?>}}</span></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <div class="pull-right">
                <?= Html::submitButton(t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
