<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\extensions\adBanners\helpers\AdBannersHelper;

$this->title = t('app', 'Ad Banners');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>

<div class="box box-primary adBanners-head-scripts">
    <div class="box-header with-border">
        <h3 class="box-title"><?=t('app', htmlspecialchars('<head> scripts for online advertisers networks'));?></h3>
    </div>
    <div class="box-body">

        <div class="groups-form">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-block alert-warning">
                        <ul>
                            <li><?=t('app','Important: Please be careful what scripts you include in the head area of the application!');?></li>
                        </ul>
                    </div>
                    <br />
                    <?= $form->field($model, 'headScripts')->textarea([
                        'class'             => 'form-control',
                        'data-content'      => t('app', 'Paste head/verification script code e.g. Google AdSense head code'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ])->label(false) ?>
                    <p>
                        <?= t('app', 'Note: The above textarea supports multiple advertising networks like Google Adsense, Amazon associates .. etc.'); ?>
                        <br />
                        <?= t('app', 'For multiple networks just paste the head/verification code one after another in the textarea.'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box box-primary adBanners">

    <div class="box-header with-border">
        <h3 class="box-title"><?=t('app', 'AdBanners Locations');?></h3>
    </div>

    <div class="box-body">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php
            foreach ($properties as $property) { ?>
                <div class="panel panel-primary">
                    <div class="panel-heading" role="tab" id="heading-<?=$property['optionKey'];?>">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-<?=$property['optionKey'];?>" aria-expanded="true" aria-controls="collapse-<?=$property['optionKey'];?>">
                                <i class="fa fa-chevron-circle-down" aria-hidden="true"></i> <?=Html::encode($property['label']);?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse-<?=$property['optionKey'];?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-<?=$property['optionKey'];?>">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="page-header">
                                    <h3 class="panel-title"><i class="fa fa-info-circle" aria-hidden="true"></i> <?=Html::encode($property['help']);?></h3>
                                </div>
                            </div>
                            <?= Html::textarea($model->formName() . '[locations][' . $property['optionKey'] . ']', options()->get('app.extensions.adBanners.' . $property['optionKey'], ''), [
                                'class'             => 'form-control',
                                'data-content'      => t('app', 'Paste advertising script code like Google AdSense, partner banner via HTML code or Any HTML code.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>


    <div class="box-footer">
        <div class="pull-right">
            <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
        <div class="pull-left">
            <?= Html::a(t('app', 'Cancel'), ['index'], ['class' => 'btn btn-success']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>