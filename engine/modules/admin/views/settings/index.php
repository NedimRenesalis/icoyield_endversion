<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Currency;
use app\models\Language;
use kartik\datecontrol\DateControl;

$this->title = t('app', 'General Settings');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#general"><?=t('app','General');?></a></li>
        <li><a data-toggle="tab" href="#listings"><?=t('app','Ads');?></a></li>
        <li><a data-toggle="tab" href="#seo"><?=t('app','SEO');?></a></li>
        <li><a data-toggle="tab" href="#api"><?=t('app','API Keys');?></a></li>
        <li><a data-toggle="tab" href="#page-sections"><?=t('app','Footer');?></a></li>
        <li><a data-toggle="tab" href="#maintenance"><?=t('app','Maintenance');?></a></li>
        <li class="pull-right">
            <?= Html::a('<i class="fa fa-ban" aria-hidden="true"></i>&nbsp&nbsp'.t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </li>
    </ul>
    <?php $form = ActiveForm::begin([
        'id'        => 'settings-form'
    ]); ?>
    <div class="tab-content">
        <div id="general" class="tab-pane fade in active">
            <div class="box-body">
                <div class="general-settings-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteName')->textInput([
                                    'maxlength'         => true,
                                    'data-content'      => t('app', 'Represents the site in invoices and sets the title of home page'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteEmail')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'Email of the site in all the invoices'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteCurrency')->dropDownList(ArrayHelper::map(Currency::getActiveCurrencies(), 'code', 'name'),[
                                    'class'=>'form-control',
                                    'prompt' => 'Please select',
                                    'data-content'      => t('app', 'Currency used to do payments for packages'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]);?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteLanguage')->dropDownList(ArrayHelper::map(Language::find()->all(),
                                function($model, $defaultValue) {
                                    return (!empty($model->region_code)) ? $model->language_code . '-' . $model->region_code : $model->language_code;
                                },
                                'name'),[
                                        'class'=>'form-control',
                                        'prompt' => 'Please select',
                                        'data-content'      => t('app', 'Represents the site Language'),
                                        'data-container'    => 'body',
                                        'data-toggle'       => 'popover',
                                        'data-trigger'      => 'hover',
                                        'data-placement'    => 'top'
                            ]);?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteTimezone')->dropDownList(\app\helpers\DateTimeHelper::getSystemInternalTimeZones(),[
                                'class'=>'form-control',
                                'prompt' => 'Please select',
                                'data-content'      => t('app', 'Timezone used for the application'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]);?>
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
        </div>
        <div id="seo" class="tab-pane fade in">
            <div class="box-body">
                <div class="general-settings-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteKeywords')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'Meta Keywords of home page'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteDescription')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'Meta Description of home page.'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'googleAnalyticsCode')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'Google analytics code should look like this UA-0000000-0.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'prettyUrl')->dropDownList([1 => t('app','Yes, enable pretty url'), 0 => t('app','No, do not use pretty url') ],[
                                'data-content'      => t('app', 'Enabling this will remove the index.php part of your urls.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group clean-urls-action" style="<?php if ($model->prettyUrl != 1){?>display:none<?php }?>">
                                <label><?= t('app', 'Action');?></label> <br />
                                <a data-toggle="modal" data-remote="<?=url(['settings/htaccess-modal']);?>" href="#writeHtaccessModal" class="btn btn-primary btn-flat"><?php echo t('app', 'Generate htaccess')?></a>
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
        </div>
        <div id="listings" class="tab-pane fade in">
            <div class="box-body">
                <div class="general-settings-form">
                    <h4><?=t('app', 'Ads Widgets');?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'relatedAds')->dropDownList(['yes' => t('app','Yes'), 'no' => t('app','No') ],[
                                'data-content'      => t('app', 'If it\'s set to yes then on every ad page will show a widget of related ads to that specific ad page.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'homePromotedNumber')->textInput([
                                'data-content'      => t('app', 'Number of promoted ads to display in the promoted ads section (e.g. 4, 8, 12...)'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'homeNewNumber')->textInput([
                                'data-content'      => t('app', 'Number of new ads to display in the new ads section (e.g. 4, 8, 12...)'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <h4><?=t('app', 'Ads Options');?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'adminApprovalAds')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app', 'If it\'s set to yes then all ads will have "Pending Approval" status until an admin activates them, otherwise ads will be active immediately.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'adHideZip')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app', 'If it\'s set to yes then zip code will be hidden, this should be set to no for most accurate position on google maps'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'disableMap')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app', 'If it\'s set to yes then map will be hidden, otherwise you need to provide Google Maps API KEY in API Keys section!'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'freeAdsLimit')->textInput([
                                'data-content'      => t('app', 'Maximum number of free ads per user.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top',
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'adsImagesLimit')->textInput([
                                'data-content'      => t('app', 'Maxim number of images per ads.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top',
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'expiredDays')->textInput([
                                'data-content'      => t('app', 'Number of days for customer to be notified before the ad expires. If it\'s set to 0, then the notification will be disabled.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top',
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'dailyNotification')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app','Send the notifications for expiration of the ads daily.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'skipPackages')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app','If it\'s set to yes then the default package will be auto selected to clients!'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4" id="show-default-package" style="<?php if ($model->skipPackages != 1){?>display:none<?php }?>">
                            <?= $form->field($model, 'defaultPackage')->dropDownList(ArrayHelper::map(\app\models\ListingPackage::find()->where(['price' => 0])->all(), 'package_id', 'title'),[
                                'data-content'      => t('app','Choose default free package.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <p>
                                <?= t('app', 'Note: If you choose to skip packages page then you will need to choose the default free package from the option that will appear next to it!'); ?>
                            </p>
                        </div>
                    </div>
                    <h4><?=t('app', 'Business');?></h4>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'disableStore')->dropDownList([ 1 => t('app','Yes'), 0 => t('app','No') ],[
                                'data-content'      => t('app','If it\'s set to yes then the upgrade to store option will be disabled!'),
                                'data-container'    => 'body',
                                'data-message'      => t('app', 'Are you sure you want to disable the option to upgrade to stores?'),
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
            </div>
        </div>
        <div id="api" class="tab-pane fade in">
            <div class="box-body">
                <div class="general-settings-form">
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteFacebookId')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'ID from Facebook app used for Social Login'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteFacebookSecret')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'API Secret key from Facebook app used for Social Login'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteGoogleId')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'ID from Google app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteGoogleSecret')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'API Secret key from Google app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteLinkedInId')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'ID from LinkedIn app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteLinkedInSecret')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'API Secret key from LinkedIn app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteTwitterId')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'ID from Twitter app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteTwitterSecret')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'API Secret key from Twitter app used for Social Login'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'siteGoogleMapsKey')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'API Key from Google app used for maps all over the site'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                            <?= $form->field($model, 'captchaSiteKey')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'If none filled, the option will be disabled by default'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-4">
                            <?= $form->field($model, 'captchaSecretKey')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'If none filled, the option will be disabled by default'),
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
            </div>
        </div>
        <div id="page-sections" class="tab-pane fade in">
            <div class="box-body">
                <div class="general-settings-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'footerFirstPageSectionTitle')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'Title of the first middle widget in the footer'),
                                    'data-container'    => 'body',
                                    'data-toggle'       => 'popover',
                                    'data-trigger'      => 'hover',
                                    'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'footerSecondPageSectionTitle')->textInput([
                                    'maxlength' => true,
                                    'data-content'      => t('app', 'Title of the second middle widget in the footer'),
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
            </div>
        </div>
        <div id="maintenance" class="tab-pane fade in">
            <div class="box-body">
                <div class="general-settings-form">
                    <div class="row">
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteStatus')->dropDownList([1 => 'Online', 0 => 'Offline' ],[
                                'data-content'      => t('app', 'If set to offline, application will be locked for all frontend customers, admin will have access everywhere.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                        <div class="col-lg-6">
                            <?= $form->field($model, 'siteOfflineMessage')->textInput([
                                'maxlength' => true,
                                'data-content'      => t('app', 'Message to display when site status is offline.'),
                                'data-container'    => 'body',
                                'data-toggle'       => 'popover',
                                'data-trigger'      => 'hover',
                                'data-placement'    => 'top'
                            ]) ?>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4">
                                <div class="form-group">
                                    <?= Html::activeLabel($model, 'expired_ads_maintenance', ['label' => t('app','Expired ads maintenance')]);?>
                                    <?= DateControl::widget([
                                        'name'=>'kartik-date-3',
                                        'type'=>DateControl::FORMAT_DATE,
                                        'displayFormat' => app()->formatter->dateFormat,
                                        'widgetOptions' => [
                                            'pluginOptions' => [
                                                'autoclose' => true
                                            ]
                                        ]
                                    ]);?>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label><?= t('app','Action') ?></label><br />
                                <a data-input-success="<?= t('app','You will delete all ads expired before ') ?>"
                                   data-input-error="<?= t('app','Please select a date before which the expired ads will be deleted!') ?>"
                                   data-msg-success="<?= t('app',' expired ads were deleted.')?>"
                                   data-url="<?= url(['settings/delete-expired-ads'])?>"
                                   id="expiredAdsId" class="btn btn-primary btn-flat"><?php echo t('app', 'Delete')?></a>
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
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<!-- MODAL HTACCESS -->
<div class="modal fade" id="writeHtaccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>