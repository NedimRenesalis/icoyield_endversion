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

namespace app\extensions\paypal;

use app\models\Order;
use Omnipay\Omnipay;

/**
 * Class Paypal
 * @package app\extensions\paypal
 */
class Paypal extends \app\init\Extension
{

    public $name = 'Paypal';

    public $author = 'CodinBit Development Team';

    public $version = '1.0';

    public $description = 'Paypal payment gateway for promo packages';

    public $type = 'gateway';

    public function run()
    {
        app()->urlManager->addRules(['paypal' => 'paypal/ipn']);

        // register ipn controller
        app()->controllerMap['paypal'] = [
            'class' => 'app\extensions\paypal\controllers\PaypalController'
        ];
        // event init
        app()->on('app.controller.ad.init', function(){
            if(options()->get('app.gateway.paypal.status', 'inactive') != 'active'){
                return;
            }

            app()->on('app.ad.gateways.option', function($event){
                echo app()->view->renderFile('@app/extensions/paypal/views/gateway-frontend-option.php',[
                    'description' => options()->get('app.gateway.paypal.description', 'EasyAds'),
                ]);
            });

            app()->on('app.ad.gateways.form', function($event){
                echo app()->view->renderFile('@app/extensions/paypal/views/gateway-frontend-form.php');
            });

            app()->on('app.controller.ad.package.handlePayment',['app\extensions\paypal\Paypal', 'handlePayment']);
        });

    }

    /**
     * @param $event
     * @throws \Exception
     */
    public static function handlePayment($event)
    {
        if('paypal' != request()->post('paymentGateway')){
            return;
        }
        $cartTotal = $event->params['cartTotal'];

        if(empty($cartTotal)){
            return;
        }

        $price = round($cartTotal,2);
        $currency = options()->get('app.settings.common.siteCurrency', 'usd');

        // Create a gateway for the Paypal Gateway
        $gateway = Omnipay::create('PayPal_Express');

        // Initialise the gateway
        $gateway->setUsername(options()->get('app.gateway.paypal.username', ''));
        $gateway->setPassword(options()->get('app.gateway.paypal.password', ''));
        $gateway->setSignature(options()->get('app.gateway.paypal.signature', ''));
        $gateway->setSellerPaypalAccountId(options()->get('app.gateway.paypal.email', ''));
        $mode = (options()->get('app.gateway.paypal.mode', 'sandbox') == 'sandbox') ? true : false;
        $gateway->setTestMode($mode);

        // Do a purchase transaction on the gateway
        $response = $gateway->purchase(
            array(
                'cancelUrl'     => request()->hostInfo . request()->url,
                'returnUrl'     => url(['paypal/ipn'], true),
                'amount'        => $price,
                'currency'      => $currency,
                'Description'   => 'Purchase Package'
            )
        )->send();

        $paypalResponse = $response->getData();

        $transactionReference = '';
        $error = false;

        if ($paypalResponse['ACK'] == 'Success') {
           $transactionReference = $paypalResponse['TOKEN'];
        } else {
            $error = true;
            if(isset($paypalResponse['L_LONGMESSAGE0'])) {
                throw new \Exception($paypalResponse['L_LONGMESSAGE0']);
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
        $order->status = Order::STATUS_PENDING;
        if(!$order->save(false)){
            throw new \Exception('Something went wrong, please try again later.');
            return;
        }

        //update order transaction data
        $orderTransaction->gateway                  = 'Paypal';
        $orderTransaction->type                     = 'purchase';
        $orderTransaction->transaction_reference    = $transactionReference;
        if(!$orderTransaction->save(false)){
            throw new \Exception('Something went wrong, please try again later.');
            return;
        }

        //go to paypal
        $event->params['error'] = '';
        $redirect = $response->getRedirectUrl();
        $event->params['redirect'] = $redirect;
    }
}