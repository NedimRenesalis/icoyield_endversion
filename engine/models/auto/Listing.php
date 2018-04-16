<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%listing}}".
 *
 * @property integer $listing_id
 * @property integer $package_id
 * @property integer $customer_id
 * @property integer $location_id
 * @property integer $category_id
 * @property integer $currency_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $price
 * @property string $negotiable
 * @property string $hide_phone
 * @property string $hide_email
 * @property integer $remaining_auto_renewal
 * @property string $promo_expire_at
 * @property string $listing_expire_at
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CategoryFieldValue[] $categoryFieldValues
 * @property Conversation[] $conversations
 * @property Category $category
 * @property Currency $currency
 * @property Customer $customer
 * @property Location $location
 * @property ListingPackage $package
 * @property ListingFavorite[] $listingFavorites
 * @property ListingImage[] $listingImages
 * @property ListingStat $listingStat
 * @property Order[] $orders
 */
class Listing extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['package_id', 'customer_id', 'location_id', 'category_id', 'currency_id', 'price', 'remaining_auto_renewal'], 'integer'],
            [['customer_id', 'location_id', 'category_id', 'currency_id', 'title', 'slug', 'description', 'price', 'promo_expire_at', 'created_at', 'updated_at'], 'required'],
            [['description'], 'string'],
            [['promo_expire_at', 'listing_expire_at', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['slug'], 'string', 'max' => 110],
            [['negotiable', 'hide_phone', 'hide_email'], 'string', 'max' => 3],
            [['status'], 'string', 'max' => 20],
            [['slug'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['currency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['currency_id' => 'currency_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'location_id']],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => ListingPackage::className(), 'targetAttribute' => ['package_id' => 'package_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'listing_id' => 'Listing ID',
            'package_id' => 'Package ID',
            'customer_id' => 'Customer ID',
            'location_id' => 'Location ID',
            'category_id' => 'Category ID',
            'currency_id' => 'Currency ID',
            'title' => 'Title',
            'slug' => 'Slug',
            'description' => 'Description',
            'price' => 'Price',
            'negotiable' => 'Negotiable',
            'hide_phone' => 'Hide Phone',
            'hide_email' => 'Hide Email',
            'remaining_auto_renewal' => 'Remaining Auto Renewal',
            'promo_expire_at' => 'Promo Expire At',
            'listing_expire_at' => 'Listing Expire At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryFieldValues()
    {
        return $this->hasMany(CategoryFieldValue::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConversations()
    {
        return $this->hasMany(Conversation::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['location_id' => 'location_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(ListingPackage::className(), ['package_id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListingFavorites()
    {
        return $this->hasMany(ListingFavorite::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListingImages()
    {
        return $this->hasMany(ListingImage::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListingStat()
    {
        return $this->hasOne(ListingStat::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @inheritdoc
     * @return ListingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingQuery(get_called_class());
    }
}
