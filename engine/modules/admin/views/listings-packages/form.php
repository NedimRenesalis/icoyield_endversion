<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ListingPackage;

if ($action == 'update') {
    $this->title = t('app', 'Update {modelClass}: ', [
            'modelClass' => t('app', 'Ads Packages'),
        ]) . $model->title;
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Ads Packages'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->package_id]];
    $this->params['breadcrumbs'][] = t('app', 'Update');
} else {
    $this->title = t('app', 'Create Ads Package');
    $this->params['breadcrumbs'][] = ['label' => t('app', 'Ads Packages'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="box box-primary listings-packages-<?= $action; ?>">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="pull-right">
            <?= Html::a(t('app', 'Cancel'), ['index'], ['class' => 'btn btn-xs btn-success']) ?>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="listings-packages-form">
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'title')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Package Title'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'price')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Package Price in {currency}', ['currency' => options()->get('app.settings.common.siteCurrency', 'usd')]),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, 'listing_days')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Number of days before an ad expires'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'promo_days')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Number of days before the promo feature expires, and the ad becomes regular'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-4">
                    <?= $form->field($model, 'auto_renewal')->textInput([
                            'maxlength' => true,
                            'data-content'      => t('app', 'Number of times for an ad to auto renew itself in the listing after expiring'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'promo_show_featured_area')->dropDownList(
                            ListingPackage::getYesNoList(),[
                            'data-content'      => t('app', 'Display the ad in promo widgets all over the site.'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top'
                    ]) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'promo_show_at_top')->dropDownList(
                            ListingPackage::getYesNoList(),[
                            'data-content'      => t('app', 'Display the ad in the first 5 listings on search and category pages.'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, 'promo_sign')->dropDownList(
                            ListingPackage::getYesNoList(),[
                            'data-content'      => t('app', 'Add Promoted label to the ad image'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top']) ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'recommended_sign')->dropDownList(
                            ListingPackage::getYesNoList(),[
                            'data-content'      => t('app', 'Add Recommended Label to package display block'),
                            'data-container'    => 'body',
                            'data-toggle'       => 'popover',
                            'data-trigger'      => 'hover',
                            'data-placement'    => 'top']) ?>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? t('app', 'Create') : t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"><!-- --></div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>