jQuery(document).ready(function($){

    // delete field button.
    $(document).on('click', 'a.btn-remove-select-field', function(){
        if ($(this).data('field-id') > 0 && !confirm($(this).data('message'))) {
            return false;
        }
        $(this).closest('.field-row').fadeOut('slow', function() {
            $(this).remove();

            if(!$('.list-fields').find('.field-row').length){
                $('.list-fields .callout.callout-warning').show();
            }
        });
        return false;
    });

    // add
    $('a.btn-add-select-field').on('click', function(){
        $('.list-fields').find('.callout.callout-warning').hide();

        var currentIndex = -1;
        $('.field-row').each(function(){
            if ($(this).data('start-index') > currentIndex) {
                currentIndex = $(this).data('start-index');
            }
        });

        currentIndex++;
        var tpl = $('#field-select-javascript-template').html();
        tpl = tpl.replace(/\{index\}/g, currentIndex);
        $tpl = $(tpl);
        $('.list-fields').append($tpl);

        $tpl.find('.has-help-text').popover();

        return false;
    });

    // add option
    $(document).on('click', '.btn-select-add-option', function(){
        var $fieldRow = $(this).closest('.field-row');
        var currentIndex = -1;
        $('.select-option-row', $fieldRow).each(function(){
            if ($(this).data('start-index') > currentIndex) {
                currentIndex = $(this).data('start-index');
            }
        });
        currentIndex++;

        var parentIndex = parseInt($(this).closest('.field-row').data('start-index'));
        var tpl = $('#field-select-option-javascript-template').html();
        tpl = tpl.replace(/\{optionIndex\}/g, currentIndex);
        tpl = tpl.replace(/\{parentIndex\}/g, parentIndex);
        $tpl = $(tpl);
        $('.select-options-list', $fieldRow).append($tpl);

        $tpl.find('.has-help-text').popover();

        return false;
    });

    // remove option
    $(document).on('click', 'a.btn-remove-select-option-field', function(){
        if ($(this).data('option-id') > 0 && !confirm($(this).data('message'))) {
            return false;
        }
        $(this).closest('.select-option-row').fadeOut('slow', function() {
            $(this).remove();
        });
        return false;
    });
});