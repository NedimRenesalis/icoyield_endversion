<?php
use yii\helpers\Html;
?>
<div id="field-select-javascript-template" style="display: none;">
    <div class="field-row" data-start-index="{index}" data-field-type="select">
        <?= Html::hiddenInput($model->formName() .'[select][{index}][field_id]', (int)$model->field_id); ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><span class="glyphicon glyphicon-th-large"></span> <?= t('app', 'New select field');?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'label');?>
                            <?= Html::textInput($model->formName().'[select][{index}][label]', html_encode($model->label), ['class' => 'form-control']); ?>
                            <?= Html::error($model, 'label', ['class' => 'help-block']);?>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'required');?>
                            <?= Html::dropDownList($model->formName().'[select][{index}][required]', html_encode($model->required), $model->getYesNoList(), ['class' => 'form-control']); ?>
                            <?= Html::error($model, 'required', ['class' => 'help-block']);?>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'sort_order');?>
                            <?= Html::textInput($model->formName().'[select][{index}][sort_order]', html_encode($model->sort_order), ['class' => 'form-control', 'required' => true]); ?>
                            <?= Html::error($model, 'sort_order', ['class' => 'help-block']);?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <?= Html::activeLabel($model, 'help_text');?>
                            <?= Html::textInput($model->formName().'[select][{index}][help_text]', html_encode($model->help_text), ['class' => 'form-control']); ?>
                            <?= Html::error($model, 'help_text', ['class' => 'help-block']);?>
                        </div>
                    </div>
                </div>
                <hr />
                <h4><?= t('app', 'Select options');?> <a href="javascript:;" class="btn btn-primary pull-right btn-select-add-option"><i class="fa fa-plus-square" aria-hidden="true"></i> <?= t('app','Option');?></a></h4>
                <hr />
                <div class="row">
                    <div class="select-options-list">

                    </div>
                </div>
            </div>

            <div class="panel-footer">
                <div class="pull-right">
                    <a href="javascript:;" class="btn btn-danger btn-flat btn-remove-text-field" data-field-id="0" data-message="<?= t('app', 'Are you sure you want to remove this field? There is no coming back from this after you save the changes.');?>"><?= t('app', 'Remove');?></a>
                </div>
                <div class="clearfix"><!-- --></div>
            </div>
        </div>
    </div>
</div>

<div id="field-select-option-javascript-template" style="display: none;">
    <div class="col-lg-6 select-option-row" data-start-index="{optionIndex}" data-parent-index="{parentIndex}">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <?= Html::activeLabel($option, 'name');?>
                    <?= Html::textInput($option->formName().'[select][{parentIndex}][{optionIndex}][name]', html_encode($option->name), ['class' => 'form-control']); ?>
                    <?= Html::error($option, 'name', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <?= Html::activeLabel($option, 'value');?>
                    <?= Html::textInput($option->formName().'[select][{parentIndex}][{optionIndex}][value]', html_encode($option->value), ['class' => 'form-control']); ?>
                    <?= Html::error($option, 'value', ['class' => 'help-block']);?>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <div class="pull-left" style="margin-top: 25px;">
                        <a href="javascript:;" class="btn btn-danger btn-flat btn-remove-select-option-field" data-option-id="0" data-message="<?= t('app', 'Are you sure you want to remove this option? There is no coming back from this after you save the changes.');?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>