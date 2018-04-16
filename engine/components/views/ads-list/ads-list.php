<?php
use app\models\ListingStat;
$formatter = app()->formatter;
?>

<section class="listings-list">
    <div class="container">
        <?php if ($title) { ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1><?= html_encode($title);?></h1>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <?php foreach ($ads as $ad) { ?>
                <?php $isFavorite = (!empty($ad->favorite)) ? true : false; ?>
                <div class="col-lg-<?=$col;?> col-md-<?=$col;?> col-sm-<?=$col;?> col-xs-12 item">
                    
                    <div class="image">
                        <?= !empty($ad->isPromoted) ? '<div class="promoted"><span>' . t('app', '') . '</span></div>' : '' ?>
                        <a href="#" data-listing-id="<?= (int)$ad->listing_id; ?>" data-stats-type="<?= ListingStat::FAVORITE; ?>" data-add-msg="<?= t('app', 'Add to favorites'); ?>" data-remove-msg="<?= t('app', 'Remove Favorite'); ?>" data-favorite-url="<?= url(['/listing/toggle-favorite']); ?>" class="action-icon favorite-listing <?= (!$isFavorite) ? 'track-stats' : ''; ?>">
                            <?php if ($isFavorite) { ?>
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            <?php } else { ?>
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <?php } ?>
                        </a>
                        <a class="img-link" href="<?= url(['/listing/index', 'slug' => $ad->slug]); ?>" style="background-image: url('<?= ($ad->mainImage != null) ? $ad->mainImage->image : ''; ?>');"><img class="lazyload" src="<?= Yii::getAlias('@web/assets/site/img/img-listing-list-empty.png');?>" data-src="<?= ($ad->mainImage != null) ? $ad->mainImage->image : ''; ?>" alt="" /></a>
                    </div>
                    <div class="info">
                        <a href="<?= url(['category/index', 'slug' => $ad->category->slug]); ?>" class="category"><i class="fa <?= html_encode($ad->category->icon); ?>" aria-hidden="true"></i> <?= html_encode($ad->category->name); ?></a>
                        <a href="<?= url(['/listing/index', 'slug' => $ad->slug]); ?>" class="name">
                            <?= html_encode(strtoupper($ad->title)); ?>
                        </a>
                        <div class="location"><i class="fa fa-map-marker" aria-hidden="true"></i> <?= html_encode($ad->getZoneCountryString()); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
