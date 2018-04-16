jQuery(document).ready(function($) {

    //##### POST AD

    if ($('.promote-slider .owl-carousel').length > 0) {
        $('.promote-slider .owl-carousel').owlCarousel({
            nav: false,
            items: 1,
            autoWidth: false,
            loop: false,
            margin: 20,
            smartSpeed: 1000,
            mouseDrag: false,
            responsive: {
                768: {
                    items: 2
                },
                1280: {
                    items: 3
                }
            },
            onInitialize: function(e) {

            }
        });
    }

    $("#input-gallery").fileinput({
        language: site.language,
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

    // SCROLL *****************************************************************************************************************************************

    $('.category-items').mCustomScrollbar({
        scrollInertia: 0,
        scrollEasing: "liniar"
    });

    $('.column-subcategory').mCustomScrollbar({
        axis: "x",
        mouseWheel: false,
        advanced: {
            autoExpandHorizontalScroll: true,
            updateOnContentResize: true,
            updateOnSelectorChange: true,
        },
        scrollInertia: 0,
        scrollEasing: "liniar"
    });



    // Category Modal **************
    // Desktop
    $(document).on('click', '.primary-category li a', function(e) {
        e.preventDefault();

        $('#modal-category .modal-footer #success-selection').hide();
        $('#modal-category .modal-footer .no-category-selected').show();
        $('#modal-category .modal-footer #close-modal').show();

        var clickedParentId = $(this).data('id');
        $('.primary-category li a').removeClass('selected').find('span.arrow').remove();
        $(this).addClass('selected').append('<span class="arrow"></span>');

        $('.column-subcategory-wrapper .column-category').hide();
        $(this).parents('.choose-category').find('.column-category[data-parent="' + clickedParentId + '"]').show();

        if ($(this).parents('.choose-category').find('.column-category[data-parent="' + clickedParentId + '"]').length == 0) {
            $('#modal-category .modal-footer #success-selection').show().data({ 'selectedId': clickedParentId, 'selectedText': $(this).text() });
            $('#modal-category .modal-footer .no-category-selected').hide();
            $('#modal-category .modal-footer #close-modal').hide();
            $('.primary-category li a span.arrow').remove();
        }

    });

    $(document).on('click', '.column-subcategory-wrapper .column-category li a', function(e) {
        e.preventDefault();

        $('#modal-category .modal-footer #success-selection').hide();
        $('#modal-category .modal-footer .no-category-selected').show();
        $('#modal-category .modal-footer #close-modal').show();
        var parent = $(this).closest('.column-category');
        var found = false;
        $('.column-subcategory-wrapper .column-category').each(function() {
            if (parent.index() == $(this).index() - 1) {
                found = true;
            }
            if (found) {
                $(this).hide().find('li a').removeClass('selected').find('span.arrow').remove();
            }
        })

        var clickedParentId = $(this).data('id');
        $(this).closest('.column-category').find('a').removeClass('selected').find('span.arrow').remove();
        $(this).addClass('selected').append('<span class="arrow"></span>');
        $(this).parents('.choose-category').find('.column-category[data-parent="' + clickedParentId + '"]').show();
        $('.column-subcategory').mCustomScrollbar('scrollTo', '-=250', {
            timeout: 150,
            scrollInertia: 1500,
            scrollEasing: "liniar",
        });

        if ($(this).parents('.choose-category').find('.column-category[data-parent="' + clickedParentId + '"]').length == 0) {
            $('#modal-category .modal-footer #success-selection').show().data({ 'selectedId': clickedParentId, 'selectedText': $(this).text() });
            $('#modal-category .modal-footer .no-category-selected').hide();
            $('#modal-category .modal-footer #close-modal').hide();
            $(this).find('span.arrow').remove();
        }
    });

    $(document).on('click', '#modal-category .modal-footer #success-selection', function(e) {
        e.preventDefault();
        $('#choose-class').text($(this).data('selectedText'));
        $('input#listing-category_id').val($(this).data('selectedId'));

        $.post($('#category-fields').data('url'), {
            category_id: $(this).data('selectedId'),
            listing_id: $('#post-form').data('listing'),
        }, function(json) {
            if (json.html) {
                $('.category-fields').show();
                $('#category-fields').html(json.html);
                $('select').select2({
                    width: '100%',
                    language: site.language,
                    dir: site.dir,
                });
            } else {
                $('.category-fields').hide();
            }
        }, 'json');
    });

    // Mobile
    $('.choose-catg-m, .close-categ-m, .close-x-categ-m').on('click', function() {
        $('.subcateg-m').hide();
        $('.maincateg-m').show();
        $('.choose-category-mobile').toggleClass('opened');
        return false;
    });

    $('.categ-item-m, .categ-subitem-m').on('click', function() {
        $('#choose-class-m').text($(this).text());
        var $divSubcateg = $(this).data('subcateg');

        // if has childs
        if ($divSubcateg != '') {
            $('.maincateg-m, .subcateg-m').hide();
            $('#subcateg-' + $divSubcateg).show();
        } else {
            //else submit
            var id = $(this).data('id');
            $('input#listing-category_id').val(id);
            $.post($('#category-fields').data('url'), {
                category_id: id,
                listing_id: $('#post-form').data('listing'),
            }, function(json) {
                if (json.html) {
                    $('.category-fields').show();
                    $('#category-fields').html(json.html);
                    $('select').select2({
                        width: '100%',
                        language: site.language,
                        dir: site.dir,
                    });
                } else {
                    $('.category-fields').hide();
                }
            }, 'json');
            $('.close-x-categ-m').click();
        }
        return false;
    });

    $('.back-categ-m').on('click', function() {
        $('.subcateg-m').hide();
        $('.maincateg-m').show();
        return false;
    });

    // END Category

    $(document).on('change', '#location-country_id', function(e) {
        e.preventDefault();

        $.post($('#listing-select-zones-wrapper').data('url'), { country_id: $(this).val() }, function(json) {
            $('#listing-select-zones-wrapper select option.gen-op').remove();
            $.each(json.response, function(key, value) {
                $('#listing-select-zones-wrapper select').append($('<option class="gen-op">').text(value.name).attr('value', value.zone_id));
            });
            $('select').select2({
                width: '100%',
                language: site.language,
                dir: site.dir,
            });
        }, 'json');
    });

    setTimeout(function() {
        if ($('input#listing-category_id').val()) {
            var categoryId = $('input#listing-category_id').val();
            $('div#modal-category a[data-id=' + categoryId + ']').click();
            $('#success-selection').click();
        }

        //autocomplete zone on update
        if ($('select#location-country_id').val()) {
            $.post($('#listing-select-zones-wrapper').data('url'), { country_id: $('select#location-country_id').val() }, function(json) {
                $('#listing-select-zones-wrapper select option.gen-op').remove();
                $.each(json.response, function(key, value) {
                    $('#listing-select-zones-wrapper select').append($('<option class="gen-op">').text(value.name).attr('value', value.zone_id));
                });
                $('select').select2({
                    width: '100%',
                    language: site.language,
                    dir: site.dir,
                });
                $('select#location-zone_id').val($('#listing-select-zones-wrapper').data('zone')).trigger('change.select2');
            }, 'json');
        }
    }, 100);

    // ### Ad Package page
    $(document).on('click', '.post-listing-promote .item', function() {
        var pricePackage = $(this).data('price');
        $('#paymentGateway').attr('required', false);
        $('#payment-block, #payment-details-block').hide();
        if (parseInt(pricePackage) > 0) {
            $('#paymentGateway').attr('required', true);
            $('#payment-block, #payment-details-block').show();
        }

        $.post($(this).data('url'), {
            country_id: $(this).data('country_id'),
            zone_id: $(this).data('zone_id'),
            price: $(this).data('price'),
        }, function(json) {
            if (json.html) {
                $('.summary-wrapper').show();
                $('.summary-wrapper').html(json.html);
            }
        }, 'json');
    });

    $('#package-form').on('submit', function(e) {
        $('body').addClass('please-wait');
    });

    // Krjee upload automated
    $('#listingimage-imagesgallery').on('filebatchselected', function() {
        $('#listingimage-imagesgallery').fileinput("upload");
    });

    $('.file-loading').on('filesorted', function(event, params) {
        $.post($('#listingimage-imagesgallery').data('sort-listing-images'), { images: JSON.stringify(params.stack) }, function(json) {}, 'json');
    });


    // ### Ad view page
    // SCROLL TOP HEADING *****************************************************************************************************************************
    //var eTopAdHeading = $('.listing-heading').offset().top;

    // check if not mobile
    if ($(window).width() >= 768) {
        $(window).scroll(function() {
            var headerHeight = $('.header-wrapper').height();
            // var diff = eTopAdHeading - $(window).scrollTop();
            $(window).scrollTop() > 0 ? $('.listing-heading-wrapper').css({ top: headerHeight + 1 }).addClass('sticky') : $('.listing-heading-wrapper').removeClass('sticky').removeAttr('style');
        });
    }

    $(window).on('load resize', function() {
        var adHeadingHeight = $('.listing-heading-wrapper').height();
        $('.listing-heading').css({ minHeight: adHeadingHeight });
    });

    $('.add-to-favorites').on('click', function() {
        setTimeout(function() {
            $(window).scrollTop($(window).scrollTop() + 1);
        }, 500);
    });

    $('body').on('click', 'button.close', function() {
        $(window).scrollTop($(window).scrollTop() + 1);
    });


    // GALLERY *****************************************************************************************************************************************

    if ($('.small-gallery').length > 0) {
        $('.small-gallery').owlCarousel({
            nav: false,
            items: 1,
            autoWidth: false,
            loop: true,
            margin: 0,
            smartSpeed: 1000,
            mouseDrag: false,
            responsive: {
                900: {
                    items: 1
                }
            }
        });
        $('.gallery-left').on('click', function() {
            $('.owl-carousel').trigger('prev.owl.carousel');
        });
        $('.gallery-right').on('click', function() {
            $('.owl-carousel').trigger('next.owl.carousel');
        });
    }

    /* *** */

    /**
     * Init full gallery
     */
    var fullGalleryInit = function() {
        var $fullGallery = $('.full-gallery');

        if ($fullGallery.length > 0) {
            if (!$fullGallery.hasClass('owl-loaded')) {

                $fullGallery.owlCarousel({
                    items: 1,
                    smartSpeed: 1000
                });

                $('.gallery-left-big').on('click', function() {
                    $('.owl-carousel').trigger('prev.owl.carousel');
                });
                $('.gallery-right-big').on('click', function() {
                    $('.owl-carousel').trigger('next.owl.carousel');
                });

            }
        }
    };

    /**
     * Toggle full gallery options
     */
    $('.big-gallery .x-close, .open-full-gallery img, .img-wrapper .zoom, .thb-wrapper').on('click', function() {
        $('.big-gallery').toggleClass('open');
        $('body').toggleClass('gallery-open');
        fullGalleryInit();
    });

    //Handle keyboard navigation
    $(document.documentElement).keyup(function(event) {
        // handle cursor keys
        if (event.keyCode == 37) {
            $('.gallery-left-big').click();
        } else if (event.keyCode == 39) {
            $('.gallery-right-big').click();
        } else if (event.keyCode === 27) {
            $('.big-gallery.open .x-close').click();
        }
    });

    $(document).on('click', '#listing-send-msg', function(e) {
        e.preventDefault();
        $.post($('#listing-send-msg').data('url'), {
            customer_id: $('#listing-send-msg').data('customer-id'),
        }, function(json) {
            if (json.result == 'success') {
                window.location.href = 'mailto:' + json.response.email;
            }
        }, 'json');
    });

    $(document).on('click', '#listing-show-email', function(e) {
        if ($('#listing-show-email').attr('href') == '#') {
            e.preventDefault();
        }
        $.post($('#listing-show-email').data('url'), {
            customer_id: $('#listing-show-email').data('customer-id'),
        }, function(json) {
            if (json.result == 'success') {
                $('#listing-show-email').html(json.response.email);
                $('#listing-show-email').attr('href', 'mailto:' + json.response.email);
            }
        }, 'json');
    });

    $(document).on('click', '#listing-show-phone', function(e) {
        if ($('#listing-show-phone').attr('href') == '#') {
            e.preventDefault();
        }
        $.post($('#listing-show-phone').data('url'), {
            customer_id: $('#listing-show-phone').data('customer-id'),
        }, function(json) {
            if (json.result == 'success') {
                $('#listing-show-phone').html(json.response.phone);
                $('#listing-show-phone').attr('href', 'tel:' + json.response.phone);
            }
        }, 'json');
    });
    $(document).on('click', '#listing-show-website', function(e) {
        if ($('#listing-show-website').attr('href') == '#') {
            e.preventDefault();
        }
        $.post($('#listing-show-website').data('url'), {
            customer_id: $('#listing-show-website').data('customer-id'),
        }, function(json) {
            if (json.result == 'success') {
                $('#listing-show-website').html(json.response.phone);
                var prefix = 'http://';
                var website = json.response.phone;
                if (website.substr(0, prefix.length) !== prefix) {
                    website = prefix + website;
                }
                $('#listing-show-website').attr('href', website);
            }
        }, 'json');
    });

    $(document).on('click', '.track-stats', function() {
        var $this = $(this);
        $.post(site.statsUrl, {
            stats_type: $this.data('stats-type'),
            listing_id: $this.data('listing-id'),
        }, function(json) {
            if (json.result == 'success') {

            } else {

            }
        }, 'json');
    });

    function scrollToAnchor(dest) {
        var stickyHeaderHeight = $('.header-wrapper').outerHeight();
        if ($(window).width() >= 768) {
            stickyHeaderHeight += $('.listing-heading-wrapper').outerHeight();
        }
        var scrollTo = $(dest).offset().top - stickyHeaderHeight;
        $('html,body').animate({ scrollTop: scrollTo }, 'slow');
    }

    $(document).on('click', '.send-message', function(e) {
        e.preventDefault();

        var dest = $(this).attr('href');
        scrollToAnchor(dest);
    });




    // Google Map For Ads

    $('#post-form').on('beforeValidateAttribute', function() {

        $('.field-location-latitude > .help-block').show();

        if ($('input[name="Location[latitude]"]').val() && $('input[name="Location[longitude]"]').val()) {

            $('.field-location-latitude > .help-block').hide();
            $('.field-location-latitude').removeClass('has-error');
        }
    });

    $(function() {
        if ($('#disableMap').val() === '0') {
            $(window).load(function() {
                var latitude = $('input[name="Location[latitude]"]').val();
                var longitude = $('input[name="Location[longitude]"]').val();
                var zoom;
                var markerUpdate;

                if (latitude && longitude) {
                    //update
                    zoom = 14;
                    markerUpdate = true;
                    initMap(zoom, latitude, longitude, markerUpdate);
                } else {
                    //post
                    zoom = 1;
                    markerUpdate = false;
                    initMap(zoom, 44.429459, 26.046170, markerUpdate);
                }
            });
        }
    });

    $(document).on('change', '#location-country_id, #location-zone_id', function(e) {

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
            if (zone) { zoom = 10 } else { zoom = 6 }

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': country + ', ' + zone }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    initMap(zoom, results[0].geometry.location.lat(), results[0].geometry.location.lng(), markerUpdate);
                }
            });
        }

    });

    $(document).on('blur', '#location-zip', function(e) {
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
            geocoder.geocode({ 'address': country + ', ' + zone + ', ' + zip }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    initMap(zoom, results[0].geometry.location.lat(), results[0].geometry.location.lng(), markerUpdate);
                }
            });
        }
    });

});
var map;
var marker;
window.initMap = function(zoom, latitude, longitude, markerUpdate) {
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
        styles: [{
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

    //Add marker on init map with input
    marker = new google.maps.Marker({
        position: new google.maps.LatLng(latitude, longitude),
    });

    if (markerUpdate) {

        marker.setMap(map);

        // give value to this inputs in case of user insert a zip code
        $('input[name="Location[latitude]"]').val(latitude);
        $('input[name="Location[longitude]"]').val(longitude);
        $('#post-form').trigger('beforeValidateAttribute');


    } else {
        $('input[name="Location[latitude]"]').val('');
        $('input[name="Location[longitude]"]').val('');

    }

    google.maps.event.addListener(map, 'click', function(e) {

        if (marker) {
            marker.setMap(null);
        }

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(e.latLng.lat(), e.latLng.lng()),
        });

        $('input[name="Location[latitude]"]').val(e.latLng.lat());
        $('input[name="Location[longitude]"]').val(e.latLng.lng());

        $('#post-form').trigger('beforeValidateAttribute');

        marker.setMap(map);

    });
};
$(document).on('click', '#listing-show-website-whitepaper', function(e) {
    if ($('#listing-show-website-whitepaper').attr('href') == '#') {
        e.preventDefault();
    }
    $.post($('#listing-show-website-whitepaper').data('url'), {
        customer_id: $('#listing-show-website-whitepaper').data('customer-id'),
    }, function(json) {
        if (json.result == 'success') {
            $('#listing-show-website-whitepaper').html(json.response.email);
            var prefix = 'http://';
            var website = json.response.email;
            if (website.substr(0, prefix.length) !== prefix) {
                website = prefix + website;
            }
            $('#listing-show-website-whitepaper').attr('href', website);
        }
    }, 'json');
});