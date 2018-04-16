<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%conversation}}".
 *
 * @property integer $conversation_id
 * @property string $conversation_uid
 * @property integer $seller_id
 * @property integer $buyer_id
 * @property integer $listing_id
 * @property integer $is_buyer_blocked
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $buyer
 * @property Listing $listing
 * @property Customer $seller
 * @property ConversationMessage[] $conversationMessages
 */
class Conversation extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%conversation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seller_id', 'buyer_id', 'listing_id', 'created_at', 'updated_at'], 'required'],
            [['seller_id', 'buyer_id', 'listing_id', 'is_buyer_blocked'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['conversation_uid'], 'string', 'max' => 13],
            [['status'], 'string', 'max' => 15],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['buyer_id' => 'customer_id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['seller_id' => 'customer_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'conversation_id' => 'Conversation ID',
            'conversation_uid' => 'Conversation Uid',
            'seller_id' => 'Seller ID',
            'buyer_id' => 'Buyer ID',
            'listing_id' => 'Listing ID',
            'is_buyer_blocked' => 'Is Buyer Blocked',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'buyer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'seller_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMessages()
    {
        return $this->hasMany(ConversationMessage::className(), ['conversation_id' => 'conversation_id']);
    }

    /**
     * @inheritdoc
     * @return ConversationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConversationQuery(get_called_class());
    }
}
