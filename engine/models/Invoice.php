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

namespace app\models;

/**
 * Class Invoice
 * @package app\models
 */
class Invoice extends \app\models\auto\Invoice
{
    /**
     * @inheritdoc
     * @return InvoiceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InvoiceQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id'    => t('app','Invoice ID'),
            'order_id'      => t('app','Order ID'),
            'firstName'     => t('app','First Name'),
            'lastName'      => t('app','Last Name'),
            'company'       => t('app','Company'),
            'created_at'    => t('app','Created At'),
        ];
    }

    /**
     * Invoice reference
     *
     * @return string
     */
    public function getReference()
    {
        $iSettings = new \app\models\options\Invoice();

        return $iSettings->prefix . $this->invoice_id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }
}
