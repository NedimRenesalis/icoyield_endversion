<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Group */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Groups',
        ]) . $model->name;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Groups'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->group_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
}else{
    $this->title = t('app', 'Create Group');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Groups'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="box box-primary groups-<?= $action;?>">
    <div class="box-header with-border">
        <h3 class="box-title">General</h3>
    </div>
    <div class="box-body">

        <?php $form = ActiveForm::begin(); ?>
        <div class="groups-form">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'status')->dropDownList([ 'active' => t('app','Active'), 'inactive' => t('app','Inactive'), ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary groups-<?= $action;?>">

    <div class="box-header with-border">
        <h3 class="box-title">Access</h3>
    </div>


    <div class="box-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <?php
        foreach ($routesAccess as $key=>$value) { ?>
            <div class="panel panel-primary">
                <div class="panel-heading" role="tab" id="heading-<?=$key;?>">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$key;?>" aria-expanded="true" aria-controls="collapse-<?=$key;?>">
                            <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> <?=Html::encode($value['controller']['name']);?>
                        </a>
                    </h4>
                </div>
                <div id="collapse-<?=$key;?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-<?=$key;?>">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="page-header">
                                <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> <?=Html::encode($value['controller']['description']);?></h3>
                            </div>
                        </div>
                        <?php foreach ($value['routes'] as $route) {?>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <?= Html::label($route->name, null) ?>
                                    <?= Html::dropDownList(
                                        'GroupRouteAccess['.$route->route.']',
                                        $route->access,
                                        $route->getAccessOptions(),
                                        [
                                            'id'                => '',
                                            'class'             => 'form-control',
                                            'data-content'      => $route->description,
                                            'data-title'        => 'Info',
                                            'data-container'    => 'body',
                                            'data-toggle'       => 'popover',
                                            'data-trigger'      => 'hover',
                                            'data-placement'    => 'top',
                                        ]
                                    ); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>
    </div>




    <div class="box-footer">
        <div class="pull-right">
            <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <div class="pull-left">
            <?= Html::a(t('app', 'Cancel'), ['index'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>

        <?php ActiveForm::end(); ?>
</div>