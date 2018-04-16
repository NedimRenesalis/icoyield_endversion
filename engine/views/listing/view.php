<?php
use app\assets\AdAsset;
use app\helpers\SvgHelper;
use app\models\ListingStat;
use app\models\Category;
use app\components\AdsListWidget;
use app\components\SendMessageWidget;
use \app\models\CustomerStore;
AdAsset::register($this);

$showGalleryArrows = (count($images) > 1) ? true : false;
$fullWidthGallery = (count($images) < 4) ? 'full-width' : '' ;
?>
<div class="view-listing">
    <section class="listing-heading">
        <div class="listing-heading-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <h1><?=html_encode(strtoupper($ad->title));?></h1>
                        <?php if(!$ad->isExpired) { ?>
                            <a href="#" class="link add-to-favorites favorite-listing" data-stats-type="<?=ListingStat::FAVORITE;?>" data-add-msg="<?=t('app','Bookmark');?>" data-remove-msg="<?=t('app','Remove Favorite');?>" data-favorite-url="<?=url(['/listing/toggle-favorite']);?>" data-listing-id="<?=(int)$ad->listing_id;?>">
                                <?php if ($ad->isFavorite) { ?>
                                    <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Remove Bookmark');?></span>
                                <?php } else { ?>
                                <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Bookmark');?></span>
                                <?php } ?>
                            </a>
                           
                            <div class="clearfix hidden-lg hidden-md hidden-sm"></div>
                            <div class="social-desktop">
                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::FACEBOOK_SHARES;?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                <a target="_blank" href="https://twitter.com/intent/tweet?text=<?=html_encode($ad->title);?>&url=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::TWITTER_SHARES;?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                <a href="mailto:?subject=<?=html_encode($ad->title);?>&body=<?=t('app', 'Read More:');?><?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::MAIL_SHARES;?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="price">
                            <?php if(!$ad->isExpired) { ?>
                                <div class="social-mobile">
                                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::FACEBOOK_SHARES;?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a target="_blank" href="https://twitter.com/intent/tweet?text=<?=html_encode($ad->title);?>&url=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::TWITTER_SHARES;?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    <a href="mailto:?subject=<?=html_encode($ad->title);?>&body=<?=t('app', 'Read More:');?><?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::MAIL_SHARES;?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                                </div>
                         <h2><b> Soft cap in fiat</b></h2> <h2><?=html_encode($ad->getPriceAsCurrency($ad->currency->code));?><span><?=$ad->isNegotiable() ? t('app','Negotiable') : '';?></span></h2>
                            <?php } else { ?>
                                <h2><?=strtoupper(\app\models\Listing::STATUS_EXPIRED);?></h2>
                            <?php } ?>
                        </div>
                        <?php if($ad->isOwnedBy(app()->customer->id)) { ?>
                        <div class="actions">
                            <a href="<?=url(['/listing/update/', 'slug' => $ad->slug]);?>" class="btn-as danger-action">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i><?=t('app','Revise');?>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="<?=url(['/']);?>"><i class="fa fa-server" aria-hidden="true"></i></a></li>
                    <?php $_categoryParents = Category::getAllParents($ad->category_id);
                    $categoryParents = array_reverse($_categoryParents);
                    foreach ($categoryParents as $categoryParent) { ?>
                        <li>
                            <a href="<?= url(['category/index', 'slug' => $categoryParent['slug']]); ?>">
                                <?= html_encode($categoryParent['name']); ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="mb10 col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 hidden-xs">
                <?php app()->trigger('ad.above.gallery', new \app\yii\base\Event()); ?>
            </div>
        </div>

        <div dir="ltr" class="row">
            <div dir="ltr" class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                <div dir="ltr" class="listing-gallery">
                    <div dir="ltr" class="img-wrapper <?=$fullWidthGallery;?>">
                        <span dir="ltr" class="zoom"><i dir="ltr" class="fa fa-search-plus" aria-hidden="true"></i></span>

                        <div dir="ltr" class="small-gallery owl-carousel owl-theme">
                            <?php foreach ($images as $image) { ?>
                                <div dir="ltr" class="item open-full-gallery"><img class="resizeImg" src="<?=$image->image;?>" alt=""/></div>
                            <?php } ?>
                        </div>
                        <?php if ($showGalleryArrows) { ?>
                            <a href="javascript:;" class="arrow-gallery gallery-left"><?= SvgHelper::getByName('arrow-left');?></a>
                            <a href="javascript:;" class="arrow-gallery gallery-right"><?= SvgHelper::getByName('arrow-right');?></a>
                        <?php } ?>

                    </div>
                    <div dir="ltr" class="thb-wrapper">
                        <span dir="ltr" class="zoom"><i dir="ltr" class="fa fa-search-plus" aria-hidden="true"></i></span>
                        <ul>
                            <?php $counterImage = 0;
                            foreach ($images as $image) {
                                $counterImage++;
                                if ($counterImage == 1) continue; ?>
                                <li><img dir="ltr" class="" src="<?=$image->image;?>" alt=""/></li>
                                <?php if ($counterImage == 4) break;
                            } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                <div class="listing-info">
                    <div class="listing-user">
                        
                        
                        <?php if(!$ad->isExpired) { ?>
                            <?php if (!empty($ad->customer->stores) && $ad->customer->stores->status === CustomerStore::STATUS_ACTIVE) { ?>
                           
                            <?php } ?>
                            <?php if (!$ad->hide_phone) { ?>
                                <div class=""><a href="#" target="_blank" id="listing-show-website" class="track-stats" data-stats-type="<?=ListingStat::SHOW_PHONE;?>" data-listing-id="<?=(int)$ad->listing_id;?>" data-url="<?=url(['/listing/get-customer-contact']);?>" data-customer-id="<?=(int)$ad->customer->customer_id;?>"><?=t('app', 'ICO WEBSITE');?></a></div>
                            <?php } ?>
                            <?php if (!$ad->hide_email) { ?>
                                <div class=""><a href="#"  target="_blank" id="listing-show-website-whitepaper" class="track-stats" data-stats-type="<?=ListingStat::SHOW_MAIL;?>" data-listing-id="<?=(int)$ad->listing_id;?>" data-url="<?=url(['/listing/get-customer-contact']);?>" data-customer-id="<?=(int)$ad->customer->customer_id;?>"><?=t('app', 'WHITEPAPER');?></a></div>
                            <?php } ?>
                        <?php } ?>
                        <br>
                        <b>Company location</b>
                        <div class="location"><?=html_encode($ad->location->getAddress());?> </div>
                        <br>
                        <b>ICO starting and ending date</b>
                        <div class=""><?=html_encode($ad->location->getCity());?> </div>
                    </div>
                    <?php if (options()->get('app.settings.common.disableMap', 0) == 0){?>
                        <div id="map" style="background-color: #eeebe8; filter: blur(10px);
                                -webkit-filter: blur(5px);
                                -moz-filter: blur(5px);
                                -o-filter: blur(5px);
                                -ms-filter: blur(5px);"></div>
                        <script>
                            var map;
                            setTimeout(function initMap() {
                                $('#map').css('filter', 'blur(0px)');
                                map = new google.maps.Map(document.getElementById('map'), {
                                    center: {lat: <?=(float)$ad->location->latitude;?>, lng: <?=(float)$ad->location->longitude;?>},
                                    zoom: 15,
                                    mapTypeControl: false,
                                    zoomControl: true,
                                    scaleControl: false,
                                    streetViewControl: false,
                                    styles: [
                                        {
                                            "featureType": "poi",
                                            "stylers": [
                                                { "visibility": "off" }
                                            ]
                                        },
                                        {
                                            "featureType": "transit",
                                            "elementType": "labels.icon",
                                            "stylers": [
                                                { "visibility": "off" }
                                            ]
                                        }
                                    ],
                                    gestureHandling: 'greedy',
                                });
                                marker = new google.maps.Marker({
                                    position:  new google.maps.LatLng(<?=(float)$ad->location->latitude;?>, <?=(float)$ad->location->longitude;?>),
                                });
                                marker.setMap(map);
                            },200);
                        </script>
                        <script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>"></script>
                    <?php } ?>
                </div>
                <a href="#" class="btn-as hidden-md hidden-sm hidden-xs favorite-listing" data-stats-type="<?=ListingStat::FAVORITE;?>" data-listing-id="<?=(int)$ad->listing_id;?>" data-add-msg="<?=t('app','Add to favorites');?>" data-remove-msg="<?=t('app','Remove Favorite');?>" data-favorite-url="<?=url(['/listing/toggle-favorite']);?>">
                    <?php if ($ad->isFavorite) { ?>
                        <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Remove Bookmark');?></span>
                    <?php } else { ?>
                        <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Bookmark');?></span>
                    <?php } ?>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="mt10 col-lg-12 col-md-12 col-sm-12 hidden-xs">
                <?php app()->trigger('ad.under.gallery', new \app\yii\base\Event()); ?>
            </div>
        </div>

        <div dir="ltr" class="big-gallery">
            <a href="javascript:;" class="x-close"><i class="fa fa-times" aria-hidden="true"></i></a>
            <div dir="ltr" class="big-gallery-wrapper">
                <div dir="ltr" class="container">
                    <div dir="ltr" class="row">
                        <div dir="ltr" class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                            <div dir="ltr" class="listing-heading-gallery">
                                <div dir="ltr" class="row">
                                    <div dir="ltr" class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <h1><?=html_encode(strtoupper($ad->title));?></h1>
                                        <?php if(!$ad->isExpired) { ?>
                                            <a href="#" class="link add-to-favorites favorite-listing" data-stats-type="<?=ListingStat::FAVORITE;?>" data-add-msg="<?=t('app','Add to favorites');?>" data-remove-msg="<?=t('app','Remove Favorite');?>" data-favorite-url="<?=url(['/listing/toggle-favorite']);?>" data-listing-id="<?=(int)$ad->listing_id;?>">
                                                <?php if ($ad->isFavorite) { ?>
                                                    <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Remove Bookmark');?></span>
                                                <?php } else { ?>
                                                    <i class="fa fa-bookmark-o" aria-hidden="true"></i> <span><?=t('app','Bookmark');?></span>
                                                <?php } ?>
                                            </a>
                                            <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::FACEBOOK_SHARES;?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                            <a target="_blank" href="https://twitter.com/intent/tweet?text=<?=html_encode($ad->title);?>&url=<?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::TWITTER_SHARES;?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                            <a href="mailto:?subject=<?=html_encode($ad->title);?>&body=<?=t('app', 'Read More:');?><?=url(['/listing/index/', 'slug' => $ad->slug], true);?>" class="social-link track-stats" data-listing-id="<?=(int)$ad->listing_id;?>" data-stats-type="<?=ListingStat::MAIL_SHARES;?>"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                                        <?php } ?>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <div class="price">
                                            <?php if(!$ad->isExpired) { ?>
                                                <h2><?=html_encode($ad->getPriceAsCurrency($ad->currency->code));?></h2>
                                                <?=$ad->isNegotiable() ? t('app','Negotiable') : '';?>
                                            <?php } else { ?>
                                                <h2><?=strtoupper(\app\models\Listing::STATUS_EXPIRED);?></h2>
                                            <?php } ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div dir="ltr" class="row">
                        <div dir="ltr" class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                            <div dir="ltr" class="full-gallery-wrapper">

                                <div dir="ltr" class="full-gallery owl-carousel owl-theme">
                                    <?php foreach ($images as $image) { ?>
                                        <div dir="ltr" class="item"><img dir="ltr" class="resizeImg" src="<?=$image->image;?>" alt=""/></div>
                                    <?php } ?>
                                </div>
                                <?php if ($showGalleryArrows) { ?>
                                    <a href="javascript:;" class="arrow-gallery gallery-left-big"><?= SvgHelper::getByName('arrow-left');?></a>
                                    <a href="javascript:;" class="arrow-gallery gallery-right-big"><?= SvgHelper::getByName('arrow-right');?></a>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       

        <?php if (!empty($ad->categoryFieldValues)) {
            $labelValueFields = [];
            foreach ($ad->categoryFieldValues as $field) {
                if ($field->field->type->name != 'checkbox' && $field->field->type->name != 'url' && !empty($field->value)) {
                    $labelValueFields[] = $field;
                }
            }
            if ($labelValueFields) { ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="separator-text no-margin-top"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="listing-custom labels">
                            <?php foreach ($labelValueFields as $field) { ?>
                                <div class="item labeled">
                                <span>
                                    <?= html_encode($field->field->label); ?>
                                </span>
                                    <span>
                                    <?= html_encode($field->value); ?> <?= ($field->field->unit) ? html_encode($field->field->unit) : ''; ?>
                                </span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        ?>

        <?php if (!empty($ad->categoryFieldValues)) {
            $checkboxFields = [];
            foreach ($ad->categoryFieldValues as $field) {
                if ($field->field->type->name == 'checkbox' && $field->value != 0) {
                    $checkboxFields[] = $field;
                }
            }
            if ($checkboxFields) { ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="separator-text"></div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="listing-custom">
                            <?php foreach ($checkboxFields as $field) { ?>
                                <div class="item"><?= html_encode($field->field->label); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            $urlFields = [];
            foreach ($ad->categoryFieldValues as $field) {
                if ($field->field->type->name == 'url' && $field->value !== '') {
                    $urlFields[] = $field;
                }
            }
            if ($urlFields) { ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="separator-text">
                            <span><?= t('app', 'Links');?></span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="listing-custom labels">
                            <?php foreach ($urlFields as $field) { ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="item labeled url">
                                        <ul>
                                            <li><span><?= html_encode($field->field->label); ?></span></li>
                                            <li>
                                                <span><a href="<?= $field->value ?>" target="_blank"><?= html_encode($field->value); ?></a></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="separator-text">
                    <span><?= t('app', '');?></span>
                </div>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <p><?=html_purify($ad->description);?></p>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <?php app()->trigger('ad.after.description', new \app\yii\base\Event()); ?>
            </div>
        </div>
    </div>
    <?= SendMessageWidget::widget(['listingSlug' => $ad->slug]); ?>

    <?= AdsListWidget::widget([
        'listType'  => AdsListWidget::LIST_TYPE_PROMOTED,
        'title'     => t('app', 'Similar ICOs'),
        'ad'        => $ad,
        'quantity'  => 4
    ]);
    ?>

    <?= AdsListWidget::widget([
        'listType'  => AdsListWidget::LIST_TYPE_RELATED,
        'title'     => t('app', 'Related ICOs'),
        'ad'        => $ad,
        'quantity'  => 4
    ]);
    ?>

</div>