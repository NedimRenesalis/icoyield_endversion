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

class GenerateInvoicePdfComponent extends Component
{
    /**
     * Generate invoice in pdf
     *
     * @param        $invoiceId
     * @param string $destination
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function generate($invoiceId, $destination = Pdf::DEST_BROWSER)
    {
        $invoice = $this->findInvoice($invoiceId);
        $pdf =app()->pdf;

        $pdf->options = [
            'title' => 'Invoice - ' . $invoice->getReference(),
        ];
        // custom css styles
        $pdf->cssInline = '.container {margin: 0px} tbody tr td {background: #e6e6e6; border:none;} thead tr th {border-color:#fff;} tr {border-color: #fff} td, th {text-align: center} td.first, th.first {text-align: left} .heading {border-bottom: 1px solid #000; line-height: 40px} th {text-transform: uppercase;} .summary td {font-weight: bold;}';

        $pdf->content = app()->controller->renderPartial('@app/components/views/invoices/_pdf-view', [
            'invoice'   => $invoice,
            'logo'      => options()->get('app.settings.invoice.logo', false) ? base64_encode(file_get_contents(\Yii::getAlias('@webdir') . options()->get('app.settings.invoice.logo', false))) : false,
            'paidStamp' => base64_encode(file_get_contents(\Yii::getAlias('@webdir') . '/assets/site/img/stamp-paid.png')),
        ]);
        $pdf->filename = $invoice->getReference() . '.pdf';
        $pdf->destination = $destination;

        return $pdf->render();
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
        throw new NotFoundHttpException(t('app', 'The requested page does not exist.'));
    }
}