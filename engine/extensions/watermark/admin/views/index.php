<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = t('app', 'Ad Image Watermark');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
<div class="box box-primary adBanners">
    <div class="box-header with-border">
        <h3 class="box-title"><?=t('app', 'Ad image watermark');?></h3>
    </div>
    <div class="box-body">
        <div class="panel-group">
            <div class="row">
                <div class="col-lg-6">
                    <?php $imageWatermark = options()->get('app.extensions.watermark.imageWatermark', \Yii::getAlias('@web/assets/site/img/watermark.png'))?>
                    <?= $form->field($model, 'imageWatermarkUpload')->widget(\kartik\file\FileInput::className(),[
                        'options' => [
                                'multiple' => false,
                                'accept' => 'image/png',
                                'class' => 'image-upload',
                                'data-image' => $imageWatermark,
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
                            'defaultPreviewContent'=> '<img src="'.$imageWatermark.'" alt="Your Avatar" style="width:400px">',
                            'layoutTemplates'=> ['main2'=> '{preview} {remove} {browse}'],
                            'allowedFileTypes' => ["image"],
                        ]
                    ])->label();
                    ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'watermarkPosition')->dropDownList($model::getWatermarkPositionList(),[
                            'data-content'      => t('app', 'Select the position of the watermark image.'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                        ]); ?>
                </div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'watermarkSize')->dropDownList($model::getWatermarkSizeList(),[
                        'data-content'      => t('app', 'Select the size of the watermark image based on the ad image ratio'),
                        'data-container'    => 'body',
                        'data-toggle'       => 'popover',
                        'data-trigger'      => 'hover',
                        'data-placement'    => 'top'
                    ]); ?>
                </div>
            </div>
        </div>
    </div>


    <div class="box-footer">
        <div class="pull-right">
            <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>