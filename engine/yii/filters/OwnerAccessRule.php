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

namespace app\yii\filters;

use app\models\Invoice;
use yii\filters\AccessRule;
use yii\web\NotFoundHttpException;

class OwnerAccessRule extends AccessRule
{
    public $allow = true;
    public $roles = ['@'];

    /**
     * Check if customer is owner of invoice
     *
     * @param \yii\base\Action $action
     * @param \yii\web\User    $user
     * @param \yii\web\Request $request
     * @return bool|null
     */
    public function allows($action, $user, $request)
    {
        return !\Yii::$app->customer->isGuest ? ($this->getInvoiceCustomerId($request) == \Yii::$app->customer->identity->id) : false;
    }

    protected function getInvoiceCustomerId($request)
    {
        $invoiceId = $request->get('id');
        $invoice = $this->findInvoice($invoiceId);

        return $invoice->order->customer_id;
    }

    /**
     * Find invoice
     *
     * @param $id
     * @return Invoice
     * @throws NotFoundHttpException
     */
    protected function findInvoice($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(t('app', 'The requested invoice does not exist.'));
    }

}