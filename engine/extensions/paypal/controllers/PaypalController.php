<?php

namespace app\extensions\paypal\controllers;

use app\models\Listing;
use app\models\Order;
use Omnipay\Omnipay;
use yii\db\Expression;
use yii\web\Controller;
use app\models\OrderTransaction;

/**
 * Class PaypalController
 * @package app\extensions\paypal\controllers
 */
class PaypalController extends Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * actionIpn Method
     */
    public function actionIpn()
    {
        if (!request()->get()){
            $this->redirect(['listing/index']);
            return;
        }

        if(!request()->get('token')){
            $this->redirect(['listing/index']);
            return;
        }

        $error = false;
        $transaction = db()->beginTransaction();
        try {
            // Create a gateway for the Paypal Gateway
            $gateway = Omnipay::create('PayPal_Express');
            $currency = options()->get('app.settings.common.siteCurrency', 'usd');

            // get order transaction for $_GET
            $orderTransaction = OrderTransaction::find()->where(['transaction_reference' => request()->get('token')])->one();
            if(empty($orderTransaction)){
                throw new \Exception(t('app', 'Something went wrong with finding your transaction, please try again later.'));
            }

            // get order from order transaction
            $order = Order::findOne(['order_id' => $orderTransaction->order_id]);
            $price = round($order->total, 2);

            // Initialise the gateway
            $gateway->setUsername(options()->get('app.gateway.paypal.username', ''));
            $gateway->setPassword(options()->get('app.gateway.paypal.password', ''));
            $gateway->setSignature(options()->get('app.gateway.paypal.signature', ''));
            $gateway->setSellerPaypalAccountId(options()->get('app.gateway.paypal.email', ''));
            $mode = (options()->get('app.gateway.paypal.mode', 'sandbox') == 'sandbox') ? true : false;
            $gateway->setTestMode($mode);

            // check Paypal account id if is the same as the one declared in the backend
            if($gateway->getSellerPaypalAccountId() != options()->get('app.gateway.paypal.email', '')){
                throw new \Exception(t('app', 'Something went wrong, please try again later.'));
            }

            // complete the purchase() action
            $response = $gateway->completePurchase(
                array(
                    'cancelUrl'=> options()->get('app.settings.urls.siteUrl', '') . request()->url,
                    'returnUrl'=> url(['paypal/ipn'], true),
                    'amount' =>  $price,
                    'currency' => $currency,
                )
            )->send();
            $data = $response->getData();

            // save paypal response in order transaction
            $orderTransaction->gateway_response = json_encode($data);
            if(!$orderTransaction->save(false)){
                throw new \Exception(t('app', 'Something went wrong, please try again later.'));
            }

            // change order status after getting paypal response
            $status = ($response->isSuccessful()) ? Order::STATUS_PAID : Order::STATUS_FAILED;
            $order->status = $status;
            if(!$order->save(false)){
                throw new \Exception(t('app', 'Something went wrong, please try again later.'));
            }

            //Check admin approval option to set the right status for ad
            $ad = Listing::findOne(['listing_id' => $order->listing_id]);

            //update ad dates
            $ad->promo_expire_at = null;
            $ad->listing_expire_at = new Expression('NOW() + INTERVAL ' . (int)$ad->package->listing_days . ' DAY');
            if ((int)$ad->package->promo_days > 0) {
                $ad->promo_expire_at = new Expression('NOW() + INTERVAL ' . (int)$ad->package->promo_days . ' DAY');
            }

            $ad->remaining_auto_renewal = 0;
            if ((int)$ad->package->auto_renewal > 0) {
                $ad->remaining_auto_renewal = $ad->package->auto_renewal;
            }

            $adminApproval = options()->get('app.settings.common.adminApprovalAds', 1);
            if($adminApproval > 0){
                $ad->status = Listing::STATUS_PENDING_APPROVAL;
                Listing::sendWaitingApprovalEmail($ad);
            } else {
                Listing::sendAdActivateEmail($ad);
                $ad->status = Listing::STATUS_ACTIVE;
            }
            $ad->save(false);

            // apply transaction db
            $transaction->commit();
        } catch (\Exception $e){
            notify()->addError($e->getTraceAsString());
            $error = true;
            $transaction->rollBack();
            $this->redirect(['account/index']);
        }

        if (!$error) {
            notify()->addSuccess(t('app','Your ad was successfully created and will be active soon'));
            $this->redirect(['account/index']);
        }
    }
}