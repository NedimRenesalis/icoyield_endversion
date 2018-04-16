<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%listing_image}}".
 *
 * @property integer $image_id
 * @property integer $listing_id
 * @property string $image_path
 * @property integer $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Listing $listing
 */
class ListingImage extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listing_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['listing_id', 'sort_order'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['image_path'], 'string', 'max' => 255],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'Image ID',
            'listing_id' => 'Listing ID',
            'image_path' => 'Image Path',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @inheritdoc
     * @return ListingImageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingImageQuery(get_called_class());
    }
}
