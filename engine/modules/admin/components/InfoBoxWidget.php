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

namespace app\modules\admin\components;

use app\models\Listing;
use app\models\ListingFavorite;
use app\models\Customer;
use app\models\Order;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\db\Expression;

/**
 * Class InfoBoxWidget
 * @package app\modules\admin\components
 */
class InfoBoxWidget extends Widget
{
    /**
     * types of available boxes
     */
    const BOX_ORDERS                = 1;
    const BOX_FAVORITES             = 2;
    const BOX_CUSTOMERS             = 3;
    const BOX_TOTAL_ADS             = 4;
    const BOX_FREE_ADS              = 5;
    const BOX_PAID_ADS              = 6;
    const BOX_LIFETIME_SALES        = 7;

    /**
     * @var box type
     */
    public $boxType;
    /**
     * @var string title of list
     */
    public $title;
    /**
     * @var
     */
    public $icon;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->icon === null) {
            $this->icon = 'fa-cog';
        }

        if (!in_array($this->boxType, [
            self::BOX_ORDERS,
            self::BOX_FAVORITES,
            self::BOX_CUSTOMERS,
            self::BOX_TOTAL_ADS,
            self::BOX_FREE_ADS,
            self::BOX_PAID_ADS,
            self::BOX_LIFETIME_SALES
        ])) {
            throw new InvalidConfigException('"' . $this->boxType . '" box type is not allowed.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        if ($this->isAdsBox()) {
            $query = Listing::find()
                ->where(['>', 'listing_expire_at', new Expression('NOW()')])
                ->andWhere(['status' => Listing::STATUS_ACTIVE])
                ->count();
        }elseif ($this->isOrdersBox()) {
            $query = Order::find()->count();
        } elseif ($this->isFavoritesBox()) {
            $query = ListingFavorite::find()->count();
        } elseif ($this->isCustomersBox()) {
            $query = Customer::find()
                ->andWhere(['status' => Customer::STATUS_ACTIVE])
                ->count();
        } elseif ($this->isFreeAdsBox()) {
            $query = Listing::find()
                ->joinWith(['package c1'], true, 'INNER JOIN')
                ->andWhere(['c1.price' => 0])
                ->andWhere(['status' => Listing::STATUS_ACTIVE])
                ->count();
        } elseif ($this->isPaidAdsBox()) {
            $query = Listing::find()
                ->joinWith(['package c1'], true, 'INNER JOIN')
                ->andWhere(['>', 'c1.price', 0])
                ->andWhere(['status' => Listing::STATUS_ACTIVE])
                ->count();
        } elseif ($this->isLifetimeSalesBox()) {
            $query = Order::find()
                ->select([
                    'sales' => new Expression('SUM(total)')
                ])
                ->andWhere(['status' => Order::STATUS_PAID])
                ->all();
            $query = ($query[0]->sales) ? (int)$query[0]->sales : 0;
        }

        return $this->render('info-box/info-box', [
            'data' => $query,
            'title' => $this->title,
            'icon' => $this->icon
        ]);

        return false;
    }

    /**
     * Check whether the box is orders
     *
     * @return bool
     */
    public function isOrdersBox()
    {
        return self::BOX_ORDERS == $this->boxType;
    }

    /**
     * Check whether the box is favorites
     *
     * @return bool
     */
    public function isFavoritesBox()
    {
        return self::BOX_FAVORITES == $this->boxType;
    }

    /**
     * Check whether the box is customers
     *
     * @return bool
     */
    public function isCustomersBox()
    {
        return self::BOX_CUSTOMERS == $this->boxType;
    }

    /**
     * Check whether the box is ads
     *
     * @return bool
     */
    public function isAdsBox()
    {
        return self::BOX_TOTAL_ADS == $this->boxType;
    }

    /**
     * Check whether the box is ads free
     *
     * @return bool
     */
    public function isFreeAdsBox()
    {
        return self::BOX_FREE_ADS == $this->boxType;
    }

    /**
     * Check whether the box is ads paid
     *
     * @return bool
     */
    public function isPaidAdsBox()
    {
        return self::BOX_PAID_ADS == $this->boxType;
    }

    /**
     * Check whether the box is ads lifetime sales
     *
     * @return bool
     */
    public function isLifetimeSalesBox()
    {
        return self::BOX_LIFETIME_SALES == $this->boxType;
    }
}