<?php

use yii\widgets\ActiveForm;

?>

<section class="post-listing">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="block">
                    <div class="block-heading">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                <h1>
                                    <?= t('app', 'Contact Us'); ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <?php $form = ActiveForm::begin([
                            'id'        => 'send-contact-form',
                            'method'    => 'post',
                            'action'    => ['site/send-contact-form'],
                    ]); ?>
                    <div class="block-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <?= options()->get('app.content.contact.shortDescription', ''); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="separator-text">
                                    <span><?= t('app', 'Contact')?></span>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?= $form->field($model, 'fullName', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Full Name'), 'class' => 'form-control'])->label(false); ?>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                    <?= $form->field($model, 'email', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Email'), 'class' => 'form-control'])->label(false); ?>
                                </div>
                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                    <?= $form->field($model, 'phone', [
                                        'template'      => '{input} {error}',
                                    ])->textInput([ 'placeholder' => t('app','Phone'), 'class' => 'form-control'])->label(false); ?>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?= $form->field($model, 'message', [
                                        'template'      => '{input} {error}',
                                    ])->textarea([ 'placeholder' => t('app','Message'), 'class' => 'form-control'])->label(false); ?>
                                </div>
                                <?php if ($captchaSiteKey = options()->get('app.settings.common.captchaSiteKey', '')) { ?>
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <?= $form->field($model, 'reCaptcha')->widget(
                                            \himiklab\yii2\recaptcha\ReCaptcha::className(),
                                            ['siteKey' => html_encode($captchaSiteKey)]
                                        )->label(false); ?>
                                    </div>
                                <?php } ?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 <?= $captchaSiteKey ? 'text-right' : '' ?>">
                                    <button type="submit" class="btn-as" value="Submit" id="send-contact-button" data-loading-text="<i class='fa fa-circle-o-notch fa-2 fa-spin'></i> Processing..."><?=t('app','Send');?></button>
                                </div>

                            </div>
                            <?php if (options()->get('app.content.contact.enableMap', 0) == 1){?>
                                <?php
                                $zoom = 7;
                                if(options()->get('app.app.content.contact.city', '') == '' || options()->get('app.content.contact.zone', '') == '' || options()->get('app.content.contact.zip', 0) == 0){$zoom = 10;}?>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="separator-text">
                                        <span><?= t('app', 'Map')?></span>
                                    </div>
                                    <div id="map" style="height: 414px; background-color: #eeebe8; filter: blur(10px);
                                        -webkit-filter: blur(5px);
                                        -moz-filter: blur(5px);
                                        -o-filter: blur(5px);
                                        -ms-filter: blur(5px);"></div>
                                    <script>
                                        var map;
                                        setTimeout(function initMap() {
                                            $('#map').css('filter', 'blur(0px)');
                                            map = new google.maps.Map(document.getElementById('map'), {
                                                center: {lat: <?=(float)options()->get('app.content.contact.latitude', 0)?>, lng: <?=(float)options()->get('app.content.contact.longitude', 0)?>},
                                                zoom: <?=$zoom?>,
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
                                                ]
                                            });
                                        },200);
                                    </script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>" async defer></script>
                                </div>
                            <?php } ?>
                         </div>
                        <?php ActiveForm::end(); ?>
                     </div>
                </div>
            </div>
        </div>
    </div>
</section>
