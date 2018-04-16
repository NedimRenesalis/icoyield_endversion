$(document).ready(function () {
    $(document).on('change','#payment_method_manual', function(){
        $('#payment-details-block > div').hide();
        $('#manual-form-wrapper').show();
    });
});