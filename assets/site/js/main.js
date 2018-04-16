
jQuery(document).ready(function($) {

    // header

    function scrollHeader() {
        var headerHeight = $('.header-wrapper').height();
        $('#header').css({minHeight : headerHeight});
        $(window).scrollTop() > 0 ? $('.header-wrapper').addClass('sticky') : $('.header-wrapper').removeClass('sticky');
    }

    $(window).scroll(function() {
        scrollHeader();
    });

    $("div.alert").on("click", "button.close", function() {
        $(this).parent().hide();
        $(window).scroll();
    });


    // FOOTER BOTTOM **********************************************************************************************************************************

    function bottomFooter() {
        var headerHeight = $('#header').outerHeight(),
            footerHeight = $('#footer').outerHeight(),
            fooMargins   = $(window).width() > 767 ? 80 : 0;
        bodyHeight   = $(window).height() - (headerHeight+footerHeight) - fooMargins;

        $('#content').css({minHeight: bodyHeight});
    };
    bottomFooter();
    $(window).on('resize', bottomFooter);


    // ClEAR PLACEHOLDER ******************************************************************************************************************************

    $('input, textarea').clearPlaceholder();



    // POPOVER **************************************************************************************************************************************
    $('.btn-hint').popover({
        template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
    });

    $('.no-category-selected').popover({
        template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
    });

    $('.form-control').popover({
        template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
    });

    $('.select2-popover').popover({
        template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
    });

    $('label').popover({
        template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
    });

    $(document).ajaxComplete(function() {
        $('.btn-hint').popover({
            template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
        });

        $('.no-category-selected').popover({
            template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
        });

        $('.form-control').popover({
            template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
        });

        $('.select2-popover').popover({
            template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
        });

        $('label').popover({
            template: '<div class="popover ea"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"><p></p></div></div>'
        });
    });

    $('select').select2({
        width: '100%',
        language: site.language,
        dir: site.dir,
        containerCssClass: "select2-visible"
    });

    // MODAL  *****************************************************************************************************************************************
    function centerModals(){
        $('.modal').each(function(i){
            var $clone = $(this).clone().css('display', 'block').appendTo('body');
            var top = Math.round(($clone.height() - $clone.find('.modal-content').height()) / 2);
            top = top > 0 ? top : 0;
            $clone.remove();
            $(this).find('.modal-content').css("margin-top", top);
        });
    }
    $('.modal').on('show.bs.modal', centerModals);
    $(window).on('resize', centerModals);

    $(document).on('shown.bs.tab', centerModals);




    $('a.categ-item').click(function (e) {
        if (this.getAttribute("href").charAt(0) === "#") {
            e.preventDefault();
            var id = $(this).attr('href');

            $('.categ-item:not([href="' + id + '"])').removeClass('active');
            $('.subcategories-box :not(' + id + ')').removeClass('active');

            $(this).toggleClass('active');
            $('.subcategories-box ' + id).toggleClass('active');
        }
    });



    // SCROLL *****************************************************************************************************************************************

    $('#modal-search-category .category-items').mCustomScrollbar({
        scrollInertia: 0,
        scrollEasing: "liniar"
    });

    $('#modal-search-category .column-subcategory').mCustomScrollbar({
        axis:"x",
        mouseWheel:false,
        advanced: {
            autoExpandHorizontalScroll:true,
            updateOnContentResize: true,
            updateOnSelectorChange: true,
        },
        scrollInertia: 0,
        scrollEasing: "liniar"
    });

    // Category Modal in searches
    $(document).on('click','#modal-search-category .primary-category li a',function (e) {
        e.preventDefault();

        $('#modal-search-category .modal-footer #success-selection').hide();
        $('#modal-search-category .modal-footer .no-category-selected').show();
        $('#modal-search-category .modal-footer #close-modal').show();

        var clickedParentId = $(this).data('id');
        var slug = $(this).data('slug');
        $('#modal-search-category .primary-category li a').removeClass('selected').find('span.arrow').remove();
        $(this).addClass('selected').append('<span class="arrow"></span>');

        $('.column-subcategory-wrapper .column-category').hide();
        $(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"] a').removeClass('selected').find('span.arrow').remove();
        $(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"]').show();

        $('#modal-search-category .modal-footer #success-selection').show().data({
            'slug':slug,
            'selectedId':clickedParentId,
            'selectedText':$(this).text()
        });
        $('#modal-search-category .modal-footer .no-category-selected').hide();

        if($(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"]').length == 0 ){
            $('#modal-search-category .primary-category li a span.arrow').remove();
        }

    });

    $(document).on('click','#modal-search-category .column-subcategory-wrapper .column-category li a',function (e) {
        e.preventDefault();

        $('#modal-search-category .modal-footer #success-selection').hide();
        $('#modal-search-category .modal-footer .no-category-selected').show();
        $('#modal-search-category .modal-footer #close-modal').show();
        var parent = $(this).closest('.column-category');
        var found = false;
        $('.column-subcategory-wrapper .column-category').each(function () {
            if(parent.index() == $(this).index()-1){
                found = true;
            }
            if(found){
                $(this).hide().find('li a').removeClass('selected').find('span.arrow').remove();
            }
        })

        var clickedParentId = $(this).data('id');
        var slug = $(this).data('slug');
        $(this).closest('.column-category').find('a').removeClass('selected').find('span.arrow').remove();
        $(this).addClass('selected').append('<span class="arrow"></span>');
        $(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"] a').removeClass('selected').find('span.arrow').remove();;
        $(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"]').show();
        $('.column-subcategory').mCustomScrollbar('scrollTo','-=250',{
            timeout:150,
            scrollInertia:1500,
            scrollEasing: "liniar",
        });

        $('#modal-search-category .modal-footer #success-selection').show().data({
            'slug':slug,
            'selectedId':clickedParentId,
            'selectedText':$(this).text()
        });
        $('#modal-search-category .modal-footer .no-category-selected').hide();

        if($(this).parents('.choose-category').find('.column-category[data-parent="'+clickedParentId+'"]').length == 0 ){
            $(this).find('span.arrow').remove();
        }
    });

    $(document).on('click','#modal-search-category .modal-footer #success-selection',function (e) {
        e.preventDefault();
        $('#choose-class').text($(this).data('selectedText'));
        $('input#listingsearch-categoryslug').val($(this).data('selectedId'));

        var form = $('form.searchArea');
        if ($('#modal-body-map').hasClass('modal-body')) {
            form.attr("action", $('#modal-body-map').data('url-search') + '/' + $(this).data('slug'));
        } else {
            form.attr("action", $('.modal-body').data('url-search') + '/' + $(this).data('slug'));
        }
        form.submit();
    });

    // Mobile
    $('.choose-catg-m-search, #modal-search-category-mobile .close-categ-m, #modal-search-category-mobile .close-x-categ-m').on('click', function () {
        $('#modal-search-category-mobile .subcateg-m').hide();
        $('#modal-search-category-mobile .maincateg-m').show();
        $('.choose-category-mobile').toggleClass('opened');
        return false;
    });

    $('#modal-search-category-mobile .categ-item-m, #modal-search-category-mobile .categ-subitem-m').on('click', function () {
        $('#choose-class-m-search').text($(this).text());
        var $divSubcateg= $(this).data('subcateg');

        // parent submit
        if($(this).parent('li.parent-option').length){
            $('#modal-search-category-mobile .close-x-categ-m').click();
            var form = $('form.searchArea');
            if ($('#modal-body-map').hasClass('modal-body')) {
                form.attr("action", $('#modal-body-map').data('url-search') + '/' + $(this).data('slug'));
            } else {
                form.attr("action", $('.modal-body').data('url-search') + '/' + $(this).data('slug'));
            }
            form.submit();
            return false;
        }

        // if has childs
        if($divSubcateg != '') {
            $('#modal-search-category-mobile .maincateg-m, #modal-search-category-mobile .subcateg-m').hide();
            $('#modal-search-category-mobile #subcateg-' + $divSubcateg).show();
        } else {
            //else submit
            $('#modal-search-category-mobile .close-x-categ-m').click();
            var form = $('form.searchArea');
            if ($('#modal-body-map').hasClass('modal-body')) {
                form.attr("action", $('#modal-body-map').data('url-search') + '/' + $(this).data('slug'));
            } else {
                form.attr("action", $('.modal-body').data('url-search') + '/' + $(this).data('slug'));
            }
            form.submit();
        }
        return false;
    });

    $('#modal-search-category-mobile .back-categ-m').on('click', function () {
        $('#modal-search-category-mobile .subcateg-m').hide();
        $('#modal-search-category-mobile .maincateg-m').show();
        return false;
    });

    // END Category

    // Search

    // exclude fields from submit if they haven't filled
    $('.search-form').submit(function (e) {
        $(this).find('input, select option:selected').filter(function () {
            return !$.trim(this.value).length;  // get all empty fields
        }).prop('disabled', true);

        $(this).find('input:hidden').filter(function () {
            return this.value == 0;  // disable empty checkboxes
        }).prop('disabled', true);

        $(this).find('#listingsearch-categoryslug').prop('disabled', true);
    });


    // add to favorite
    $(document).on('click', '.favorite-listing', function (e) {
        notify.remove();
        e.preventDefault();
        var $this = $(this);
        $.post($this.data('favorite-url'),{
            listing_id:$this.data('listing-id'),
        },function (json) {
            if(json.result == 'success') {
                notify.addSuccess(json.msg).show();
                if(json.action == 'added'){
                    $('.favorite-listing[data-listing-id="'+$this.data('listing-id')+'"] i').removeClass('fa-heart-o').addClass('fa-heart');
                    $('.favorite-listing[data-listing-id="'+$this.data('listing-id')+'"] span').html($('.favorite-listing').data('remove-msg'));
                    // add to stats
                    $.post(site.statsUrl,{
                        stats_type:'favorite',
                        listing_id:$this.data('listing-id'),
                    },function (json) {
                    }, 'json');
                } else {
                    $('.favorite-listing[data-listing-id="'+$this.data('listing-id')+'"] i').removeClass('fa-heart').addClass('fa-heart-o');
                    $('.favorite-listing[data-listing-id="'+$this.data('listing-id')+'"] span').html($('.favorite-listing').data('add-msg'));
                }
            } else {
                notify.addError(json.msg).show();
            }
        }, 'json');
    });

    // Contact page.
    $('#send-contact-form').on('submit', function() {
        $('#send-contact-button').button('loading');
        setTimeout(function() {
            $('#send-contact-button').button('reset');
        }, 2000);
    });


    // Captcha
    function scaleCaptcha(elementWidth) {
        var reCaptchaWidth = 375;
        var containerWidth = $('.container').width();

        if(reCaptchaWidth > containerWidth) {
            var captchaScale = containerWidth / reCaptchaWidth;
            $('.g-recaptcha').css({
                'transform':'scale('+captchaScale+')'
            });
        }
    }

    $(function() {
        scaleCaptcha();
        $(window).resize( $.throttle(100, scaleCaptcha));
    });



});
