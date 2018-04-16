$(document).ready(function () {
    if ( $.isFunction($.fn.payment) ) {
        $('#twocheckout-form-wrapper input#TwocheckoutCardNumber').payment('formatCardNumber');
        $('#twocheckout-form-wrapper input#TwocheckoutCvc').payment('formatCardCVC');
    }

    $(document).on('change','#payment_method_twocheckout', function(){
        $('#payment-details-block > div').hide();
        $('#twocheckout-form-wrapper').show();
    });
});


// Called when token created successfully.
var successCallback = function(data) {
    var myForm = document.getElementById('package-form');
    myForm.token.value = data.response.token.token;
    myForm.submit();
};

// Called when token creation fails.
var errorCallback = function(data) {
    var myForm = document.getElementById('package-form');
    if (data.errorCode === 200) {
        tokenRequest();
    } else {
        myForm.submit();
    }
};

var tokenRequest = function() {
    // Setup token request arguments
    var args = {
        sellerId: $('#twocheckout-form-wrapper').data('sid'),
        publishableKey: $('#twocheckout-form-wrapper').data('key'),
        ccNo: $("#TwocheckoutCardNumber").val(),
        cvv: $("#TwocheckoutCvc").val(),
        expMonth: $("#TwocheckoutExpireMonth").val(),
        expYear: $("#TwocheckoutExpireYear").val()
    };

    // Make the token request
    TCO.requestToken(successCallback, errorCallback, args);
};

$(function() {
    TCO.loadPubKey($('#twocheckout-form-wrapper').data('mode'));

    $("#package-form").submit(function(e) {
        if($('#twocheckout-form-wrapper').is(':visible')) {
            var t = tokenRequest();
            return false;
        }
    });
});