<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%customer}}".
 *
 * @property integer $customer_id
 * @property string $customer_uid
 * @property integer $group_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_reset_key
 * @property string $phone
 * @property string $gender
 * @property string $birthday
 * @property string $avatar
 * @property string $source
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Conversation[] $conversations
 * @property Conversation[] $conversations0
 * @property ConversationMessage[] $conversationMessages
 * @property ConversationMessage[] $conversationMessages0
 * @property CustomerStore[] $customerStores
 * @property Listing[] $listings
 * @property ListingFavorite[] $listingFavorites
 * @property Order[] $orders
 */
class Customer extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id'], 'integer'],
            [['birthday', 'created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at'], 'required'],
            [['customer_uid'], 'string', 'max' => 13],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['password_hash', 'auth_key', 'access_token'], 'string', 'max' => 64],
            [['password_reset_key'], 'string', 'max' => 32],
            [['phone', 'source'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 1],
            [['avatar'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => 'Customer ID',
            'customer_uid' => 'Customer Uid',
            'group_id' => 'Group ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'access_token' => 'Access Token',
            'password_reset_key' => 'Password Reset Key',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'birthday' => 'Birthday',
            'avatar' => 'Avatar',
            'source' => 'Source',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['buyer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations0()
    {
        return $this->hasMany(Conversation::className(), ['seller_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMessages()
    {
        return $this->hasMany(ConversationMessage::className(), ['buyer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversationMessages0()
    {
        return $this->hasMany(ConversationMessage::className(), ['seller_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerStores()
    {
        return $this->hasMany(CustomerStore::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listing::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListingFavorites()
    {
        return $this->hasMany(ListingFavorite::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @inheritdoc
     * @return CustomerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CustomerQuery(get_called_class());
    }
}
