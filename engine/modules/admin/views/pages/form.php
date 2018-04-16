<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Pages */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Pages',
        ]) . $model->title;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Pages'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->page_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
} else {
    $this->title = t('app', 'Create Page');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Pages'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="box box-primary pages-<?= $action; ?>">

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
        <div class="pages-form">
            <div class="row">
                <div class="col-lg-6">
                    <?php
                    $titleOptions = ['maxlength' => true];
                    $slugOptions = ['maxlength' => true];
                    if ($action == 'create') {
                        $titleOptions['id'] = 'sluggable-title';
                        $slugOptions['id'] = 'sluggable-slug';
                    }
                    ?>
                    <?= $form->field($model, 'title')->textInput($titleOptions) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'slug')->textInput(array_merge($slugOptions,[
                        'data-content'      => t('app', 'URL of the page'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ])) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'keywords')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Page Meta Keywords'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'description')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Page Meta Description'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
                        'options' => ['rows' => 6],
                        'preset'  => 'basic'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'section')->dropDownList(
                        $model::getSectionsList(),
                        [
                                'prompt' => t('app', 'Select section'),
                                'data-content'      => t('app', 'The section where this page should display in footer'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                        ]
                    ); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'sort_order')->dropDownList(
                        $model->section ? $model->getListOfAvailablePositions($model->section) : [],
                        [
                            'prompt'       => t('app', 'Select position'),
                            'data-url'     => url(['pages/get-available-positions']),
                            'data-page-id' => $model->page_id,
                            'data-content'      => t('app', 'The order in footer section where this page should be display'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]
                    ); ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>
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