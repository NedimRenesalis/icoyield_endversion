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

namespace app\extensions\twocheckout;

use app\helpers\StringHelper;
use app\models\Order;
use Omnipay\Omnipay;

/**
 * Class Twocheckout
 * @package app\extensions\twocheckout
 */
class Twocheckout extends \app\init\Extension
{

    public $name = '2Checkout';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = '2Checkout payment gateway for promo packages';

    public $type = 'gateway';

    public function run()
    {
        // event init
        app()->on('app.controller.ad.init', function(){
            if(options()->get('app.gateway.twocheckout.status', 'inactive') != 'active'){
                return;
            }

            app()->on('app.ad.gateways.option', function($event){
                echo app()->view->renderFile('@app/extensions/twocheckout/views/gateway-frontend-option.php',[
                    'description' => options()->get('app.gateway.twocheckout.description', ''),
                ]);
            });

            app()->on('app.ad.gateways.form', function($event){
                echo app()->view->renderFile('@app/extensions/twocheckout/views/gateway-frontend-form.php');
            });

            app()->on('app.controller.ad.package.handlePayment',['app\extensions\twocheckout\Twocheckout', 'handlePayment']);
        });

    }

    /**
     * @param $event
     * @throws \Exception
     */
    public static function handlePayment($event)
    {
        if('twocheckout' != request()->post('paymentGateway')){
            return;
        }

        // Data
        $orderTransaction   = $event->params['transaction'];
        $order              = $event->params['order'];
        $cartTotal          = $event->params['cartTotal'];
        $customer           = $event->params['customer'];
        $location           = $event->params['location'];

        if(empty($cartTotal)){
            return;
        }

        $cardData = request()->post('Twocheckout');
        // Check month before submit
        if((int)$cardData['expireYear'] == (int)date('Y') && (int)date('m') > (int)$cardData['expireMonth']){
            throw new \Exception(t('app', 'Invalid Credit Card expire date selected'));
            return;
        }

        $price = round($cartTotal,2);
        $currency = options()->get('app.settings.common.siteCurrency', 'usd');

        // Create a gateway for the Twocheckout Gateway
        $gateway = Omnipay::create('TwoCheckoutPlus_Token');

        // Initialise the gateway
        $mode = (options()->get('app.gateway.twocheckout.mode', 'sandbox') == 'sandbox') ? true : false;
        $gateway->initialize(array(
            'accountNumber'         => options()->get('app.gateway.twocheckout.accountId', ''),
            'privateKey'            => options()->get('app.gateway.twocheckout.privateKey', ''),
            'testMode'              => $mode,
        ));

        $token = request()->post('token');
        // Do a purchase transaction on the gateway
        $response = $gateway->purchase(
            array(
                'currency'              => $currency,
                'transactionId'         => StringHelper::random(40),
                'token'                 => $token,
                'amount'                => $price,
                'card' => array(
                    'billingName'        => $customer->getFullName(),
                    'billingAddress1'    => $location->city,
                    'billingAddress2'    => $location->city,
                    'billingCity'        => $location->city,
                    'billingState'       => $location->zone->name,
                    'billingPostcode'    => $location->zip,
                    'billingCountry'     => $location->country->name,
                    'email'              => $customer->email,
                    'billingPhone'       => $customer->phone,
                )
            )
        )->send();

        $gatewayResponse = $response->getData();
        $transactionReference = '';
        $error = false;

        if ($response->isSuccessful()) {
            $transactionReference = $response->getTransactionReference();
        } else {
            $error = true;
            throw new \Exception($response->getMessage());
        }

        if($error == true){
            return;
        }

        //update order status
        $order->status = Order::STATUS_PAID;
        $order->save(false);

        //update order transaction data
        $orderTransaction->gateway                  = '2checkout';
        $orderTransaction->type                     = 'purchase';
        $orderTransaction->transaction_reference    = $transactionReference;
        $orderTransaction->gateway_response         = json_encode($gatewayResponse);
        $orderTransaction->save(false);
        return;
    }
}