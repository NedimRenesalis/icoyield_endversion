$(document).ready(function(){
    $('[data-toggle = "popover"]').popover();

    $(document).ajaxComplete(function() {
        $('[data-toggle = "popover"]').popover();
    });

    $('[data-switch-style = "bootstrap"]').bootstrapSwitch();

    /**
     * Header
     */
    // custom toggle adminlte
    $(document).on('click','.custom-toggle',function () {
        $('body').addClass('sidebar-mini');
    });


    /**
     * Categories Page
     */
    // confirm dialog if save and no fields set
    $(document).on('submit','.categories-create form, .categories-update form',function () {
        if((!$('.list-fields').find('.field-row').length) && (!$('.list-fields-parent').find('.field-row').length)){
            if(!confirm($(this).data('message'))){
                return false;
            }
        }
    });

    // switch load parent fields
    $(document).on('switchChange.bootstrapSwitch','#categories-switch-inherit-parent-fields',function(e, state){
        var parentId = $(this).data('parent-id');
        var parentPath = $(this).data('parent-path');
        var noParentFieldMsg = $(this).data('no-parent-field-msg');

        $('.list-fields').find('#parentHasNoFieldsMsg').remove();
       if(state) {
           $('.list-fields').find('.callout.callout-warning').hide();

           $('.categories-fields-update > div:first-child, .categories-fields-create > div:first-child').after('<div class="box-body list-fields-parent"></div>');

           $('.list-fields-parent').load(parentPath + '?id=' + parentId + ' .list-fields > .field-row', function (response, status, xhr) {
               // change input hidden of data received to not overwrite
               $('.list-fields-parent').find('input[type=hidden]').val('0');
               if($('.list-fields-parent').is(':empty')) {
                   $('.list-fields').prepend('<div id="parentHasNoFieldsMsg" class="callout callout-warning">' + noParentFieldMsg + '</div>');
                   if(!$('.list-fields').find('.field-row').length){
                       $('.list-fields .callout.callout-warning').show();
                   }
               }
           });
       } else {
           $('.categories-fields-update .list-fields-parent, .categories-fields-create .list-fields-parent').remove();
           if(!$('.list-fields').find('.field-row').length){
               $('.list-fields .callout.callout-warning').show();
           }
       }
    });

    // change data-parent-id on create category to work with switch load fields
    $(document).on('change','.categories-create #category-parent_id, .categories-update #category-parent_id', function () {
        if($(this).val()) {
            $('#categories-switch-inherit-parent-wrapper').show();
            $('#categories-switch-inherit-parent-fields').data('parent-id',$(this).val());
        } else {
            $('#categories-switch-inherit-parent-wrapper').hide();
        }
    });

    // select icon for a category
    $(document).on('click','.fa-icons > a',function(e){
        e.preventDefault();
        var selectedIcon = $(this).data('icon');
        $('.fa-icons a').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.row').find('input[type=hidden]#category-icon').val(selectedIcon);
    });

    /**
     * Pages page
     */
    var currentSlug = '';

    $('#sluggable-title').on('change', function () {
        var currentValue = $(this).val();

        if (currentValue && !currentSlug) {
            $('#sluggable-slug').val(getSlug(currentValue));
        }
    });

    $('#sluggable-slug').on('input', function () {
        currentSlug = $(this).val();
    });

    // javascript range to generate range like php function
    var range = function(start, end, step) {
        var range = [];
        var typeofStart = typeof start;
        var typeofEnd = typeof end;

        if (step === 0) {
            throw TypeError("Step cannot be zero.");
        }

        if (typeofStart == "undefined" || typeofEnd == "undefined") {
            throw TypeError("Must pass start and end arguments.");
        } else if (typeofStart != typeofEnd) {
            throw TypeError("Start and end arguments must be of same type.");
        }

        typeof step == "undefined" && (step = 1);

        if (end < start) {
            step = -step;
        }

        if (typeofStart == "number") {
            while (step > 0 ? end >= start : end <= start) {
                range.push(start);
                start += step;
            }
        } else {
            throw TypeError("Only number type are supported");
        }

        return range;
    };

    // retrieve list for sort pages in section
    $('#page-section').on('change', function (e) {
        $.post($('#page-sort_order').data('url'), {section_id: $(this).val(), page_id: $('#page-sort_order').data('page-id')}, function (json) {
            $('#page-sort_order option:gt(0)').remove();
            // generate new range like in action, to sort result in right way
            // because of incorrect sort of keys in javascript
            var rangeToOrderResponse = range(-30, 30, 1);

            if (json.result == 'success') {
                rangeToOrderResponse.forEach(function (key) {
                    if (key in json.response) {
                        $('#page-sort_order').append($('<option>').text(json.response[key]).attr('value', json.response[key]));
                    }
                });
            }
        }, 'json');
    });

    /**
     * Contact page
     */

    // retrieve list for sort pages in section
    $('#contact-section').on('change', function (e) {
        $.post($('#contact-sort_order').data('url'), {section_id: $(this).val(), page_id: $('#contact-sort_order').data('page-id')}, function (json) {
            $('#contact-sort_order option:gt(0)').remove();
            // generate new range like in action, to sort result in right way
            // because of incorrect sort of keys in javascript
            var rangeToOrderResponse = range(-30, 30, 1);

            if (json.result == 'success') {
                rangeToOrderResponse.forEach(function (key) {
                    if (key in json.response) {
                        $('#contact-sort_order').append($('<option>').text(json.response[key]).attr('value', json.response[key]));
                    }
                });
            }
        }, 'json');
    });

    $('select#contact-enablemap').on('change', function(){
        var $this = $(this);
        if ($this.val() == 1) {
            $('.clear-address-options').show();
        } else {
            $('.clear-address-options').hide();
        }
    });

    $('#googleModal').on('show.bs.modal', function (e) {

        var zoom = 7;
        var country = $('#contact-country').val();
        var zone = $('#contact-zone').val();
        var city = $('#contact-city').val();
        var zip = $('#contact-zip').val();

        if(zone || city || zip) zoom = 10;

        $.post($('#verify-address').data('url'),
        {
            country: country,
            zone: zone,
            city: city,
            zip: zip
        }, function (json) {
            var notifyContainer = notify.getOption('container');
            notify.setOption('container', '.modal-error');
            if (json.result == 'success'){
                notify.remove();
                if(json.content.error_message){ notify.addError(json.content.error_message);}
                if(json.content.hasOwnProperty('status') &&
                    json.content.status == 'ZERO_RESULTS'){
                    notify.addWarning($("#googleModal").data("msg-error"))
                }

                if(json.content.latitude && json.content.longitude){
                    document.getElementById('latitude-address').value = json.content.latitude;
                    document.getElementById('longitude-address').value = json.content.longitude;

                    initMap(zoom, json.content.latitude, json.content.longitude);
                }

            }
            notify.show();
            notify.setOption('container', notifyContainer);
        });
    });

    var map;
    window.initMap = function(zoom, latitude, longitude) {

        $('#map-content').css('filter', 'blur(0px)');
        // Enabling new cartography and themes
        google.maps.visualRefresh = true;

        // Setting starting options
        var mapOptions = {
            center: new google.maps.LatLng(latitude, longitude),
            zoom: zoom,
            mapTypeIds: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            panControl: false,
            scaleControl: false,
            streetViewControl: false,
            zoomControl: true,
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

        // Getting Map DOM Element
        var mapElement = document.getElementById('map-content');

        // Creating a map with DOM element
        map = new google.maps.Map(mapElement, mapOptions);
    }


    /**
     * Taxes Page
     */

    $(document).on('change','#tax-country_id',function (e) {
        e.preventDefault();

        $.post($('#taxes-select-zones-wrapper').data('url'),{country_id:$(this).val()},function (json) {
            $('#taxes-select-zones-wrapper select option.gen-op').remove();
            $.each(json.response,function(key,value){
                $('#taxes-select-zones-wrapper select').append($('<option class="gen-op">').text(value.name).attr('value', value.zone_id));
            });
        }, 'json');
    });

    setTimeout(function () {
        //autocomplete zone on update
        if($('select#tax-country_id').val() && $('#taxes-select-zones-wrapper').data('zone')){
            $('select#tax-country_id').val($('select#tax-country_id').val()).trigger('change');
            $(document).ajaxStop(function () {
                $('select#tax-zone_id').val($('#taxes-select-zones-wrapper').data('zone')).trigger('change');
            });
        }
    },100);

    /**
     * Gateways Page
     */

    $('.gateways-index .tab-content div:first').addClass('active');
    $('.gateways-index .nav-tabs li:first').addClass('active');

    /**
     * Deactivate countries, currencies, zones
     */

    $('#deactivate-all-countries, #deactivate-all-currencies').on('click', function () {
        alert('Customers can post ads only if they have at least one item activated!');
    });
    $('#deactivate-all-zones').on('click', function () {
        alert('Customers can post ads only if they have at least one active zone of an active country!');
    });

    /**
     * Settings
     */

    $('#common-disablestore').on('change',function () {
        if ($('#common-disablestore').val() === '1') {
            if(!confirm($('#common-disablestore').data('message'))){
                return false;
            }
        }

    });

    $('select#common-prettyurl').on('change', function(){
        var $this = $(this);
        if ($this.val() == 1) {
            $('.clean-urls-action').show();
        } else {
            $('.clean-urls-action').hide();
        }
    });

    $(document).on('click', 'button.btn-write-htaccess', function(){
        var $this = $(this);
        $this.button('loading');
        $.get($this.data('remote'), {}, function(json){
            $this.button('reset');
            var notifyContainer = notify.getOption('container');
            notify.remove();
            notify.setOption('container', '.modal-message');
            if (json.result === 'success') {
                notify.addSuccess(json.message);
            } else {
                notify.addError(json.message);
            }
            notify.show();
            notify.setOption('container', notifyContainer);
        }, 'json');
    });

    $(document).on('click', '.btn.bulk-delete', function(e) {
        e.preventDefault();
        var selectedRows = $('.grid-view').yiiGridView('getSelectedRows');
        if (selectedRows.length) {
            if (confirm("Are you sure you want to delete selected conversations?")) {
                $.post("delete-multiple",
                    {
                        pk: selectedRows
                    },
                    function () {
                        $.pjax.reload({container: '#conversation-pjax-container'});
                    }
                );
            }
        } else {
            alert('Please select at least one item.');
        }
    });

    $(document).on('click', '.btn.bulk-delete-messages', function(e) {
        e.preventDefault();
        var selectedRows = $('.grid-view').yiiGridView('getSelectedRows');
        if (selectedRows.length) {
            if (confirm("Are you sure you want to delete selected messages?")) {
                $.post("delete-multiple-messages",
                    {
                        pk: selectedRows
                    },
                    function () {
                        $.pjax.reload({container: '#conversation-messages-pjax-container'});
                    }
                );
            }
        } else {
            alert('Please select at least one item.');
        }
    });

    $('select#common-skippackages').on('change', function(){
        var $this = $(this);
        if ($this.val() == 1) {
            $('#show-default-package').show();
        } else {
            $('#show-default-package').hide();
        }
    });

    $('#expiredAdsId').on('click', function (e) {
        var date = $('#w1-disp').val();

        if (!date) {
            alert($('#expiredAdsId').data("input-error"));
        } else {

            alert($('#expiredAdsId').data("input-success") + date);

            $.post($('#expiredAdsId').data('url'),
                {
                    date: date
                },
                function (json) {
                    var notifyContainer = notify.getOption('container');
                    notify.remove();
                    notify.setOption('container', '.notify-wrapper');
                    if (json.result === 'success') {
                        notify.addSuccess(json.count + $('#expiredAdsId').data("msg-success"));
                    }
                    notify.show();
                    notify.setOption('container', notifyContainer);
                }, 'json');
        }

    });


    /**
     *Listing Page
     */
    $(document).on('change','#location-country_id',function (e) {
        e.preventDefault();
        $.post($('#listings-select-zones-wrapper').data('url'),{country_id:$(this).val()},function (json) {
            $('#listings-select-zones-wrapper select').empty();
            $.each(json.response,function(key,value){
                $('#listings-select-zones-wrapper select').append($('<option class="gen-op">').text(value.name).attr('value', value.zone_id));
            });

        }, 'json');
    });

    $("#input-gallery").fileinput({
        deleteUrl: false,
        uploadUrl: false,
        allowedFileTypes: ["image"],
        showUpload: false,
        browseClass: 'btn btn-as',
        removeClass: 'btn btn-as reverse',
        uploadClass: 'btn btn-as reverse',
        layoutTemplates: {
            footer: '<div class="file-thumbnail-footer">\n' +
            '    {progress} {actions}\n' +
            '</div>'
        },
        fileActionSettings: {
            showUpload: false,
            showDrag: true,
        }
    });
    // Krjee upload automated
    $('#listingimage-imagesgallery').on('filebatchselected', function() {
        $('#listingimage-imagesgallery').fileinput("upload");
    });

    $('.file-loading').on('filesorted', function(event, params) {
        $.post($('#listingimage-imagesgallery').data('sort-listing-images'),{images:JSON.stringify(params.stack)},function (json) {
        }, 'json');
    });

    $('#listing-category_id').on('change', function (e) {
        e.preventDefault();
        $('#category-fields').css('opacity', '0.4');
        var category_id = $("#listing-category_id option:selected").val();
        $('#choose-class').text($(this).data('selectedText'));
        $('input#listing-category_id').val($(this).data('selectedId'));

        $.post($('#category-fields').data('url'),{
            category_id:category_id,
            listing_id:$('#listing-category_id').data('listing-id'),
        },function (json) {
            $(document).ajaxComplete(function() {
                $('#category-fields').css('opacity', '1');
            });
            if(json.html) {
                $('.category-fields').show();
                $('#category-fields').html(json.html);

            }else{
                $('.category-fields').hide();
            }
        }, 'json');
    });


    //Google Map For Ads

    $('#listing-category_id').trigger('change');

    $('#admin-post-form').on('beforeValidateAttribute', function () {

        if ($('input[name="Location[latitude]"]').val() && $('input[name="Location[longitude]"]').val() ) {

            $('.field-location-latitude > .help-block').hide();
        } else {
            $('.field-location-latitude > .help-block').show();
        }
    });

    $(function(){
        if ($('#disableMap').val() === '0') {
            $(window).load(function(){
                var latitude = $('input[name="Location[latitude]"]').val() ;
                var longitude= $('input[name="Location[longitude]"]').val();
                var zoom = 14;
                var markerUpdate = true;

                initMaps(zoom, latitude, longitude, markerUpdate);

            });
        }
    });

    $(document).on('change','#location-country_id, #location-zone_id',function (e) {

        if ($('#disableMap').val() === '0') {
            var zoom;
            var country;
            var zone;
            var markerUpdate = false;

            if ($(this).attr('id') === 'location-country_id') {
                $('#location-zone_id').val('');
            }

            if ($('#location-country_id').val()) {
                country = $("select#location-country_id option:selected").text();
            } else {
                country = '';
            }

            if ($('#location-zone_id').val()) {
                zone = $("select#location-zone_id option:selected").text();
            } else {
                zone = '';
            }
            if (zone) {zoom = 10} else {zoom = 6}

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': country + ', ' + zone }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    initMaps(zoom, results[0].geometry.location.lat(), results[0].geometry.location.lng(), markerUpdate);
                }
            });
        }

    });

    $(document).on('blur','#location-zip', function (e) {
        if ($('#disableMap').val() === '0') {
            var zip = $(this).val();
            var zoom = 15;
            var markerUpdate = true;
            var country;
            var zone;

            if ($('#location-country_id').val()) {
                country = $("select#location-country_id option:selected").text();
            } else {
                country = '';
            }

            if ($('#location-zone_id').val()) {
                zone = $("select#location-zone_id option:selected").text();
            } else {
                zone = '';
            }

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({'address':country + ', ' + zone + ', ' + zip}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    initMaps(zoom, results[0].geometry.location.lat(), results[0].geometry.location.lng(), markerUpdate);
                }
            });
        }
    });

});

var map;
var marker;
window.initMaps = function(zoom, latitude, longitude, markerUpdate) {
    $('#map-content').css('filter', 'blur(0px)');
    // Enabling new cartography and themes
    google.maps.visualRefresh = true;

    // Setting starting options
    var mapOptions = {
        center:  new google.maps.LatLng(latitude, longitude),
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        panControl: false,
        scaleControl: false,
        streetViewControl: false,
        zoomControl: true,
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
        ],
    };

    // Getting Map DOM Element
    var mapElement = document.getElementById('map-content');

    // Creating a map with DOM element
    map = new google.maps.Map(mapElement, mapOptions);

    //Add marker on init map with input
    marker = new google.maps.Marker({
        position:  new google.maps.LatLng(latitude, longitude),
    });

    if (markerUpdate) {

        marker.setMap(map);

        $('input[name="Location[latitude]"]').val(latitude);
        $('input[name="Location[longitude]"]').val(longitude);
        $('#admin-post-form').trigger('beforeValidateAttribute');


    } else {
        $('input[name="Location[latitude]"]').val('');
        $('input[name="Location[longitude]"]').val('');

    }

    google.maps.event.addListener(map, 'click', function (e) {

        if (marker) {
            marker.setMap(null);
        }

        marker = new google.maps.Marker({
            position:  new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()),
        });

        $('input[name="Location[latitude]"]').val(e.latLng.lat());
        $('input[name="Location[longitude]"]').val(e.latLng.lng());

        $('#admin-post-form').trigger('beforeValidateAttribute');

        marker.setMap(map);

    });
};
