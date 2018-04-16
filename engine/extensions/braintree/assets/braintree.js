$(document).ready(function () {
    $(document).on('click','#payment_method_braintree', function(){
        $('#payment-details-block > div').hide();
        $('#braintree-form-wrapper').show();
    });

    var form = document.querySelector('form#package-form');
    var submit = document.querySelector('button[type="submit"]');

    braintree.client.create({
        authorization: $('#braintree-form-wrapper').data('token')
    }, function (clientErr, clientInstance) {
            if (clientErr) {
                // Handle error in client creation
                return;
            }
            braintree.hostedFields.create({
                client: clientInstance,
                styles: {
                    'input': {
                        'font-family': 'Open Sans, sans-serif',
                        'color': '#292929',
                        'font-size': '14px',
                        'line-height': '14px',
                        'font-weight' : '300',
                        '-webkit-font-smoothing': 'antialiased',
                    },
                    ':focus': {
                        'color': 'black'
                    },
                    '.valid': {
                    }
                },
                fields: {
                    number: {
                        selector: '#BraintreeCardNumber',
                        formatInput: true,
                        placeholder: '•••• •••• •••• ••••',
                    },
                    cvv: {
                        selector: '#BraintreeCvc',
                        placeholder: '•••'

                    },
                    expirationMonth: {
                        selector: '#BraintreeExpireMonth',
                        placeholder: 'MM'
                    },
                    expirationYear: {
                        selector: '#BraintreeExpireYear',
                        placeholder: 'YY'
                    }
                }
                }, function (hostedFieldsErr, hostedFieldsInstance) {
                    if (hostedFieldsErr) {
                    // Handle error in Hosted Fields creation
                    return;
                    }
                    submit.removeAttribute('disabled');
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();
                        hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                            if (tokenizeErr) {
                                // Handle error in Hosted Fields tokenization
                                return;
                            }
                            document.querySelector('input[name="payment_method_nonce"]').value = payload.nonce;
                            form.submit();
                        });
                    }, false);
                }
            );
        }
    );
});