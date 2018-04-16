$(document).ready(function () {
    $(document).on('change','#payment_method_paypal', function(){
        $('#payment-details-block > div').hide();
        $('#paypal-form-wrapper').show();
    });
});