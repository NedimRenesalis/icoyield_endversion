<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%listing_package}}".
 *
 * @property integer $package_id
 * @property string $title
 * @property integer $price
 * @property integer $listing_days
 * @property integer $promo_days
 * @property string $promo_show_featured_area
 * @property string $promo_show_at_top
 * @property string $promo_sign
 * @property string $recommended_sign
 * @property integer $auto_renewal
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Listing[] $listings
 */
class ListingPackage extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listing_package}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'listing_days', 'promo_days', 'auto_renewal'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['promo_show_featured_area', 'promo_show_at_top', 'promo_sign', 'recommended_sign'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'package_id' => 'Package ID',
            'title' => 'Title',
            'price' => 'Price',
            'listing_days' => 'Listing Days',
            'promo_days' => 'Promo Days',
            'promo_show_featured_area' => 'Promo Show Featured Area',
            'promo_show_at_top' => 'Promo Show At Top',
            'promo_sign' => 'Promo Sign',
            'recommended_sign' => 'Recommended Sign',
            'auto_renewal' => 'Auto Renewal',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listing::className(), ['package_id' => 'package_id']);
    }

    /**
     * @inheritdoc
     * @return ListingPackageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingPackageQuery(get_called_class());
    }
}
