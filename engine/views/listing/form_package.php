<?php
use app\assets\AdAsset;
use yii\bootstrap\ActiveForm;
use \app\yii\base\Event;

AdAsset::register($this);

?>
<section class="post-listing">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php $form = ActiveForm::begin([
                    'id'        => 'package-form',
                    'method'    => 'post',
                ]); ?>
                    <div class="block">
                        <div class="block-heading">
                            <div class="row">
                                <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                                    <h1>
                                        <?=t('app','Packages');?>
                                        <span class="info"><?=t('app','Please take a few moments to choose the right package');?>.</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="block-body">
                            <div class="row">
                                <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                                    <div dir="ltr" class="post-listing-promote">
                                        <div class="promote-slider">
                                            <div class="owl-carousel owl-theme">
                                                <?php foreach ($packages as $key=>$package) {
                                                    ?>
                                                    <div class="item" data-url="<?=url(['/listing/get-summary']);?>" data-price="<?=html_encode($package->price);?>" data-country_id="<?=(int)$ad->location->country_id;?>" data-zone_id="<?=(int)$ad->location->zone_id;?>">
                                                        <input type="radio" name="Listing[package_id]" value="<?=(int)$package->package_id;?>" id="package-<?=(int)$package->package_id;?>" />
                                                        <label for="package-<?=(int)$package->package_id;?>">
                                                            <?php if ($package->recommended_sign == 'yes') { ?>
                                                                <div class="recommended"><?=t('app','recommended');?></div>
                                                            <?php } ?>
                                                            <div class="info">
                                                                <div class="name"><?=html_encode($package->title);?></div>
                                                                <div class="description">
                                                                    <ul>
                                                                        <?php
                                                                        $classPromoAreas            = ($package->promo_show_featured_area == 'yes' ) ? 'fa-check' : 'fa-close';
                                                                        $classPromoPriority         = ($package->promo_show_at_top == 'yes' ) ? 'fa-check' : 'fa-close';
                                                                        $classPromoFeaturedLabel    = ($package->promo_sign == 'yes' ) ? 'fa-check' : 'fa-close';
                                                                        ?>
                                                                        <li>
                                                                            <span><?=html_encode($package->auto_renewal);?></span>
                                                                            <?=t('app','Auto renewals');?>
                                                                            <a href="javascript:;"
                                                                               class="btn-hint pull-right"
                                                                               data-container="body"
                                                                               data-toggle="popover"
                                                                               data-trigger="hover"
                                                                               data-placement="left"
                                                                               title="<?=t('app','Auto renewals');?>"
                                                                               data-content="<?=t('app','The number of times an ad will auto renew.');?>">
                                                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <span><?=html_encode($package->promo_days);?></span>
                                                                            <?=t('app','Featured days');?>
                                                                            <a href="javascript:;"
                                                                               class="btn-hint pull-right"
                                                                               data-container="body"
                                                                               data-toggle="popover"
                                                                               data-trigger="hover"
                                                                               data-placement="left"
                                                                               title="<?=t('app','Featured days');?>"
                                                                               data-content="<?=t('app','The number of days for this ad to be featured with the other package options.');?>">
                                                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa <?=html_encode($classPromoFeaturedLabel);?>" aria-hidden="true"></i>
                                                                            <?=t('app','Show featured label');?>
                                                                            <a href="javascript:;"
                                                                               class="btn-hint pull-right"
                                                                               data-container="body"
                                                                               data-toggle="popover"
                                                                               data-trigger="hover"
                                                                               data-placement="left"
                                                                               title="<?=t('app','Show featured label');?>"
                                                                               data-content="<?=t('app','This option will add a label on the ad image.');?>">
                                                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa <?=html_encode($classPromoAreas);?>" aria-hidden="true"></i>
                                                                            <?=t('app','Show in featured areas');?>
                                                                            <a href="javascript:;"
                                                                               class="btn-hint pull-right"
                                                                               data-container="body"
                                                                               data-toggle="popover"
                                                                               data-trigger="hover"
                                                                               data-placement="left"
                                                                               title="<?=t('app','Show in featured areas');?>"
                                                                               data-content="<?=t('app','This option will make the ad visible in the featured sections of the website.');?>">
                                                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <i class="fa <?=html_encode($classPromoPriority);?>" aria-hidden="true"></i>
                                                                            <?=t('app','Priority featured ad');?>
                                                                            <a href="javascript:;"
                                                                               class="btn-hint pull-right"
                                                                               data-container="body"
                                                                               data-toggle="popover"
                                                                               data-trigger="hover"
                                                                               data-placement="left"
                                                                               title="<?=t('app','Priority featured ad');?>"
                                                                               data-content="<?=t('app','This option will add a priority criteria to your ad on pages.');?>">
                                                                                <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="check-area"></div>
                                                            <div class="buy">
                                                                <span class="btn-as large"><?=app()->formatter->asCurrency($package->price);?></span>
                                                            </div>
                                                            <div class="expires"><?=t('app','expires in {listingDays} days', ['listingDays' => html_encode($package->listing_days)]);?></div>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-wrapper">

                            </div>

                            <div class="row" id="payment-block" style="display: none">
                                <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="separator-text">
                                                <span><?=t('app','Please choose a payment method');?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row payment-method">
                                        <?php app()->trigger('app.ad.gateways.option', new Event(['params' => []])); ?>
                                    </div>

                                    <div class="" id="payment-details-block" style="">
                                        <?php app()->trigger('app.ad.gateways.form', new Event(['params' => []])); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block">
                        <div class="block-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="a-center">
                                        <a href="<?=url(['/listing/update/' . $ad->slug]);?>" class="btn-as reverse"><?=t('app','Revise');?></a>
                                        <button type="submit" class="btn-as"><?=t('app','Finish');?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>

