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
use Yii;

/**
 * This command send details of invoice
 *
 * Class SendInvoiceController
 * @package app\commands
 */
class SendInvoiceController extends Controller
{
    /**
     * This command send details of invoice
     */
    public function actionIndex($invoiceId)
    {
        if (options()->get('app.settings.invoice.disableInvoices', 0) == 1) {
            return false;
        }
        app()->sendInvoice->send($invoiceId);
    }

}
