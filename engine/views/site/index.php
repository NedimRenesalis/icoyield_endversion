<?php
use app\components\AdsListWidget;
use app\assets\AppAsset;

AppAsset::register($this);
$this->title = html_encode(options()->get('app.settings.common.siteName', 'EasyAds'));
?>

<section class="main-search">
    <?= $this->render('_homepage-search', [
            'searchModel' => $searchModel,
    ]); ?>

</section>

<div class="container">
    <div class="row">
        <div class="mb10 col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 hidden-xs">
            <?php app()->trigger('home.under.categories', new \app\yii\base\Event()); ?>
        </div>
    </div>
</div>

<?= $promoted = AdsListWidget::widget(['listType' => AdsListWidget::LIST_TYPE_PROMOTED, 'title' => t('app', ''), 'quantity' => options()->get('app.settings.common.homePromotedNumber', 8)]) ?>
<?php
    if (!empty($promoted)) {
        echo AdsListWidget::widget(['listType' => AdsListWidget::LIST_TYPE_NEW, 'title' => t('app', 'New ICOs'), 'emptyTemplate' => false, 'quantity' => options()->get('app.settings.common.homeNewNumber', 8)]);
    } else {
        echo AdsListWidget::widget(['listType' => AdsListWidget::LIST_TYPE_NEW, 'title' => t('app', 'New ICOs'), 'emptyTemplate' => true, 'quantity' => options()->get('app.settings.common.homeNewNumber', 8)]);
    }
?>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 hidden-xs">
            <?php app()->trigger('home.above.footer', new \app\yii\base\Event()); ?>
        </div>
    </div>
</div>
