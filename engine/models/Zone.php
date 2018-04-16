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

use yii\helpers\ArrayHelper;

/**
 * Class Zone
 * @package app\models
 */
class Zone extends \app\models\auto\Zone
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','code'], 'required'],
            [['status'], 'string'],
            [['country_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'country_id'    => t('app', 'Country Name'),
            'zone_id'       => t('app', 'Zone Id'),
            'name'          => t('app', 'Zone Name'),
            'code'          => t('app', 'Zone Code'),
            'status'        => t('app', 'Status'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),

        ]);
    }

    /**
     * @param $country_id
     * @return auto\Zone[]|array
     */
    public static function getCountryZones($country_id)
    {
        return self::find()->where(['country_id' => $country_id])->all();
    }

    /**
     * @return bool
     */
    public function activate()
    {
        if ($this->status == self::STATUS_INACTIVE){
            $this->status = self::STATUS_ACTIVE;
            $this->save(false);
        }

        return true;
    }

    public function deactivate()
    {
        if ($this->status == self::STATUS_ACTIVE){
            $this->status = self::STATUS_INACTIVE;
            $this->save(false);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function hasActiveCountry()
    {
        return ($this->country->status === Country::STATUS_ACTIVE) ? true : false;
    }

}
