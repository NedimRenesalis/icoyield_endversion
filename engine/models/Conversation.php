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

use app\helpers\StringHelper;

/**
 * Class Conversation
 * @package app\models
 */
class Conversation extends \app\models\auto\Conversation
{
    const CONVERSATION_STATUS_ACTIVE = 'active';
    const CONVERSATION_STATUS_ARCHIVED = 'archived';

    public function rules()
    {
        return [
            [['seller_id', 'buyer_id', 'listing_id'], 'required'],
            [['seller_id', 'buyer_id', 'listing_id', 'is_buyer_blocked'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 15],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['buyer_id' => 'customer_id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['seller_id' => 'customer_id']],
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->conversation_uid = $this->generateUid();
        }

        return true;
    }

    /**
     * @param $conversation_uid
     * @return auto\ConversationMessage|array|null
     */
    public function findByUid($conversation_uid)
    {
        return $this->find()->where(array(
            'conversation_uid' => $conversation_uid,
        ))->one();
    }

    /**
     * @return string
     */
    public function generateUid()
    {
        $unique = StringHelper::uniqid();
        $exists = $this->findByUid($unique);

        if (!empty($exists)) {
            return $this->generateUid();
        }

        return $unique;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->conversation_uid;
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
     * Check whether the customer is seller
     *
     * @param int $customerId
     *
     * @return bool
     */
    public function isCustomerSeller($customerId)
    {
        return $customerId == $this->seller_id;
    }

    /**
     * Check whether the conversation is archived
     *
     * @return bool
     */
    public function isArchived()
    {
        return self::CONVERSATION_STATUS_ARCHIVED == $this->status;
    }

    /**
     * Check whether the buyer is blocked
     *
     * @return bool
     */
    public function isBuyerBlocked()
    {
        return self::YES == $this->is_buyer_blocked;
    }

    /**
     * Return list of available statuses of conversation
     *
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::CONVERSATION_STATUS_ACTIVE   => self::CONVERSATION_STATUS_ACTIVE,
            self::CONVERSATION_STATUS_ARCHIVED => self::CONVERSATION_STATUS_ARCHIVED
        ];
    }

    /**
     * Return list of participants of conversation
     *
     * @return array
     */
    public function getParticipants()
    {
        return [
            $this->seller->id => $this->seller->fullName,
            $this->buyer->id  => $this->buyer->fullName
        ];
    }
}
