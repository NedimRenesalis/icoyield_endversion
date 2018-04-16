<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use app\models\Currency;
use app\models\Category;
use app\models\Country;


/* @var $this yii\web\View */
/* @var $model app\models\Listing */

$this->title = t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Listing',
        ]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => t('app','Ads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = t('app', 'Update: ') . ' ' . $model->title;

?>

<div class="box box-primary listing">

    <div class="box-header">
        <div class="pull-left">
            <div class="box-title"><?= Html::encode($this->title) ?></div>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app','Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>

   <div class="box-body">

       <?php $form = ActiveForm::begin([
               'id' => 'admin-post-form'
       ]); ?>
       <div class="listing-form">
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model, 'title')->textInput([
                       'maxlength'          => true,
                       'data-content'      => t('app', 'Ad title'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    =>  'top'
                   ]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-12">
                   <?= $form->field($model,'description')->widget(CKEditor::className(), [
                       'options' => ['rows' => 6],
                       'preset' => 'basic',
                       'clientOptions' => [
                           'removePlugins' => 'pastefromword, tableselection',
                           'contentsCss' => [Yii::getAlias('@web/assets/site/css/customCkeditor.css')],
                           'toolbar'   => ['clipboard', 'cut, copy, paste'],
                       ]
                   ]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model,'category_id')->dropDownList(
                       Category::getAllCategories(),
                       [
                           'prompt'            => t('app', 'Select Category'),
                           'data-content'      => t('app', 'Category'),
                           'data-category-id'  => $model->category_id,
                           'data-listing-id'   => $model->listing_id,
                           'data-container'    => 'body',
                           'data-toggle'       => 'popover',
                           'data-trigger'      => 'hover',
                           'data-placement'    => 'top'
                       ]
                   ) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model,'currency_id')->dropDownList(
                       ArrayHelper::map(Currency::getActiveCurrencies(), 'currency_id', 'name'),
                       [
                           'prompt'            => t('app', 'Select Currency'),
                           'data-content'      => t('app', 'Currency'),
                           'data-container'    => 'body',
                           'data-toggle'       => 'popover',
                           'data-trigger'      => 'hover',
                           'data-placement'    => 'top'
                       ]
                   ) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($model, 'price')->textInput([
                       'maxlength'          => true,
                       'data-content'       => t('app', 'Price'),
                       'data-container'     => 'body',
                       'data-toggle'        => 'popover',
                       'data-trigger'       => 'hover',
                       'data-placement'     => 'top',
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($model, 'negotiable')->dropdownList([0 => t('app', 'No'), 1 =>t('app', 'Yes')],[
                       'data-content'      => t('app', 'Negotiable price'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top',
                   ]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-3">
                   <?= $form->field($customer, 'phone')->textInput([
                       'maxlength'          => true,
                       'data-content'      => t('app', 'Phone'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    =>  'top'
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($model, 'hide_phone')->dropdownList([0 => t('app', 'No'), 1 =>t('app', 'Yes')],[
                       'data-content'      => t('app', 'Hide phone'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top',
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($customer, 'email')->textInput([
                       'maxlength'          => true,
                       'data-content'      => t('app', 'Email'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top'
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($model, 'hide_email')->dropdownList([0 => t('app', 'No'), 1 =>t('app', 'Yes')],[
                       'data-content'      => t('app', 'Hide email'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top',
                   ]) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-3">
                   <?= $form->field($location, 'country_id')->dropdownList(
                       ArrayHelper::map(Country::getActiveCountries(), 'country_id', 'name'),
                       [
                       'data-content'      => t('app', 'Country'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top',
                   ]) ?>
               </div>
               <div class="col-lg-3" id="listings-select-zones-wrapper", data-url="/admin/listings/get-country-zones", data-zone="<?=(int)$location->zone_id?>">
                   <?= $form->field($location, 'zone_id')->dropdownList(
                       ArrayHelper::map(\app\models\Zone::getCountryZones($location->country_id), 'zone_id', 'name'),
                       [
                       'data-content'      => t('app', 'Zone'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top',
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($location, 'city')->textInput([
                       'maxlength'          => true,
                       'data-content'      => t('app', 'City'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top'
                   ]) ?>
               </div>
               <div class="col-lg-3">
                   <?= $form->field($location, 'zip')->textInput([
                       'maxlength'          => true,
                       'data-content'      => t('app', 'Zip'),
                       'data-container'    => 'body',
                       'data-toggle'       => 'popover',
                       'data-trigger'      => 'hover',
                       'data-placement'    => 'top'
                   ]) ?>
               </div>
           </div>
            <div class="row">
                <div class="col-lg-12" <?php if (options()->get('app.settings.common.disableMap', 0) == 1){?>  style="display: none" <?php  } ?>>
                    <div id="map-content" style="height: 414px; background-color: #eeebe8; filter: blur(10px);
                    -webkit-filter: blur(5px);
                    -moz-filter: blur(5px);
                    -o-filter: blur(5px);
                    -ms-filter: blur(5px);"></div>
                        <script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>&callback"></script>
                    </div>
                </div>
                <?php if (options()->get('app.settings.common.disableMap', 0) == 0){?>
                    <div class="col-lg-12">
                        <?= $form->field($location, 'latitude', [
                            'template' => '{input} {error}',
                        ])->hiddenInput()->label(false); ?>
                        <?= $form->field($location, 'longitude', [
                            'template' => '{input} {error}',
                            'options'    => ['style' => 'display:none'],
                        ])->hiddenInput()->label(false); ?>
                    </div>
                <?php } ?>
                <?= \yii\bootstrap\Html::hiddenInput('disableMap',options()->get('app.settings.common.disableMap', 0), [
                    'id' => 'disableMap'
                ]) ?>
                <?= \yii\bootstrap\Html::hiddenInput('adHideZip',options()->get('app.settings.common.adHideZip', 0), [
                    'id' => 'adHideZip'
                ]) ?>
            </div>
           <h4><?=t('app', 'Custom fields');?></h4>
           <hr>
           <div class="row category-fields" id="category-fields" data-url="<?=url(['/admin/listings/get-category-fields']);?>" style="display: none">

           </div>
           <h4><?=t('app', 'Ads Images');?></h4>
           <hr>
           <div class="row">
                <div class="col-lg-12">
                    <?= $form->field($images, 'image_form_key', [
                        'template' => '{input} {error}',
                        'options'    => ['style' => 'display:none'],
                    ])->hiddenInput(['value'=> $image_random_key, 'class' => 'form-control'])->label(false); ?>
                    <?php
                    $imagesPreview = [];
                    $imagesPreviewConfig = [];
                        // sort for images sort_order
                        usort($uploadedImages, function ($a, $b) { return strnatcmp($a['sort_order'], $b['sort_order']); });
                        if ($uploadedImages) foreach ($uploadedImages as $key => $image) {
                            $imagesPreview[] = $image->image;
                            $imagesPreviewConfig[$key]['caption'] = $image->image;
                            $imagesPreviewConfig[$key]['url'] = url(['/admin/listings/remove-ad-image']);
                            $imagesPreviewConfig[$key]['key'] = $image->image_id;
                    }
                    echo $form->field($images, 'imagesGallery[]')->widget(FileInput::classname(), [
                        'options' => [
                            'multiple' => true,
                            'accept' => 'image/*',
                            'class' =>'file-loading',
                            'data-sort-listing-images' => url(['/admin/listings/sort-ad-images']),
                        ],
                        'pluginOptions' => [
                            'initialPreview'=> $imagesPreview,
                            'initialPreviewConfig' =>
                                $imagesPreviewConfig
                            ,
                            'initialPreviewAsData'=>true,
                            'language' => html_encode(options()->get('app.settings.common.siteLanguage', 'en')),
                            'deleteUrl' => url(['/admin/listings/remove-ad-image']),
                            'uploadUrl' => url(['/admin/listings/upload-image']),
                            'uploadExtraData' => [
                                'image_form_key' => $image_random_key,
                                'adId'  => $model->listing_id,
                            ],
                            'uploadAsync' => true,
                            'allowedFileTypes' => ["image"],
                            'showUpload' => false,
                            'showRemove' => false,
                            'showClose' => false,
                            'browseOnZoneClick' => true,
                            'dropZoneEnabled' => false,
                            'browseLabel' => t('app', 'Browse ...'),
                            'browseClass' => 'btn btn-as btn-primary',
                            'removeClass' => 'btn btn-as reverse',
                            'uploadClass' => 'btn btn-as reverse',
                            'msgPlaceholder' => t('app', 'Select files...'),
                            'captionClass'  => [
                                'height' => '100px'
                            ],
                            'layoutTemplates' =>
                                [
                                    'fileIcon' => '',
                                    'footer'=> '<div class="file-thumbnail-footer">' . '{progress} {actions}' . '</div>'
                                ],
                            'fileActionSettings' => [
                                'showUpload' => false,
                                'showDrag' => true,
                            ],
                            'overwriteInitial'=>false,
                        ]
                    ])->label(false);
                    ?>
                </div>
           </div>
           <hr>
           <div class="row">
               <div class="col-lg-6">
                   <?= $form->field($model,'status')->dropDownList(
                       $model->getListingStatusesList(),
                       [
                           'prompt'            => t('app', 'Select Status'),
                           'data-content'      => t('app', 'Status'),
                           'data-container'    => 'body',
                           'data-toggle'       => 'popover',
                           'data-trigger'      => 'hover',
                           'data-placement'    => 'top'
                       ]
                   ) ?>
               </div>
               <div class="col-lg-6">
                   <label><?= t('app', 'Ad package');?></label> <br />
                   <?= $form->field($model, 'package_id')->dropdownList(
                       ArrayHelper::map(\app\models\ListingPackage::getAllPackages(), 'package_id', 'title'),
                       [
                           'prompt'            => t('app', 'Select Package'),
                           'data-content'      => t('app', 'Package'),
                           'data-container'    => 'body',
                           'data-toggle'       => 'popover',
                           'data-trigger'      => 'hover',
                           'data-placement'    => 'top',
                           'required'          => true
                       ]
                   )->label(false) ?>
               </div>
           </div>
           <div class="row">
               <div class="col-lg-12">
                   <p>
                       <?= t('app', 'Note: Changes applied to status will automatically rebuild the values of package\'s options! (eg. Auto renew... ) '); ?>
                   </p>
               </div>
           </div>
           <hr>
            <div class="row">
               <div class="col-lg-6">
                   <div class="form-group">
                       <label><?= t('app', 'Send email to Customer');?></label> <br />
                       <?= Html::dropDownList('send_email',[], [0 => t('app', 'No'), 1 =>t('app', 'Yes')],
                           [
                               'class'             => 'form-control',
                               'prompt'            => t('app', 'Select an option'),
                               'data-content'      => t('app', 'Send email to Customer to inform about the ad update'),
                               'data-container'    => 'body',
                               'data-toggle'       => 'popover',
                               'data-trigger'      => 'hover',
                               'data-placement'    => 'top',
                               'required'          =>  true
                           ]
                       )?>
                   </div>
               </div>
           </div>
       </div>
       <div class="box-footer">
           <div class="pull-right">
               <?= Html::submitButton(t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
           </div>
           <div class="clearfix"><!-- --></div>
       </div>
   </div>

    <?php $form = ActiveForm::end(); ?>
</div>
