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

use yii\helpers\ArrayHelper;


/**
 * Class ConversationMessage
 * @package app\models
 */
class ConversationMessage extends \app\models\auto\ConversationMessage
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['conversation_id', 'message'], 'required'],
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
        return ArrayHelper::merge(parent::attributeLabels(), [
            'message'         => t('app', 'Message'),
        ]);
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
     * Get author of message
     *
     * @return auto\Customer
     */
    public function getAuthor()
    {
        return $this->seller ? $this->seller : $this->buyer;
    }

    /**
     * Check whether the customer is author
     *
     * @param $customerId
     *
     * @return bool
     */
    public function isAuthor($customerId)
    {
        $author = $this->getAuthor();

        return $author->customer_id == $customerId;
    }

    /**
     * Check whether the author is seller
     *
     * @return bool
     */
    public function isAuthorSeller()
    {
        return (bool)$this->seller_id;
    }

    /**
     * Check whether the message is unread
     *
     * @return bool
     */
    public function isUnread()
    {
        return $this->is_read == self::NO;
    }
}
