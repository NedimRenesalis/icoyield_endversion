<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;


$this->title = t('app', 'Invoice Settings');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary">

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
                <div class="col-lg-4">
                    <?= $form->field($model, 'prefix')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Prefix to be used in all PDF invoices'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-8">
                    <?= $form->field($model, 'notes')->textArea([
                        'data-content'      => t('app', 'Notes to be displayed at the end of PDF invoice'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <?php $logo = options()->get('app.settings.invoice.logo', \Yii::getAlias('@web/assets/site/img/logo.png'));?>
                    <?= $form->field($model, 'logoUpload')->widget(\kartik\file\FileInput::classname(), [
                        'options' => [
                            'class'=>'image-upload',
                            'data-image'=>$logo,
                        ],
                        'pluginOptions' => [
                            'language' => options()->get('app.settings.common.siteLanguage', 'en'),
                            'overwriteInitial'=> true,
                            'showClose'=> false,
                            'showRemove' => false,
                            'showCaption'=> false,
                            'showBrowse'=> true,
                            'browseOnZoneClick'=> true,
                            'removeLabel'=> '',
                            'removeIcon'=> '<i class="glyphicon glyphicon-remove"></i>',
                            'removeTitle'=> 'Cancel or reset changes',
                            'elErrorContainer'=> '.image-upload-error',
                            'msgErrorClass'=> 'alert alert-block alert-danger',
                            'defaultPreviewContent'=> '<img src="'.$logo.'" alt="Your logo" style="width:400px">',
                            'layoutTemplates'=> ['main2'=> '{preview} {remove} {browse}'],
                            'allowedFileTypes' => ["image"],
                        ]
                    ])->label();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'disableInvoices')->dropDownList([1 => t('app','Yes'), 0 => t('app','No') ],[
                        'data-content'      => t('app', 'If it\'s set to yes then invoices section will be hidden'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]) ?>
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