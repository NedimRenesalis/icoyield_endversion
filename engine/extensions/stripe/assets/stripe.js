$(document).ready(function () {
    if ( $.isFunction($.fn.payment) ) {
        $('#stripe-form-wrapper input[name="Stripe[cardNumber]"]').payment('formatCardNumber');
        $('#stripe-form-wrapper input[name="Stripe[cvc]"]').payment('formatCardCVC');
    }
    $(document).on('change','#payment_method_stripe', function(){
        $('#payment-details-block > div').hide();
        $('#stripe-form-wrapper').show();
    });
});