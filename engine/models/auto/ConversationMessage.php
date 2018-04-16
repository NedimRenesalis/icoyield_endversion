<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%conversation_message}}".
 *
 * @property integer $conversation_message_id
 * @property integer $conversation_id
 * @property integer $seller_id
 * @property integer $buyer_id
 * @property string $message
 * @property integer $is_read
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Customer $buyer
 * @property Conversation $conversation
 * @property Customer $seller
 */
class ConversationMessage extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%conversation_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_id', 'message', 'created_at', 'updated_at'], 'required'],
            [['conversation_id', 'seller_id', 'buyer_id', 'is_read'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['message'], 'string', 'max' => 1000],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['buyer_id' => 'customer_id']],
            [['conversation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conversation::className(), 'targetAttribute' => ['conversation_id' => 'conversation_id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['seller_id' => 'customer_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'conversation_message_id' => 'Conversation Message ID',
            'conversation_id' => 'Conversation ID',
            'seller_id' => 'Seller ID',
            'buyer_id' => 'Buyer ID',
            'message' => 'Message',
            'is_read' => 'Is Read',
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
    public function getConversation()
    {
        return $this->hasOne(Conversation::className(), ['conversation_id' => 'conversation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'seller_id']);
    }

    /**
     * @inheritdoc
     * @return ConversationMessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConversationMessageQuery(get_called_class());
    }
}
