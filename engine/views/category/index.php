<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use app\helpers\SvgHelper;
use app\assets\AppAsset;
use app\components\AdsListWidget;

AppAsset::register($this);
?>

<section class="main-search">
    <?= $this->render('_main-search', [
        'categories'              => $categories,
        'categoryPlaceholderText' => $categoryPlaceholderText,
        'searchModel'             => $searchModel,
        'customFields'            => $customFields,
        'locationDetails'         => $locationDetails,
        'selectedCategory'        => $category,
        'advanceSearchOptions'    => $advanceSearchOptions
    ]); ?>
</section>

<div class="container">
    <div class="row">
        <div class="mb10 col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 hidden-xs">
            <?php app()->trigger('category.above.results', new \app\yii\base\Event()); ?>
        </div>
    </div>
</div>

<section class="listings-list-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-push-2 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h1><?= !empty($category) ? html_encode($category->name) : t('app', 'All categories'); ?></h1>
                    </div>
                </div>
                <!-- child categories -->
                <?php if (!empty($childCategories)) { ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="child-categories-list">
                                <?= SvgHelper::getByName('arrow-top');?>
                                <ul>
                                    <?php foreach ($childCategories as $childCat) { ?>
                                        <li><?= Html::a(html_encode($childCat->name), ['category/index', 'slug' => $childCat->slug, $paramsSearch['key'] => $paramsSearch['ListingSearch']]) ?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?= ListView::widget([
                    'id'               => 'category-listings',
                    'dataProvider'     => $adsProvider,
                    'layout'           => '
                        <div class="row">
                            {items}
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="pagination-custom">
                                    <div class="row">
                                        {pager}
                                    </div>
                                </div>
                            </div>
                        </div>
                    ',
                    'itemView'         => '_ad-item',
                    'emptyText'        => '',
                    'itemOptions'      => [
                        'tag'   => 'div',
                        'class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12 item',
                    ],
                    'emptyTextOptions' => ['tag' => 'ul', 'class' => 'list invoice'],
                    'pager'            => [
                        'class'         => 'app\widgets\CustomLinkPager',
                        'prevPageLabel' => SvgHelper::getByName('arrow-left'),
                        'nextPageLabel' => SvgHelper::getByName('arrow-right')
                    ]
                ]); ?>

                <?php if ($isNothingFound) { ?>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="no-results"><?= t('app', 'No results found') ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
<section class="others-listings">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-push-2 col-md-12 col-sm-12 col-xs-12">
                <?php if ($isNothingFound) { ?>
                    <?= AdsListWidget::widget([
                        'listType'  => AdsListWidget::LIST_TYPE_PROMOTED,
                        'title'     => t('app', 'Similar ICOs'),
                        'quantity'  => 3
                    ]);
                    ?>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
