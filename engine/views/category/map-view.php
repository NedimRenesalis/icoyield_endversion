<?php
use yii\widgets\ListView;
use yii\helpers\Html;
use app\helpers\SvgHelper;
use app\assets\AppAsset;
use app\components\AdsListWidget;

AppAsset::register($this);
?>
<div class="listing-on-map">

    <div class="dropdown">
        <a href="javascript:;" class="dropdown-toggle" id="dropdownSubCategories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= !empty($category) ? html_encode($category->name) : t('app', 'All categories'); ?>
            <?php
            if (!empty($childCategories)) {
                echo SvgHelper::getByName('arrow-bottom');
            }
            ?>
        </a>
        <?php if (!empty($childCategories)) { ?>
            <div class="dropdown-menu" aria-labelledby="dropdownSubCategories">
                <?php foreach ($childCategories as $childCat) { ?>
                    <?= Html::a(html_encode($childCat->name), ['category/map-view', 'slug' => $childCat->slug, $paramsSearch['key'] => $paramsSearch['ListingSearch']]) ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <section class="listings-list-2" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h1><?= !empty($category) ? html_encode($category->name) : t('app', 'All categories'); ?></h1>
                </div>
                <!-- child categories -->
                <?php if (!empty($childCategories)) { ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="child-categories-list">
                            <?= SvgHelper::getByName('arrow-top');?>
                            <ul>
                                <?php foreach ($childCategories as $childCat) { ?>
                                    <li><?= Html::a(html_encode($childCat->name), ['category/map-view', 'slug' => $childCat->slug, $paramsSearch['key'] => $paramsSearch['ListingSearch']]) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Start init map -->
    <?php
    if (strpos($locationDatabase, 'co-') === 0) {
        $zoom = 6;
    } elseif (strpos($locationDatabase, 'zo-') === 0 ){
        $zoom = 12;
    } else $zoom = 13;
    ?>
    <?php if (!$listingAds) { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="no-results"><?= t('app', 'No results found') ?></span>
        </div>
    <?php } ?>

    <div class="map-wrapper">
        <div class="map" id="map" data-url="<?=url(['/category/get-map-location']);?>" style="height: 800px; background-color: #eeebe8; filter: blur(10px);
                    -webkit-filter: blur(5px);
                    -moz-filter: blur(5px);
                    -o-filter: blur(5px);
                    -ms-filter: blur(5px);">
        </div>
        <div class="overlay-loading-map" style="display: block;">
            <div class="icon-placeholder">
                <i class="fa fa-spinner fa-spin" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?=html_encode(options()->get('app.settings.common.siteGoogleMapsKey', ''));?>"></script>
    <script>

        var ads = <?= json_encode($listingAds)?>;
        var zoom = <?= $zoom ?>;
        var latitude;
        var longitude;
        var map;
        var content = [];
        var mc;

        function createMarker(latlng, contentFinal){
            var j;
            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                position: latlng
            });

            //get array of markers currently in cluster
            var allMarkers = mc.getMarkers();

            var countAds = 0;

            //check to see if any of the existing markers watch the latlng of the new marker
            if (allMarkers.length !== 0) {
                for (j = 0; j < allMarkers.length; j++) {
                    var currentMarker = allMarkers[j];
                    var pos = currentMarker.getPosition();

                    if (latlng.equals(pos)) {
                        contentFinal = contentFinal + content[j];
                        countAds++;
                    }
                }
            }

            google.maps.event.addListener(marker, 'click', function () {
                infowindow.close();
                if (countAds > 0) {
                    var text = 'Scroll to see all '
                    infowindow.setContent('<div class="tooltip-list"><span class="tool-list-title"><?=t('app','Scroll to see all results');?></span>' + contentFinal + '</div>');
                } else {
                    infowindow.setContent('<div class="tooltip-list">' + contentFinal + '</div>');
                }
                infowindow.open(map,marker);
            });

            return marker;
        }


        if (ads.length === 1) {

            latitude = ads[0]['lat'];
            longitude = ads[0]['lng'];
            zoom = 13;

            setTimeout(function(){ initMap(latitude, longitude); }, 500);

        } else {

            var tid = setInterval(getCoordinates, 1000);

            function getCoordinates() {
                var locationDetails;
                var locationDatabase;

                locationDetails = '<?= $locationDetails; ?>';
                locationDatabase = '<?= $locationDatabase; ?>';

                if(locationDetails && locationDatabase) {
                    jQuery.post(jQuery('#map').data('url'),{
                        locationDetails: locationDetails,
                        locationDatabase: locationDatabase
                    }, function (json) {

                        if(json.content.latitude){

                            latitude = json.content.latitude;
                            longitude = json.content.longitude;
                            setTimeout(function(){ initMap(latitude, longitude); }, 500);

                            abortTimer();
                        }
                    });
                } else {
                    abortTimer();
                }
            }

            function abortTimer() {
                clearInterval(tid);
            }
        }

        function initMap(latitude, longitude) {

            jQuery('#map').css('filter', 'blur(0px)');
            jQuery('.map-wrapper .overlay-loading-map').removeAttr('style');
            jQuery('.listing-search form').removeClass('search-map');


            google.maps.visualRefresh = true;

            var mapOptions = {
                zoom: zoom,
                center: {lat: latitude, lng: longitude},
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                panControl: false,
                scaleControl: false,
                streetViewControl: false,
                zoomControl: true,
                scrollwheel: false,
                gestureHandling: 'greedy',
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
            };
            var mapElement = document.getElementById('map');

            map = new google.maps.Map(mapElement, mapOptions);

            mc = new MarkerClusterer(map, [], {
                imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
                maxZoom: 14
            });
            var k;

            var address= [];
            for (k = 0; k < ads.length; k++){

                content[k] = '<div class="map-tooltip">' +
                    '<a class="image" target="_blank" href="/index.php/listing/index/' + ads[k]['slug'] + '">' + '<img src="' + ads[k]['img'] + '" style="">' + '</a>' +
                    '<div class="copy">' +
                    '<h1>' + '<a target="_blank" href="/index.php/listing/index/' + ads[k]['slug'] + '">' + ads[k]['title'] + '</a>' + '</h1>' +
                    '<h3>' + '<span>' + ads[k]['price'] + '</span>' + '</h3>' +
                    '<a class="" target="_blank" href="/index.php/listing/index/' + ads[k]['slug'] + '">' + '<?=t('app', 'View Ad'); ?>' + '</a>' +
                    '</div>' +
                    '</div>';

                address[k] =  new google.maps.LatLng(ads[k]['lat'], ads[k]['lng'])
                var marker = createMarker(address[k],content[k]);
                mc.addMarker(marker);
            }
        }
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
    <!-- End init map -->

</div>

<section class="main-search">
    <?= $this->render('_main-search-map', [
        'categories'              => $categories,
        'categoryPlaceholderText' => $categoryPlaceholderText,
        'searchModel'             => $searchModel,
        'customFields'            => $customFields,
        'locationDetails'         => $locationDetails,
        'selectedCategory'        => $category,
        'advanceSearchOptions'    => $advanceSearchOptions,
    ]); ?>
</section>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-push-2 col-md-8 col-md-push-2 col-sm-12 hidden-xs">
            <?php app()->trigger('category.map.bottom', new \app\yii\base\Event()); ?>
        </div>
    </div>
</div>


