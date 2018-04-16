<?php
use yii\helpers\StringHelper;
use app\models\ListingStat;

$isFavorite = (!empty($model->favorite)) ? true : false;
?>

<div class="price">
    <?=html_encode($model->getPriceAsCurrency($model->currency->code));?>
    <?= $model->isNegotiable() ? '<span>' . t('app', 'Negotiable') . '</span>' : ''; ?>
</div>
<div class="image">
    <?= !empty($model->isPromoted) ? '<div class="promoted"><span>' . t('app', 'Promoted') . '</span></div>' : '' ?>
    <a href="#" data-listing-id="<?= (int)$model->listing_id; ?>" data-stats-type="<?= ListingStat::FAVORITE; ?>" data-add-msg="<?= t('app', 'Add to favorites'); ?>" data-remove-msg="<?= t('app', 'Remove Favorite'); ?>" data-favorite-url="<?= url(['/listing/toggle-favorite']); ?>" class="action-icon favorite-listing <?= (!$isFavorite) ? 'track-stats' : ''; ?>">
        <?php if ($isFavorite) { ?>
            <i class="fa fa-bookmark-o" aria-hidden="true"></i>
        <?php } else { ?>
            <i class="fa fa-bookmark-o" aria-hidden="true"></i>
        <?php } ?>
    </a>
    <a class="img-link" style="background-image: url('<?=($model->mainImage) ? $model->mainImage->image : '';?>');" href="<?= url(['/listing/index/', 'slug' => $model->slug]); ?>">
        <img class="lazyload" src="<?=Yii::getAlias('@web/assets/site/img/img-listing-list-empty.png');?>" data-src="<?=($model->mainImage) ? $model->mainImage->image : '';?>" alt=""/>
    </a>
</div>
<div class="info">
    <a href="<?= url(['category/index', 'slug' => $model->category->slug]); ?>" class="category"><i class="fa <?= html_encode($model->category->icon); ?>" aria-hidden="true"></i> <?= html_encode($model->category->name); ?></a>
    <a href="<?= url(['/listing/index/', 'slug' => $model->slug]); ?>" class="name">
        <span class="title"><?= html_encode($model->title); ?></span>
        <span><?= StringHelper::truncate(strip_tags(html_purify($model->description)), 80); ?></span>
    </a>
    <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> <?= html_encode($model->getZoneCountryString()); ?></div>
</div>