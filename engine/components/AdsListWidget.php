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

namespace app\components;

use app\models\Listing;
use yii\base\Widget;
use yii\base\InvalidConfigException;
use yii\db\Expression;

class AdsListWidget extends Widget
{
    /**
     * types of available lists
     */
    const LIST_TYPE_PROMOTED = 1;
    const LIST_TYPE_NEW = 2;
    const LIST_TYPE_RELATED = 3;

    /**
     * @var int number of items to retrieve
     */
    public $quantity = 8;

    /**
     * @var int one of constants
     */
    public $listType;

    /**
     * @var string title of list
     */
    public $title;

    /**
     * @var
     */
    public $ad;

    /**
     * @var
     */
    public $col = 3;

    /**
     * @var bool to show empty template
     */
    public $emptyTemplate = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->quantity == 3) {
            $this->col = 4;
        }

        if (!in_array($this->listType, [self::LIST_TYPE_PROMOTED, self::LIST_TYPE_NEW, self::LIST_TYPE_RELATED])) {
            throw new InvalidConfigException('"' . $this->listType . '" list type is not allowed.');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $query = Listing::find()->alias('t')
            ->joinWith(['package c1'], true, 'INNER JOIN')
            ->with(['favorite', 'currency', 'mainImage', 'category', 'location.zone', 'location.country'])
            ->where(['>', 'listing_expire_at', new Expression('NOW()')])
            ->andWhere(['status' => Listing::STATUS_ACTIVE])
            ->limit($this->quantity);

        if ($this->getIsPromotedList()) {
            $query->andWhere(['>', 'promo_expire_at', new Expression('NOW()')])
                ->andWhere(['c1.promo_show_featured_area' => 'yes'])
                ->orderBy(new Expression('rand()'));
        } elseif ($this->getIsNewList()) {
            $query->andWhere('t.promo_expire_at <= t.created_at')
                ->orderBy(['t.promo_expire_at' => SORT_DESC]);
        } elseif ($this->getIsRelatedList()) {
            $query->andWhere(['category_id' => $this->ad->category_id])
                ->orWhere(['location_id' => $this->ad->location_id])
                ->orderBy(new Expression('rand()'));
        }

        // if ad loaded to this lists (eg. ad view)
        if (!empty($this->ad)){
            $query->andWhere(['<>' ,'listing_id' , $this->ad->listing_id]);
        }

        $ads = $query->all();

        if (!empty($ads)) {
            return $this->render('ads-list/ads-list', [
                'ads' => $ads,
                'title' => $this->title,
                'col' => $this->col,
                'isPromoted' => $this->getIsPromotedList()]);
        }

        if ($this->emptyTemplate) {
            return $this->render('ads-list/ads-list-empty', []);
        }

        return false;
    }

    /**
     * Check whether the list is promoted
     *
     * @return bool
     */
    public function getIsPromotedList()
    {
        return self::LIST_TYPE_PROMOTED == $this->listType;
    }

    /**
     * Check whether the list is new
     *
     * @return bool
     */
    public function getIsNewList()
    {
        return self::LIST_TYPE_NEW == $this->listType;
    }

    /**
     * Check whether the list is related
     *
     * @return bool
     */
    public function getIsRelatedList()
    {
        return self::LIST_TYPE_RELATED == $this->listType;
    }
}
