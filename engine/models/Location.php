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

use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class Location
 * @package app\models
 */
class Location extends \app\models\auto\Location
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'zone_id'], 'required'],
            ['latitude', 'required', 'when' => function($model) {
                return options()->get('app.settings.common.disableMap', 0) == 0;
            },  'whenClient' => "function (attribute, value) {
                return $('#disableMap').val() == 0;
            }",'message' => t('app', 'Please mark a address on map.')],
            ['zip', 'required', 'when' => function($model) {
                return options()->get('app.settings.common.adHideZip', 0) == 0;
            },  'whenClient' => "function (attribute, value) {
                return $('#adHideZip').val() == 0;
            }",'message' => t('app', 'Zip Code cannot be blank..')],
            [['longitude'], 'safe'],
            [['country_id', 'zone_id', 'city', 'zip'], 'trim'],
            [['country_id', 'zone_id'], 'integer'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => 'country_id'],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zone::className(), 'targetAttribute' => 'zone_id', 'filter' => function($query) {
                $query->andWhere(['country_id' => (int)$this->country_id]);
            }],
            [['city'], 'string', 'max' => 150],
            [['zip'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'zone_id'         => t('app', 'Zone'),
            'country_id'      => t('app', 'Country'),
            'city'            => t('app', 'City')
        ]);
    }

    /**
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($existingLocation = $this->checkLocation()) {
            foreach ($existingLocation->attributes as $key=>$value) {
                $this->$key = $value;
            }
            return true;
        } else {
            $this->location_id = null;
            $this->retries     = 0;
            if (options()->get('app.settings.common.disableMap', 0) == 1)
            {
                $this->latitude  = null;
                $this->longitude = null;
            }
            if (options()->get('app.settings.common.adHideZip', 0) == 1)
            {
                $this->zip  = null;
            }
            $this->isNewRecord = true;
        }

        if (options()->get('app.settings.common.disableMap', 0) == 1)
        {
            $this->updateCoordinates();
        }

        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        foreach ($this->attributes as $key => $value) {
            if ( !in_array($key,['status', 'retries']) && ($value == '' || strlen($value) == 0)) {
                $this->$key = null;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return auto\Location|array|null
     */
    public function checkLocation()
    {
        $condition = [
            'country_id'    => $this->country_id,
            'zone_id'       => $this->zone_id,
            'city'          => $this->city,
            'zip'           => null,
            'latitude'      => null,
            'longitude'     => null,
        ];

        if ((options()->get('app.settings.common.adHideZip', 0) == 0)) {
            $condition['zip'] = $this->zip;
        }

        if ((options()->get('app.settings.common.disableMap', 0) == 0)) {
            $condition['latitude'] = number_format($this->latitude, 6);
            $condition['longitude'] = number_format($this->longitude, 6);
        }
        return static::find()->where($condition)->one();
    }

    /**
     * @return Location
     */
    public function updateCoordinates()
    {
        if ($this->latitude !== null && $this->longitude !== null) {
            $this->retries = 0;
            return $this;
        }

        if ($this->hasReachedRetries()) {
            return $this;
        }

        $this->retries  = (int)$this->retries + 1;
        $this->latitude = $this->longitude = null;

        $condition = [];
        foreach (['country_id', 'zone_id', 'city', 'zip'] as $attribute) {
            if (!empty($this->$attribute)) {
                $condition[$attribute] = $this->$attribute;
            }
        }

        if (empty($condition)) {
            return $this;
        }

        $location = static::findOne($condition);

        if ($location !== null && $location->latitude !== null && $location->longitude !== null) {
            $this->latitude  = $location->latitude;
            $this->longitude = $location->longitude;
            return $this;
        }

        $location = [];
        if (!empty($this->country_id) && !empty($this->country)) {
            $location[] = $this->country->name;
        }
        if (!empty($this->zone_id) && !empty($this->zone)) {
            $location[] = $this->zone->name;
        }
        foreach (['city', 'zip'] as $name) {
            $location[] = $this->$name;
        }
        $location = array_filter(array_map('trim', $location));
        $location = implode(',', $location);
        $response = app()->httpClient->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => $location,
            'sensor'  => 'false'
        ])->send();

        if (!$response->isOk) {
            return $this;
        }

        $body = json_decode($response->content);
        if (empty($body->status) || $body->status != "OK" || empty($body->results[0]->geometry->location)) {
            return $this;
        }
        $location = $body->results[0]->geometry->location;
        if (!isset($location->lat) || !isset($location->lng)) {
            return $this;
        }

        $this->latitude  = $location->lat;
        $this->longitude = $location->lng;

        return $this;
    }
    /**
     * @return bool
     */
    public function hasReachedRetries()
    {
        return !empty($this->retries) && (int)$this->retries >= 3;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return trim(sprintf('%s, %s, %s', $this->country->name, $this->zone->name, $this->zip));
    }
/**
     * @return string
     */
    public function getCity()
    {
        return trim(sprintf( '%s', $this->city));
    }


    /**
     * @param $searchTerm
     * @return array
     */
    public static function getResultsByTerm($searchTerm)
    {
        $cacheKey = sha1(__METHOD__ . $searchTerm . 'v1');

        if ($results = cache()->get($cacheKey)) {
            $results['from_cache'] = true;
            return $results;
        }

        $results = array (
            'results' =>
                array (
                    0 =>
                        array (
                            'text' => t('app', 'Country'),
                            'children' =>
                                array (),
                        ),
                    1 =>
                        array (
                            'text' => t('app', 'Zone'),
                            'children' =>
                                array (),
                        ),
                    2 =>
                        array (
                            'text' => t('app', 'City'),
                            'children' =>
                                array (),
                        ),
                )
        );

        $countries  = Country::find()->with('zones')->where(['like', 'name', $searchTerm])->andWhere(['status' => Country::STATUS_ACTIVE])->limit(30)->all();
        $zones      = Zone::find()->with('country')->where(['like', 'name', $searchTerm])->andWhere(['status' => Zone::STATUS_ACTIVE])->limit(30)->all();
        $locations  = static::find()->where(['like', 'city', $searchTerm])->groupBy('city')->limit(30)->all();


        // Build Search results from zones and countries
        foreach ($countries as $key => $country) {
            $results['results'][0]['children'][] = [
                'text'  => html_encode($country->name),
                'id'    => 'co-' . (int)($country->country_id)
            ];
        }
        // Build Search results from zones with active country
        foreach ($zones as $key => $zone) {
            if ($zone->hasActiveCountry()) {
                $results['results'][1]['children'][] = [
                    'text'  => html_encode($zone->name) . ', ' . html_encode($zone->country->name),
                    'id'    => 'zo-' . (int)$zone->zone_id
                ];

            }
        }
        // Build Search results from cities in Location model
        foreach ($locations as $key => $location) {
            $results['results'][2]['children'][] = [
                'text' => html_encode(ucfirst($location->city)),
                'id'   => 'ci-' . html_encode($location->city)
            ];
        }

        cache()->set($cacheKey, $results, 300);

        if(empty($countries)) unset($results['results'][0]);
        if(empty($zones)) unset($results['results'][1]);
        if(empty($locations)) unset($results['results'][2]);

        $results = array_values($results['results']);

        // because it was removed from array_values actions
        return ['results' => $results];
    }
}
