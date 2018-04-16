<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%listing_stat}}".
 *
 * @property integer $listing_id
 * @property integer $total_views
 * @property integer $facebook_shares
 * @property integer $twitter_shares
 * @property integer $mail_shares
 * @property integer $favorite
 * @property integer $show_phone
 * @property integer $show_mail
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Listing $listing
 */
class ListingStat extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%listing_stat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total_views', 'facebook_shares', 'twitter_shares', 'mail_shares', 'favorite', 'show_phone', 'show_mail'], 'integer'],
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'listing_id' => 'Listing ID',
            'total_views' => 'Total Views',
            'facebook_shares' => 'Facebook Shares',
            'twitter_shares' => 'Twitter Shares',
            'mail_shares' => 'Mail Shares',
            'favorite' => 'Favorite',
            'show_phone' => 'Show Phone',
            'show_mail' => 'Show Mail',
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
     * @return ListingStatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ListingStatQuery(get_called_class());
    }
}
