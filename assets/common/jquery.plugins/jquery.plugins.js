
jQuery(document).ready(function($) {

    // Plugin Pure Drawer Custom Trigger
    $.fn.triggerSideMenu = function(options){
        var opts = $.extend({}, {
            direction: 'left', // left, right, top
            triggerEvent: 'click' // click, mouseenter
        }, options);
        return this.each(function(){
            $(this).on(opts.triggerEvent, function(){
                $(".pure-toggle-label[data-toggle-label='"+opts.direction+"']").trigger('click');
            });
        });
    };
    //Usage $(".myClass").triggerSideMenu({direction:"right", triggerEvent : "click"});


    /* ******************************* */


    // Plugin Clear placeholder
    $.fn.clearPlaceholder = function() {
        return this.each(function(){
            $(this).data('holder',$(this).attr('placeholder'));
            $(this).focusin(function(){
                $(this).attr('placeholder', '');
            });
            $(this).focusout(function(){
                $(this).attr('placeholder', $(this).data('holder'));
            });
        });
    };
    //Usage $('input').clearPlaceholder();

});

