<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\options\Contact */

$this->title = t('app', 'Contact Form Settings');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary">

    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="contact-form-settings-form">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'keywords')->textInput([
                        'maxlength' => true,
                        'data-content'      => t('app', 'Page Meta Keywords'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'description')->textInput([
                        'maxlength' => true,
                        'data-content'      => t('app', 'Page Meta Description'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    =>  'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'contactEmail')->textInput([
                        'maxlength' => true,
                        'data-content'      => t('app', 'Email account where contact messages will be send'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'senderEmail')->dropdownList([0 => t('app', 'No'), 1 =>t('app', 'Yes')],[
                        'data-content'      => t('app', 'Choosing yes will send a copy of message to sender'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top',
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($model,'shortDescription')->widget(CKEditor::className(), [
                        'options' => ['rows' => 6],
                        'preset' => 'basic'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <?= $form->field($model, 'enableMap')->dropDownList([0 => t('app', 'No, do not enable map'), 1 => t('app', 'Yes, enable map')],[
                        'data-content'      => t('app', 'Enabling this will display a map on your contact form.'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="clear-address-options" style="<?php if ($model->enableMap != 1){?>display:none<?php }?>">
                    <div class="col-lg-3">
                        <?= $form->field($model, 'country')->textInput([
                            'id'                => 'contact-country',
                            'maxlength'         => true,
                            'data-content'      => t('app', 'Country name'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'zone')->textInput([
                            'id'                => 'contact-zone',
                            'maxlength'         => true,
                            'data-content'      => t('app', 'Zone name'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'city')->textInput([
                            'id'                => 'contact-city',
                            'maxlength'         => true,
                            'data-content'      => t('app', 'City name'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                    <div class="col-lg-2">
                        <?= $form->field($model, 'zip')->textInput([
                            'id'                => 'contact-zip',
                            'data-content'      => t('app', 'Zip code'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                    <div class="col-lg-1">
                        <label><?= t('app', 'Action');?></label> <br />
                        <a data-toggle="modal" data-url="<?= url(['contact/verify-address'])?>" href="#googleModal" class="btn btn-primary btn-flat" id="verify-address"><?php echo t('app', 'Verify')?></a>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'latitude')->textInput([
                            'id'                => 'latitude-address',
                            'maxlength'         => true,
                            'data-content'      => t('app', 'Latitude'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'longitude')->textInput([
                            'id'                => 'longitude-address',
                            'maxlength'         => true,
                            'data-content'      => t('app','Longitude'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model,'section')->dropDownList(
                        \app\models\Page::getSectionsList(),
                        [
                                'prompt'            => t('app', 'Select selection'),
                                'data-content'      => t('app', 'The section where this contact should display in footer'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                        ]
                    ) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'sort_order')->dropDownList(
                        $model->section ? $pageModel->getListOfAvailablePositions($model->section) : [],
                        [
                            'prompt'       => t('app', 'Select position'),
                            'data-url'     => url(['pages/get-available-positions']),
                            'data-content'      => t('app', 'The order in footer section where this contact should be display'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]
                    ) ?>
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

<!-- MODAL MAP -->
<div class="modal fade" id="googleModal" data-msg-error="<?= t('app', 'Address not found, please insert another location.')?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <div class="modal-header" id="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><?php echo t('app', 'Map content')?></h4>
                    <h4 class="modal-error"</div>
                </div>
                <div class="modal-body" id="modal-body">
                    <div class="modal-message" id="map-content" style="height: 500px; background-color: #eeebe8; filter: blur(10px);
                                -webkit-filter: blur(5px);
                                -moz-filter: blur(5px);
                                -o-filter: blur(5px);
                                -ms-filter: blur(5px);">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo t('app', 'Close');?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>&callback" async defer></script>