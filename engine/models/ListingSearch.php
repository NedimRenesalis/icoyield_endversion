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

use app\helpers\LocationHelper;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class ListingSearch
 * @package app\common\models
 */
class ListingSearch extends Listing
{
    public $searchPhrase;
    public $location;
    public $minPrice;
    public $maxPrice;
    public $store;
    public $categorySlug;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'package_id', 'customer_id', 'location_id', 'category_id', 'currency_id', 'status', 'searchPhrase', 'location', 'categorySlug', 'store'], 'safe'],
            [[ 'remaining_auto_renewal' , 'negotiable'], 'integer'],
            [['price', 'minPrice', 'maxPrice'], 'double']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Listing::find()->alias('t');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'       => ['defaultOrder' => ['updated_at' => SORT_DESC]],
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (strlen($this->customer_id) >= 1) {
            $query->joinWith(['customer' => function ($query) {
                $query->from(Customer::tableName() . ' c1');
            }], true, 'INNER JOIN')
                ->andWhere(['like', 'c1.first_name', $this->customer_id])
                ->orWhere(['like', 'c1.last_name', $this->customer_id]);
        }

        if (strlen($this->store) >= 1) {
            $query->joinWith(['store' => function ($query) {
                $query->from(CustomerStore::tableName() . ' c6');
            }], true, 'INNER JOIN')->andWhere(['like', 'c6.store_name', $this->store])->andWhere(['=', 'c6.status', CustomerStore::STATUS_ACTIVE]);
        }

        if (strlen($this->category_id) >= 1) {
            $query->joinWith(['category' => function ($query) {
                $query->from(Category::tableName() . ' c2');
            }], true, 'INNER JOIN')->andWhere(['like', 'c2.name', $this->category_id]);
        }

        if (strlen($this->currency_id) >= 1) {
            $query->joinWith(['currency' => function ($query) {
                $query->from(Currency::tableName() . ' c3');
            }], true, 'INNER JOIN')->andWhere(['like', 'c3.name', $this->currency_id]);
        }

        if (strlen($this->location_id) >= 1) {
            $query->joinWith(['location' => function ($query) {
                $query->from(Location::tableName() . ' c4');
            }], true, 'INNER JOIN')->andWhere(['like', 'c4.city', $this->location_id]);
        }

        if (strlen($this->package_id) >= 1) {
            $query->joinWith(['package' => function ($query) {
                $query->from(ListingPackage::tableName() . ' c5');
            }], true, 'INNER JOIN')->andWhere(['like', 'c5.title', $this->package_id]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 't.title', $this->title])
            ->andFilterWhere(['=', 't.price', $this->price])
            ->andFilterWhere(['=', 't.remaining_auto_renewal', $this->remaining_auto_renewal])
            ->andFilterWhere(['like', 't.created_at', $this->created_at]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function categorySearch($params)
    {
        $query = Listing::find()->alias('t')
            ->with(['mainImage', 'category', 'favorite'])
            ->where(['>', 't.listing_expire_at', new Expression('NOW()')])
            ->andWhere(['t.status' => 'active']);

        $query->joinWith(['currency', 'location.zone', 'location.country']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        // custom fields filters
        if (isset($params['CategoryField'])) {
            $customFieldsFilters = $params['CategoryField'];
            $filterFields = [];
            foreach ($customFieldsFilters as $fieldId => $filters) {
                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        if (!empty($filter)) {
                            $filterFields[$fieldId] = $filters;
                        }
                    }
                } else {
                    if (!empty($filters)) {
                        $filterFields[$fieldId] = $filters;
                    }
                }
            }

            if (!empty($filterFields)) {
                foreach ($filterFields as $fieldId => $filters) {
                    if (is_array($filters)) {
                        $alias = 'cf' . $fieldId;
                        $query->joinWith(['categoryFieldValues ' . $alias], true, 'INNER JOIN')
                            ->andWhere([$alias . '.field_id' => $fieldId]);

                        if (!empty($filters['min'])) {
                            $query->andWhere(['>=', $alias . '.value', $filters['min']]);
                        }
                        if (!empty($filters['max'])) {
                            $query->andWhere(['<=', $alias . '.value', $filters['max']]);
                        }
                    } else {
                        $alias = 'cf' . $fieldId;
                        $query->joinWith(['categoryFieldValues ' . $alias], true, 'INNER JOIN')
                            ->andWhere([$alias . '.field_id' => $fieldId])
                            ->andWhere([$alias . '.value' => $filters]);
                    }
                }
            }
        }

        // search block for location: city, zone or country
        if ($this->location) {
            if(strpos($this->location, 'zo-') === 0) $query->andFilterWhere(['=', '{{%zone}}.zone_id', substr($this->location, 3)]);
            else if(strpos($this->location, 'co-') === 0) $query->andFilterWhere(['=', '{{%country}}.country_id', substr($this->location, 3)]);
            else $query->andFilterWhere(['LIKE', '{{%location}}.city', substr($this->location, 3)]);
        }

        if (trim($this->searchPhrase) != '') {
            $query->andWhere('(t.title LIKE :searchPhrase OR t.description LIKE :searchPhrase)', [
                ':searchPhrase' => '%'.$this->searchPhrase.'%'
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere(['=', 't.remaining_auto_renewal', $this->remaining_auto_renewal])
            ->andFilterWhere(['=', 't.negotiable', $this->negotiable])
            ->andFilterWhere(['=', '{{%currency}}.currency_id', $this->currency_id])
            ->andFilterWhere(['>=', 't.price', $this->minPrice])
            ->andFilterWhere(['<=', 't.price', $this->maxPrice]);

        return $dataProvider;
    }

    /**
     * @param $params
     * @return array
     */
    public function mapSearch($params)
    {
        $query = \app\models\Listing::find()->alias('t')
            ->where(['>', 't.listing_expire_at', new Expression('NOW()')])
            ->andWhere(['t.status' => 'active']);

        $query->joinWith(['currency','location.zone', 'location.country']);

        if(strpos($params['location'], 'zo-') === 0) $query->andFilterWhere(['=', '{{%zone}}.zone_id', substr($params['location'], 3)]);
        else if(strpos($params['location'], 'co-') === 0) $query->andFilterWhere(['=', '{{%country}}.country_id', substr($params['location'], 3)]);
        else $query->andFilterWhere(['LIKE', '{{%location}}.city', substr($params['location'], 3)]);

        if (isset($params['searchPhrase']) && trim($params['searchPhrase']) != '') {
            $query->andWhere('(t.title LIKE :searchPhrase OR t.description LIKE :searchPhrase)', [
                ':searchPhrase' => '%'.$params['searchPhrase'].'%'
            ]);
        }

        if (isset($params['remaining_auto_renewal'])) { $query->andFilterWhere(['=', 't.remaining_auto_renewal', $params['remaining_auto_renewal']]); };
        if (isset($params['negotiable'])) { $query->andFilterWhere(['=', 't.negotiable', $params['negotiable']]); };
        if (isset($params['currency_id'])) { $query ->andFilterWhere(['=', '{{%currency}}.currency_id', $params['currency_id']]); };
        if (isset($params['minPrice'])) { $query->andFilterWhere(['>=', 't.price', $params['minPrice']]); };
        if (isset($params['maxPrice'])) { $query ->andFilterWhere(['<=', 't.price', $params['maxPrice']]); };

        $listing = $query->all();

        $adsDetails = [];
        foreach ($listing as $key => $ad) {
            $location                       = $ad->getLocation()->one();
            $image                          = $ad->getMainImage()->one();
            $adsDetails[$key]['title']      = $ad->title;
            $adsDetails[$key]['lat']        = (float)$location->latitude;
            $adsDetails[$key]['lng']        = (float)$location->longitude;
            $adsDetails[$key]['img']        = $image->image;
            $adsDetails[$key]['slug']       = $ad->slug;
            $adsDetails[$key]['price']      = $ad->getPriceAsCurrency($ad->currency->code);

        }
        return $adsDetails;
    }

    /**
     * @param $params
     * @return array
     */
    public function mapCategorySearch($params, $categories)
    {
        $query = Listing::find()->alias('t')
            ->with(['mainImage', 'category', 'favorite'])
            ->where(['>', 't.listing_expire_at', new Expression('NOW()')])
            ->where(['category_id' => $categories])
            ->andWhere(['t.status' => 'active']);
        $query->joinWith(['currency', 'location.zone', 'location.country']);

        // custom fields filters
        if (isset($params['CategoryField'])) {
            $customFieldsFilters = $params['CategoryField'];
            $filterFields = [];
            foreach ($customFieldsFilters as $fieldId => $filters) {
                if (is_array($filters)) {
                    foreach ($filters as $filter) {
                        if (!empty($filter)) {
                            $filterFields[$fieldId] = $filters;
                        }
                    }
                } else {
                    if (!empty($filters)) {
                        $filterFields[$fieldId] = $filters;
                    }
                }
            }

            if (!empty($filterFields)) {
                foreach ($filterFields as $fieldId => $filters) {
                    if (is_array($filters)) {
                        $alias = 'cf' . $fieldId;
                        $query->joinWith(['categoryFieldValues ' . $alias], true, 'INNER JOIN')
                            ->andWhere([$alias . '.field_id' => $fieldId]);

                        if (!empty($filters['min'])) {
                            $query->andWhere(['>=', $alias . '.value', $filters['min']]);
                        }
                        if (!empty($filters['max'])) {
                            $query->andWhere(['<=', $alias . '.value', $filters['max']]);
                        }
                    } else {
                        $alias = 'cf' . $fieldId;
                        $query->joinWith(['categoryFieldValues ' . $alias], true, 'INNER JOIN')
                            ->andWhere([$alias . '.field_id' => $fieldId])
                            ->andWhere([$alias . '.value' => $filters]);
                    }
                }
            }
        }

        // search block for location: city, zone or country
        if (isset($params['ListingSearch'])) {
            if(strpos($params['ListingSearch']['location'], 'zo-') === 0) $query->andFilterWhere(['=', '{{%zone}}.zone_id', substr($params['ListingSearch']['location'], 3)]);
            else if(strpos($params['ListingSearch']['location'], 'co-') === 0) $query->andFilterWhere(['=', '{{%country}}.country_id', substr($params['ListingSearch']['location'], 3)]);
            else $query->andFilterWhere(['LIKE', '{{%location}}.city', substr($params['ListingSearch']['location'], 3)]);
        }

        if (isset($params['ListingSearch']['searchPhrase'])) {
            if (trim($params['ListingSearch']['searchPhrase']) != '') {
                $query->andWhere('(t.title LIKE :searchPhrase OR t.description LIKE :searchPhrase)', [
                    ':searchPhrase' => '%'.$params['ListingSearch']['searchPhrase'].'%'
                ]);
            }
        }

        // grid filtering conditions
        if (isset($params['ListingSearch']['remaining_auto_renewal'])) { $query->andFilterWhere(['=', 't.remaining_auto_renewal', $params['ListingSearch']['remaining_auto_renewal']]); };
        if (isset($params['ListingSearch']['negotiable'])) { $query->andFilterWhere(['=', 't.negotiable', $params['ListingSearch']['negotiable']]); };
        if (isset($params['ListingSearch']['currency_id'])) { $query ->andFilterWhere(['=', '{{%currency}}.currency_id', $params['ListingSearch']['currency_id']]); };
        if (isset($params['ListingSearch']['minPrice'])) { $query->andFilterWhere(['>=', 't.price', $params['ListingSearch']['minPrice']]); };
        if (isset($params['ListingSearch']['maxPrice'])) { $query ->andFilterWhere(['<=', 't.price', $params['ListingSearch']['maxPrice']]); };

        $listing = $query->all();


        $adsDetails = [];
        foreach ($listing as $key => $ad) {
            $location                       = $ad->getLocation()->one();
            $image                          = $ad->getMainImage()->one();
            $adsDetails[$key]['title']      = $ad->title;
            $adsDetails[$key]['lat']        = (float)$location->latitude;
            $adsDetails[$key]['lng']        = (float)$location->longitude;
            $adsDetails[$key]['img']        = $image->image;
            $adsDetails[$key]['slug']       = $ad->slug;
            $adsDetails[$key]['price']      = $ad->getPriceAsCurrency($ad->currency->code);

        }

        return $adsDetails;
    }

    /**
     * @param $locationName
     * @param $locationType
     * @return \app\helpers\Coordinates
     */
    public static function mapCoordinates($locationName, $locationType)
    {

        if(strpos($locationType, 'zo-') === 0) {
            $zoneAddress = $locationName;
        } else {
            $zoneAddress = '';
        }

        if(strpos($locationType, 'co-') === 0) {
            $countryAddress = $locationName;
        } else {
            $countryAddress = '';
        }

        if(strpos($locationType, 'ci-') === 0) {
            $cityAddress = $locationName;
        } else {
            $cityAddress = '';
        }

        $zipAddress = null;

        $coordinates = LocationHelper::getCoordinates($countryAddress, $zoneAddress, $cityAddress, $zipAddress);

        return $coordinates;

    }
}
