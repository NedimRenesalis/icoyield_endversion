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

namespace app\components;

use yii\base\Component;
use app\models\Invoice;
use kartik\mpdf\Pdf;
use yii\web\NotFoundHttpException;

class SendInvoiceComponent extends Component
{
    /**
     * Send invoice by email
     *
     * @param      $invoiceId
     * @param null $recipientEmail
     * @return bool
     * @throws NotFoundHttpException
     */
    public function send($invoiceId, $recipientEmail = null)
    {
        $invoice = $this->findInvoice($invoiceId);
        $invoicePdf = app()->generateInvoicePdf->generate($invoiceId, Pdf::DEST_STRING);

        return (bool)app()->mailSystem->add('customer-invoice', [
            'invoiceId' => $invoiceId,
            'attachContent' => [
                'file' => $invoicePdf,
                'fileMeta' => [
                    'fileName' => $invoice->getReference() . '.pdf',
                    'contentType' => 'application/pdf'
                ]
            ]
        ]);
    }

    /**
     * Find invoice by pk
     *
     * @param $id
     * @return Invoice
     * @throws NotFoundHttpException if model not found
     */
    protected function findInvoice($id)
    {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(t('app', 'The requested invoice does not exist.'));
    }
}