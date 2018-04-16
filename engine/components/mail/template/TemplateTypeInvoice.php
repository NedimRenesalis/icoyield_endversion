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

namespace app\components\mail\template;

use app\models\Invoice;

class TemplateTypeInvoice implements TemplateTypeInterface
{
    const TEMPLATE_TYPE = 2;

    /**
     * @var array list of variables of template
     */
    protected $varsList = [
        'customer_first_name' => 'Customer First Name',
        'customer_last_name'  => 'Customer Last Name',
        'customer_email'      => 'Customer Email',
    ];

    protected $invoiceId;

    public function __construct(array $data)
    {
        if (!empty($data)) {
            $this->invoiceId = $data['invoiceId'];
        }
    }

    public function populate()
    {
        $invoiceModel = Invoice::find()->with('order.customer')->where(['invoice_id' => $this->invoiceId])->one();

        $this->recipient = $invoiceModel->order->customer->email;

        return [
            'customer_first_name' => $invoiceModel->order->customer->first_name,
            'customer_last_name'  => $invoiceModel->order->customer->last_name,
            'customer_email'      => $invoiceModel->order->customer->email,
        ];
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return array
     */
    public function getVarsList()
    {
        return $this->varsList;
    }
}