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

namespace app\extensions\braintree;

use app\helpers\StringHelper;
use app\models\Order;
use Omnipay\Omnipay;

/**
 * Class Braintree
 * @package app\extensions\braintree
 */
class Braintree extends \app\init\Extension
{

    public $name = 'Braintree';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = 'Braintree payment gateway for promo packages';

    public $type = 'gateway';

    public function run()
    {
        // event init
        app()->on('app.controller.ad.init', function(){
            if(options()->get('app.gateway.braintree.status', 'inactive') != 'active'){
                return;
            }

            // check currency match
            if(options()->get('app.settings.common.siteCurrency', 'usd') != options()->get('app.gateway.braintree.merchantCurrency', '')){
                return;
            }

            app()->on('app.ad.gateways.option', function($event){
                echo app()->view->renderFile('@app/extensions/braintree/views/gateway-frontend-option.php',[
                    'description' => options()->get('app.gateway.braintree.description', ''),
                ]);
            });

            app()->on('app.ad.gateways.form', function($event){
                echo app()->view->renderFile('@app/extensions/braintree/views/gateway-frontend-form.php');
            });

            app()->on('app.controller.ad.package.handlePayment',['app\extensions\braintree\Braintree', 'handlePayment']);
        });

    }

    /**
     * @return \Omnipay\Common\GatewayInterface
     */
    protected static function _setup()
    {
        $gateway = Omnipay::create('Braintree');
        $mode = (options()->get('app.gateway.braintree.mode', 'sandbox') == 'sandbox') ? true : false;
        $gateway->initialize(array(
            'merchantId'            => options()->get('app.gateway.braintree.merchantId', ''),
            'publicKey'             => options()->get('app.gateway.braintree.publicKey', ''),
            'privateKey'            => options()->get('app.gateway.braintree.privateKey', ''),
            'testMode'              => $mode,
        ));
        return $gateway;
    }

    /**
     * @param $event
     * @throws \Exception
     */
    public static function handlePayment($event)
    {
        if('braintree' != request()->post('paymentGateway')){
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

        $price = round($cartTotal,2);

        if(options()->get('app.settings.common.siteCurrency', 'usd') != options()->get('app.gateway.braintree.merchantCurrency', '')){
            throw new \Exception(t('app', 'Something went wrong, please try again later.'));
            return;
        }

        $gateway = self::_setup();

        $token = request()->post('payment_method_nonce');

        // Do a purchase transaction on the gateway
        $response = $gateway->purchase(
            array(
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
            throw new \Exception(t('app','Something is wrong with your card details, please correct and try again.'));
        }

        if($error == true){
            return;
        }

        //update order status
        $order->status = Order::STATUS_PAID;
        $order->save(false);

        //update order transaction data
        $orderTransaction->gateway                  = 'Braintree';
        $orderTransaction->type                     = 'purchase';
        $orderTransaction->transaction_reference    = $transactionReference;
        $orderTransaction->gateway_response         = json_encode((array)$gatewayResponse->transaction);
        $orderTransaction->save(false);
        return;
    }

    /**
     * @return mixed
     */
    public static function getToken()
    {
        $gateway = self::_setup();
        return $token = $gateway->clientToken()->send()->getToken();
    }
}