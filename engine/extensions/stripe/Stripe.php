<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0
 */

namespace app\extensions\stripe;

use app\models\Order;
use Omnipay\Common\CreditCard;
use Omnipay\Omnipay;

/**
 * Class Stripe
 * @package app\extensions\stripe
 */
class Stripe extends \app\init\Extension
{

    public $name = 'Stripe';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = 'Stripe payment gateway for promo packages';

    public $type = 'gateway';

    public function run()
    {
        app()->on('app.controller.ad.init', function(){
            if(options()->get('app.gateway.stripe.status', 'inactive') != 'active'){
                return;
            }

            app()->on('app.ad.gateways.option', function($event){
                echo app()->view->renderFile('@app/extensions/stripe/views/gateway-frontend-option.php',[
                    'description' => options()->get('app.gateway.stripe.description', ''),
                ]);
            });

            app()->on('app.ad.gateways.form', function($event){
                echo app()->view->renderFile('@app/extensions/stripe/views/gateway-frontend-form.php');
            });

            app()->on('app.controller.ad.package.handlePayment',['app\extensions\stripe\Stripe', 'handlePayment']);
        });

    }

    /**
     * @param $event
     * @throws \Exception
     */
    public static function handlePayment($event){
        if('stripe' != request()->post('paymentGateway')){
            return;
        }

        $cartTotal = $event->params['cartTotal'];

        if(empty($cartTotal)){
            return;
        }

        $cardData = request()->post('Stripe');
        // Check month before submit
        if((int)$cardData['expireYear'] == (int)date('Y') && (int)date('m') > (int)$cardData['expireMonth']){
            throw new \Exception(t('app', 'Invalid Credit Card expire date selected'));
        }

        $price = round($cartTotal,2);
        $currency = options()->get('app.settings.common.siteCurrency', 'usd');

        // Create a gateway for the Stripe Gateway
        $gateway = Omnipay::create('Stripe');

        // Initialise the gateway
        $gateway->initialize(array(
            'apiKey' => options()->get('app.gateway.stripe.secretKey', ''),
        ));

        // Create a credit card object
        $card = new CreditCard(array(
                    'number'                => str_replace(' ', '',$cardData['cardNumber']),
                    'expiryMonth'           => $cardData['expireMonth'],
                    'expiryYear'            => $cardData['expireYear'],
                    'cvv'                   => $cardData['cvc']
        ));

        // Do a purchase transaction on the gateway
        $transaction = $gateway->purchase(array(
            'amount'                   => $price,
            'currency'                 => $currency,
            'description'              => 'Purchase Package',
            'card'                     => $card,
        ));

        $response = $transaction->send();
        $gatewayResponse = $response->getRequest()->getResponse()->getData();

        $transactionReference = '';
        $error = false;

        if ($response->isSuccessful()) {
           $transactionReference = $response->getTransactionReference();
        } else {
            $error = true;
            if(isset($gatewayResponse['error'], $gatewayResponse['error']['message'])) {
                throw new \Exception($gatewayResponse['error']['message']);
            } else {
                throw new \Exception(t('app','Something went wrong, please try again later.'));
            }
        }

        if($error == true){
            return;
        }

        $orderTransaction = $event->params['transaction'];
        $order = $event->params['order'];

        //update order status
        $order->status = Order::STATUS_PAID;
        $order->save(false);

        //update order transaction data
        $orderTransaction->gateway                  = 'Stripe';
        $orderTransaction->type                     = 'purchase';
        $orderTransaction->transaction_reference    = $transactionReference;
        $orderTransaction->gateway_response         = json_encode($gatewayResponse);
        $orderTransaction->save(false);
        return;
    }
}