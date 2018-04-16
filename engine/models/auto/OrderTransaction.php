<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%order_transaction}}".
 *
 * @property integer $transaction_id
 * @property integer $order_id
 * @property string $gateway
 * @property string $type
 * @property string $transaction_reference
 * @property string $gateway_response
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Order $order
 */
class OrderTransaction extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_transaction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id'], 'integer'],
            [['gateway_response'], 'string'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['gateway'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['transaction_reference'], 'string', 'max' => 100],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'order_id' => 'Order ID',
            'gateway' => 'Gateway',
            'type' => 'Type',
            'transaction_reference' => 'Transaction Reference',
            'gateway_response' => 'Gateway Response',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderTransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderTransactionQuery(get_called_class());
    }
}
