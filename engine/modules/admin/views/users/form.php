<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\helpers\DateTimeHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Users',
        ]) . $model->first_name.' '.$model->last_name;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Users'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->first_name.' '.$model->last_name, 'url' => ['view', 'id' => $model->user_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
}else{
    $this->title = t('app', 'Create User');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Users'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}

$avatarImage = ($model->avatar) ? $model->avatar : Yii::getAlias('@web/assets/admin/img/default.jpg');
?>
<div class="nav-tabs-custom users-<?= $action;?>">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#info"><?=t('app','Info');?></a></li>
        <li><a data-toggle="tab" href="#avatar"><?=t('app','Avatar');?></a></li>
        <li class="pull-right">
            <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp&nbsp'.t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </li>
    </ul>

<?php $form = ActiveForm::begin(); ?>
    <div class="tab-content">
        <div id="info" class="tab-pane fade in active">
            <div class="box-body">
                <div class="users-info-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'confirm_email')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'fake_password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'confirm_password')->passwordInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'timezone')->dropDownList(DateTimeHelper::getTimeZones()) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'status')->dropDownList(\app\yii\db\ActiveRecord::getStatusesList()) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map(\app\models\Group::find()->all(), 'group_id', 'name'),['class'=>'form-control','prompt' => 'Please select']);?>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                    <div class="clearfix"><!-- --></div>
                </div>
            </div>
        </div>
        <div id="avatar" class="tab-pane fade in">
            <div class="box-body">
                <div class="users-avatar-form">
                    <div class="row" style="display: block;">
                        <div class="col-lg-6">
                            <div class="image-upload-wrapper">
                                <div class="image-upload-error" style="display: none"></div>
                                <?= $form->field($model, 'avatarUpload')->widget(\kartik\file\FileInput::classname(), [
                                    'options' => [
                                        'class'=>'image-upload',
                                        'data-image'=>$avatarImage,
                                    ],
                                    'pluginOptions' => [
                                        'language' => options()->get('app.settings.common.siteLanguage', 'en'),
                                        'overwriteInitial'=> true,
                                        'maxFileSize'=> 1500,
                                        'showClose'=> false,
                                        'showRemove' => false,
                                        'showCaption'=> false,
                                        'showBrowse'=> false,
                                        'browseOnZoneClick'=> true,
                                        'removeLabel'=> '',
                                        'removeIcon'=> '<i class="glyphicon glyphicon-remove"></i>',
                                        'removeTitle'=> 'Cancel or reset changes',
                                        'elErrorContainer'=> '.image-upload-error',
                                        'msgErrorClass'=> 'alert alert-block alert-danger',
                                        'defaultPreviewContent'=> '<img src="'.$avatarImage.'" alt="Your Avatar" style="width:160px"><h6 class="text-muted">' . t('app','Click to change picture') . '</h6>',
                                        'layoutTemplates'=> ['main2'=> '{preview} {remove} {browse}'],
                                        'allowedFileTypes' => ["image"],
                                    ]
                                ])->label(false);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
    </div>
        <?php ActiveForm::end(); ?>
</div>