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
 * Class OrderTransaction
 * @package app\models
 */
class OrderTransaction extends \app\models\auto\OrderTransaction
{

    const TYPE_AUTH_ONLY = 'auth only';

    const TYPE_CAPTURE = 'capture';


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => 'order_id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id'        => t('app', 'Transaction ID'),
            'order_id'              => t('app', 'Order ID'),
            'transaction_reference' => t('app', 'Transaction Reference'),
            'gateway'               => t('app', 'Gateway'),
            'gateway_response'      => t('app', 'Gateway Response'),
            'type'                  => t('app', 'Type'),
            'created_at'            => t('app', 'Created At'),
            'updated_at'            => t('app', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getTypesList()
    {
        return [
            self::TYPE_AUTH_ONLY   => t('app', 'auth only'),
            self::TYPE_CAPTURE => t('app', 'capture'),
        ];
    }

    /**
     * @param null $order
     * @return OrderTransaction|void
     */
    public static function createNew($order = null)
    {
        if($order == null){
            return;
        }

        $transaction            = new self();
        $transaction->order_id  = $order->order_id;
        $transaction->save(false);
        return $transaction;
    }
}
