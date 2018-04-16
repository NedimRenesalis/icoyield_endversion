<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => 'Category',
        ]) . $model->name;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Categories'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->category_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
}else{
    $this->title = t('app', 'Create Category');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Categories'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#general"><?=t('app','General');?></a></li>
        <li><a data-toggle="tab" href="#fields"><?=t('app','Fields');?></a></li>
        <li><a data-toggle="tab" href="#icon"><?=t('app','Icon');?></a></li>
        <li class="pull-right">
            <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp&nbsp'.t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </li>
    </ul>

    <?php $form = ActiveForm::begin([
        'options' => [
            'data' => [
                'message' => t('app', 'Your Category Has No Fields Set, Proceed Anyway?'),
            ]
        ]
    ]); ?>
    <div class="tab-content">
        <div id="general" class="tab-pane fade in active">
            <div class="box-body">
                <div class="users-form">
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
                            <?= $form->field($model, 'name')->textInput($titleOptions) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'slug')->textInput($slugOptions) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'sort_order')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'status')->dropDownList([ 'active' => 'active', 'inactive' => 'inactive', ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'parent_id')->dropDownList(
                                \app\models\Category::getAllCategories($model->category_id),
                                ['class'=>'form-control','prompt' => 'No Parent']
                            );
                            ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <?= $form->field($model, 'description')->textArea() ?>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="fields" class="tab-pane fade">
            <div class="box box-primary categories-fields-<?= $action;?>">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title">
                            <?=t('app','Existing Fields');?>
                        </h3>
                    </div>
                    <?php
                    $parentId = ($model->isNewRecord) ? null : $model->parent_id;
                    $showFields = ($parentId) ? 'style="display:block"' : 'style="display:none"';
                    ?>
                    <div class="pull-right" id="categories-switch-inherit-parent-wrapper" <?=$showFields;?>>
                        <h5 class="box-title">
                            <span class="switch-label">
                                <i class="fa fa-info-circle" aria-hidden="true" data-content="<?=t('app','If Enabled, fields from parent will show.');?>" data-title="Info" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top"></i>
                                <?=t('app','Inherit From Direct Parent? ');?>
                            </span>
                            <input id="categories-switch-inherit-parent-fields" type="checkbox" data-size="mini" data-on-color="primary" data-switch-style="bootstrap" data-parent-id="<?=$parentId;?>" data-parent-path="<?=url(['categories/update'],true);?>" data-no-parent-field-msg="<?=t('app','The direct parent of this category has no fields set, you can still add fields from Actions section bellow');?>">
                        <h5/>
                    </div>
                </div>
                <div class="box-body list-fields">
                    <?php
                    app()->trigger('admin.categories.form.fields.list', new \app\yii\base\Event(['params' => [
                        'form'=>$form,
                        'model'=>$model,
                    ]]));

                    $hasFields = (app()->hasEventHandlers('admin.categories.form.fields.list')) ? 'style="display:none"' : '';
                    ?>
                    <div class="callout callout-warning" <?= $hasFields; ?>>
                        <p>No Fields are set for this category yet, please add some from "Actions" section Below.</p>
                    </div>
                </div>
            </div>

            <div class="box box-primary categories-fields-buttons-<?= $action;?>">
                <div class="box-header">
                    <div class="pull-left">
                        <h3 class="box-title"><?=t('app','Actions');?></h3>
                    </div>
                </div>
                <div class="box-body">
                    <?php
                        app()->trigger('admin.categories.form.fields.add', new \app\yii\base\Event(['params' => []]));
                    ?>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="icon" class="tab-pane fade">
            <div class="box box-primary categories-icons-<?= $action;?>">
                <div class="box-body list-icons">
                    <div class="row">
                        <?= $form->field($model, 'icon')->hiddenInput()->label(false);
                        if (!empty($fontAwesomeIcons)) {
                            foreach ($fontAwesomeIcons as $key => $value) {
                                $selected = ($value == $model->icon) ? 'active' : '';
                        ?>
                                <div class="fa-icons col-md-3 col-sm-4">
                                    <a href="#" data-icon="<?= $value; ?>" class="<?=$selected;?>">
                                        <i class="fa <?= $value; ?> fa-2x" aria-hidden="true"></i>
                                        <span class="sr-only">Example of </span>
                                        <?= str_replace('fa-', '', $value); ?>
                                    </a>
                                </div>
                        <?php }
                        } else {
                            echo t('app', 'Something went wrong...');
                        }?>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
    app()->trigger('admin.categories.form.fields.templates', new \app\yii\base\Event(['params' => []]));
?>

