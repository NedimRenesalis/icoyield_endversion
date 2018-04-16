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

namespace app\commands;

use yii\console\Controller;
use app\models\Order;
use app\models\Invoice;

/**
 * This command generate invoices for orders with status 'paid'
 *
 * Class GenerateInvoicesController
 * @package app\commands
 */
class GenerateInvoicesController extends Controller
{
    /**
     * This command generate invoices for orders with status 'paid'
     * that haven't generated before
     */
    public function actionIndex()
    {
        $orders = Order::find()->paid()->joinWith([
            'invoice' => function (\yii\db\ActiveQuery $query) {
                $query->andWhere(['invoice_id' => null]);
            }
        ], false)->all();

        foreach ($orders as $order) {
            $invoice = new Invoice();
            $invoice->order_id = $order->order_id;

            if ($invoice->save(false)) {
                app()->consoleRunner->run('send-invoice/index ' . $invoice->invoice_id);
            }
        }
    }

}
