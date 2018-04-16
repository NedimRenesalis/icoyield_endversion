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

/**
 * Class ListingFavorite
 * @package app\models
 */
class ListingFavorite extends \app\models\auto\ListingFavorite
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'listing_id'], 'required'],
            [['customer_id', 'listing_id'], 'integer'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
    }
}