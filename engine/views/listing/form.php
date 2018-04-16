<?php
use app\assets\AdAsset;
use yii\bootstrap\ActiveForm;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use app\helpers\FrontendHelper;
use yii\helpers\ArrayHelper;
use app\models\Currency;
use app\models\Country;
use yii\bootstrap\Html;

AdAsset::register($this);
$adsImagesNumber = (int)options()->get("app.settings.common.adsImagesLimit", 4);

$script = <<< JS
$(document).ready(function() {
    $('#post-form').yiiActiveForm('add',
        {
            "id":"listingimage-imagesgallery",
            "name":"imagesGallery[]",
            "container":".field-listingimage-imagesgallery",
            "input":"#listingimage-imagesgallery",
            "error":".help-block.help-block-error",
            "validate":function (attribute, value, messages, deferred) {
                if ($('.file-preview-frame').length == 0) {
                    yii.validation.required(value, messages, {"message": $('#post-form').data('err-msg-gallery')});
                }
                if ($('.file-preview-frame').length > 2*$adsImagesNumber) {
                    yii.validation.addMessage(messages, $('#post-form').data('err-msg-img-limit'), value);
                }
            }
        }
    );
}); 
JS;

$this->registerJs($script, yii\web\View::POS_LOAD);
?>
<section class="post-listing <?=$action;?>">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php $listing_id = (int)($action == 'update') ? $ad->listing_id : 0;
                    $form = ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data',
                            'data-listing'   => $listing_id,
                            'data-err-msg-gallery' => t('app', 'Please add at least your ICOs logo. Besides you can upload additional 19 pictures, screenshots, graphics.'),
                            'data-err-msg-img-limit' => t('app', 'The number of uploaded images exceeds the maximum allowed limit of {limitNumber} images', [
                                'limitNumber' => $adsImagesNumber,
                            ])
                        ],
                        'id'        => 'post-form',
                        'method'    => 'post',
                ]); ?>
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 col-xs-12">
                                <h1><?= ($action == 'update') ? t('app', 'Update your ICO') : t('app', 'Submit your ICO'); ?></h1>
                            </div>
                        </div>
                    </div>
                    <br>
                 <center>  Listing your ICO on ICOyield is free for 7 days. <br><br>Due to the high volume of ICO listing requests, usual publishing time is up to 15 days.  <br><br><b>Besides we also offer a processing fee for priority listing your ICO within 24 hours. <br>This processing fee is 0.3ETH.</b>
                    <br>
                    <br> If you prefer a prolonged listing duration, the fee for 30 days is 1ETH and for 45 days 1.3ETH.
                    <br>
                    <br> <b>In addition to priority listing you may contact us for a "Platinum listing package" (details are in our advertising section) offering additional ICO promotion. <br><br>After submitting your ICO, please email us: team@icoyield.com if you want a Platinum listing, a priority listing and/or a prolonged 30 or 45 days listing duration. </br> </center>
                    <br>
                    <div class="block-body">
                        <div class="row">
                       
                            <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?= $form->field($ad, 'title', [
                                            'template'      => '{input} {error}',
                                        ])->textInput([ 'placeholder' => t('app','Enter the ICO name - Enter the ICOs token name'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                </div>
                                <br>
                             <u>  <center> <br>Please provide following information below in the ICO description:</u>
                                <br>Our hard cap is...
                                <br>Our soft cap is...
                                <br>Platform: 
                                <br>Restricted countries:
                                <br>Whitelist/KYC:
                                <br>ICO description text </center>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?= $form->field($ad, 'description')->widget(CKEditor::className(), [
                                            'options' => ['rows' => 6],
                                            'preset' => 'basic',
                                            'clientOptions' => [
                                                'removePlugins' => 'pastefromword, tableselection',
                                                'contentsCss' => [Yii::getAlias('@web/assets/site/css/customCkeditor.css')],
                                                'toolbar'   => ['clipboard', 'cut, copy, paste'],
                                            ]
                                        ])->label(); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="separator-text">
                                            <span><?=t('app','Choose category');?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                                        <div class="form-group hidden-xs">
                                            <a href="#" id="choose-class" class="form-control" data-toggle="modal" data-target="#modal-category"><?=t('app', 'Choose category');?></a>
                                        </div>
                                        <div class="form-group hidden-lg hidden-md hidden-sm">
                                            <a href="javascript:;" id="choose-class-m" class="form-control choose-catg-m"><?=t('app', 'Choose category');?></a>
                                        </div>
                                        <?= $form->field($ad, 'category_id', [
                                            'template'      => '{input} {error}',
                                        ])->hiddenInput(['class' => 'form-control'])->label(false); ?>
                                    </div>
                                </div>

                                <div class="modal fade" id="modal-category" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog add-category" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="choose-category">

                                                    <div class="column-category primary-category">
                                                        <h4><?=t('app','Categories');?></h4>
                                                        <div class="category-items mCustomScrollbar mCS-autoHide">
                                                            <ul>
                                                                <?php foreach ($categories as $category) {
                                                                    if (empty($category->parent_id)) { ?>
                                                                        <li>
                                                                            <a href="#" data-id="<?=(int)$category->category_id;?>">
                                                                                <span><i class="fa <?=html_encode($category->icon);?>" aria-hidden="true"></i></span>
                                                                                <span><?=html_encode($category->name);?></span>
                                                                            </a>
                                                                        </li>
                                                                <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="column-subcategory mCustomScrollbar mCS-autoHide">
                                                        <div class="column-subcategory-wrapper">
                                                            <?php $sortedCategories =  \app\helpers\FrontendHelper::getCategoriesSorted($categories);
                                                            foreach ($sortedCategories as $sortedCategoryId=>$sortedCategory) { ?>
                                                                <div class="column-category" data-parent="<?=(int)$sortedCategoryId;?>" style="display: none">
                                                                    <h4><?=html_encode($sortedCategory['name']);?></h4>
                                                                    <div class="category-items mCustomScrollbar mCS-autoHide">
                                                                        <ul>
                                                                            <?php foreach ($sortedCategory['children'] as $childCategory) { ?>
                                                                            <li>
                                                                                <a href="#" data-id="<?=(int)$childCategory->category_id;?>" class="subcateg">
                                                                                    <span><?=html_encode($childCategory->name);?></span>
                                                                                </a>
                                                                            </li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <span class="no-category-selected"><?=t('app','Please select a specific category');?></span>
                                                        <button id="success-selection" type="button" class="btn-as" style="display: none" data-dismiss="modal"><?= t('app', 'Submit');?></button>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <button id="close-modal" type="button" class="btn-as danger-action pull-right" data-dismiss="modal"><?= t('app', 'Cancel');?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="choose-category-mobile">
                                    <a href="#" class="close-x-categ-m"><i class="fa fa-times" aria-hidden="true"></i></a>
                                    <div class="maincateg-m">
                                        <div class="heading">
                                            <a href="#" class="close-categ-m"><i class="fa fa-arrow-left" aria-hidden="true"></i></a> <?=t('app', 'Choose category');?>
                                        </div>
                                        <ul class="categ-items">
                                            <?php foreach ($categories as $category) {
                                                if (empty($category->parent_id)) { ?>
                                                <li>
                                                    <a href="#" data-id="<?=(int)$category->category_id;?>" class="categ-item-m" data-subcateg="<?= (!empty($category->children)) ? html_encode($category->slug) : '';?>">
                                                        <span><i class="fa <?=html_encode($category->icon);?>" aria-hidden="true"></i></span>
                                                        <span><?=html_encode($category->name);?></span>
                                                    </a>
                                                </li>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <?php $sortedCategories = FrontendHelper::getCategoriesSorted($categories);
                                    foreach ($sortedCategories as $sortedCategoryId=>$sortedCategory) { ?>
                                        <div id="subcateg-<?=html_encode($sortedCategory['slug']);?>" class="subcateg-m">
                                            <div class="heading">
                                                <a href="#" data-parent="<?=(int)$sortedCategoryId;?>" class="back-categ-m">
                                                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                                </a>
                                                <?=html_encode($sortedCategory['name']);?>
                                            </div>
                                            <ul class="categ-items">
                                                <?php foreach ($sortedCategory['children'] as $childCategory) { ?>
                                                    <li>
                                                        <a href="#" data-id="<?=(int)$childCategory->category_id;?>" data-subcateg="<?= (!empty($childCategory->children)) ? html_encode($childCategory->slug) : '';?>" class="categ-subitem-m">
                                                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                                            <?=html_encode($childCategory->name);?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="separator-text">
                                            <span><?=t('app','Soft cap in fiat');?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php $currencyNumber = count(ArrayHelper::map(Currency::getActiveCurrencies(),'currency_id', 'name'));?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?php if($currencyNumber > 1) { ?>
                                            <?= $form->field($ad, 'currency_id', [
                                                'template' => '{input} {error}',
                                            ])->dropDownList(ArrayHelper::map(Currency::getActiveCurrencies(), 'currency_id', 'name'), ['class' => '', 'prompt' => t('app', 'Currency')])->label(false);
                                        } else { ?>
                                            <?= $form->field($ad, 'currency_id', [
                                                'template' => '{input} {error}',
                                                'options'    => ['style' => 'display:none'],
                                                ])->hiddenInput(['value'=> Currency::getActiveCurrencies()[0]->currency_id, 'class' => 'form-control'])->label(false); ?>
                                            <?= $form->field($ad, 'currency_id', [
                                                'template' => '{input} {error}',
                                            ])->dropDownList(ArrayHelper::map(Currency::getActiveCurrencies(), 'currency_id', 'name'), ['value' => Currency::getActiveCurrencies()[0]->currency_id, 'class' => '', 'prompt' => t('app', 'Currency'), 'disabled' => true])->label(false);
                                        } ?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <?= $form->field($ad, 'price', [
                                            'template'      => '{input} {error}',
                                        ])->textInput(['placeholder' => t('app','Soft cap in fiat'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                   
                                </div>
                                <center>If you intend to list your soft cap value in crypto, please indicate this in the above-mentioned ICO description form field. 
                                <br>Here please convert your soft cap either in EUR or in USD.</center>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="separator-text">
                                            <span><?=t('app','Contact details');?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <?= $form->field($customer, 'phone', [
                                            'template'      => '{input} {error}',
                                        ])->textInput(['placeholder' => t('app','Web address'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <?= $form->field($customer, 'email', [
                                            'template'      => '{input} {error}',
                                        ])->textInput(['placeholder' => t('app','Web address whitepaper'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <?php $countryNumber = count(ArrayHelper::map(Country::getActiveCountries(),'country_id', 'name'));?>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <?php if($countryNumber > 1) { ?>
                                            <?= $form->field($location, 'country_id', [
                                                'template'      => '{input} {error}',
                                            ])->dropDownList(ArrayHelper::map(Country::getActiveCountries(), 'country_id', 'name'),['class'=>'', 'prompt' => t('app','Country'),])->label(false);
                                        }else { ?>
                                            <?= $form->field($location, 'country_id', [
                                                'template' => '{input} {error}',
                                                'options'    => ['style' => 'display:none'],
                                            ])->hiddenInput(['value'=> Country::getActiveCountries()[0]->country_id, 'class' => 'form-control'])->label(false); ?>
                                            <?= $form->field($location, 'country_id', [
                                                'template'      => '{input} {error}',
                                            ])->dropDownList(ArrayHelper::map(Country::getActiveCountries(), 'country_id', 'name'),['value' => Country::getActiveCountries()[0]->country_id, 'class'=>'', 'prompt' => t('app','Country'), 'disabled' => true])->label(false);
                                        } ?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="listing-select-zones-wrapper" data-url="<?=url(['/listing/get-country-zones']);?>" data-zone = <?=(int)$location->zone_id;?>>
                                        <?= $form->field($location, 'zone_id', [
                                            'template' => '{input} {error}',
                                        ])->dropDownList(array(), ['class' => '', 'prompt' => t('app', 'Zone')])->label(false);?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <?= $form->field($location, 'city', [
                                            'template'      => '{input} {error}',
                                        ])->textInput(['placeholder' => t('app','Date of starting - date of ending'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                    <?php if (options()->get('app.settings.common.adHideZip', 'en') == 0) { ?>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <?= $form->field($location, 'zip', [
                                            'template'      => '{input} {error}',
                                        ])->textInput(['placeholder' => t('app','Zip code'), 'class' => 'form-control'])->label(false); ?>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php if (options()->get('app.settings.common.disableMap', 0) == 1){?>  style="display: none" <?php  } ?>>
                                        <div id="map-content" style="height: 414px; background-color: #eeebe8; filter: blur(10px);
                                            -webkit-filter: blur(5px);
                                            -moz-filter: blur(5px);
                                            -o-filter: blur(5px);
                                            -ms-filter: blur(5px);"></div>
                                        <script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>&callback"></script>
                                    </div>
                                    <?php if (options()->get('app.settings.common.disableMap', 0) == 0){?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?= $form->field($location, 'latitude', [
                                                'template' => '{input} {error}',
                                            ])->hiddenInput()->label(false); ?>
                                            <?= $form->field($location, 'longitude', [
                                                'template' => '{input} {error}',
                                                'options'    => ['style' => 'display:none'],
                                            ])->hiddenInput()->label(false); ?>
                                        </div>
                                    <?php  } ?>
                                    <?= Html::hiddenInput('disableMap',options()->get('app.settings.common.disableMap', 0), [
                                            'id' => 'disableMap'
                                    ]) ?>

                                    <?= Html::hiddenInput('adHideZip',options()->get('app.settings.common.adHideZip', 0), [
                                            'id' => 'adHideZip'
                                    ]) ?>
                                </div>
                                <div class="row category-fields" style="display: none">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="separator-text">
                                            <span><?=t('app','Category Fields');?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row category-fields" id="category-fields" data-url="<?=url(['/listing/get-category-fields']);?>" style="display: none">
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="separator-text">
                                            <span><?=t('app','Photo Gallery - You can upload your logo plus additional 19 pictures, graphics and screenshots ');?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?= $form->field($images, 'image_form_key', [
                                            'template' => '{input} {error}',
                                            'options'    => ['style' => 'display:none'],
                                        ])->hiddenInput(['value'=> $image_random_key, 'class' => 'form-control'])->label(false); ?>
                                        <?php
                                        $imagesPreview = [];
                                        $imagesPreviewConfig = [];
                                        if ($action == 'update') {
                                            // sort for images sort_order
                                            usort($uploadedImages, function ($a, $b) { return strnatcmp($a['sort_order'], $b['sort_order']); });
                                            if ($uploadedImages) foreach ($uploadedImages as $key => $image) {
                                                $imagesPreview[] = $image->image;
                                                $imagesPreviewConfig[$key]['caption'] = $image->image;
                                                $imagesPreviewConfig[$key]['url'] = url(['/listing/remove-ad-image']);
                                                $imagesPreviewConfig[$key]['key'] = $image->image_id;
                                            }
                                         }
                                        echo $form->field($images, 'imagesGallery[]')->widget(FileInput::classname(), [
                                            'options' => [
                                                'multiple' => true,
                                                'accept' => 'image/*',
                                                'class' =>'file-loading',
                                                'data-sort-listing-images' => url(['/listing/sort-ad-images']),
                                            ],
                                            'pluginOptions' => [
                                                'initialPreview'=> $imagesPreview,
                                                'initialPreviewConfig' =>
                                                    $imagesPreviewConfig
                                                ,
                                                'initialPreviewAsData'=>true,
                                                'language' => html_encode(options()->get('app.settings.common.siteLanguage', 'en')),
                                                'uploadUrl' => url(['/listing/upload-image']),
                                                'uploadExtraData' => [
                                                    'image_form_key' => $image_random_key,
                                                    'action' => $action,
                                                    'adId'  => $ad->listing_id,
                                                ],
                                                'uploadAsync' => true,
                                                'allowedFileTypes' => ["image"],
                                                'showUpload' => false,
                                                'showRemove' => false,
                                                'showClose' => false,
                                                'browseOnZoneClick' => true,
                                                'dropZoneEnabled' => false,
                                                'browseLabel' => t('app', 'Browse ...'),
                                                'browseClass' => 'btn btn-as',
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block">
                    <div class="block-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php if ($action == 'create' || $ad->status != \app\models\Listing::STATUS_ACTIVE) { ?>
                                <div class="a-center">
                                    <button type="submit" name="process[package]" class="btn-as"><?=t('app','Submit');?></button>
                                   
                                </div>
                                <?php } else { ?>
                                <div class="a-center">
                                    <button type="submit" name="process[update-info]" class="btn-as"><?=t('app','Update Ad Info');?></button>
                                    <?php if (options()->get('app.settings.common.skipPackages', 0) == 0) { ?>
                                        <button type="submit" name="process[package]" class="btn-as"><?=t('app','Update Ad Package');?></button>
                                    <?php } ?>
                                   
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>