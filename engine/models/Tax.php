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
 * Class Tax
 * @package app\models
 */
class Tax extends \app\models\auto\Tax
{
    public $price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'percent', 'is_global', 'status'], 'required'],
            ['status', 'in', 'range' => array_keys(self::getStatusesList())],
            ['is_global', 'in', 'range' => array_keys(self::getYesNoList())],
            [['country_id', 'zone_id'], 'integer'],
            [['percent'], 'integer', 'integerOnly' => false],
            [['name'], 'string', 'max' => 80],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'zone_id'       => t('app', 'Zone'),
            'country_id'    => t('app', 'Country'),
            'name'          => t('app', 'Tax Name'),
            'percent'       => t('app', 'Percent'),
            'is_global'     => t('app', 'Is Global'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
            'status'        => t('app', 'Status'),
        ]);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->country_id == 0) {
            $this->country_id = null;
        }

        if ($this->zone_id == 0) {
            $this->zone_id = null;
        }

        return true;
    }

    /**
     * @param null $country_id
     * @param null $zone_id
     * @return array
     */
    public static function getTax($country_id = null, $zone_id = null)
    {
        $globalTax = self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['is_global' => 'yes'])->all();
        $countryTax = self::find()->where(['status' => self::STATUS_ACTIVE])->andWhere(['country_id' => $country_id])->all();

        foreach ($countryTax as $key => $c_tax) {
            if($c_tax->zone_id == null) {
                continue;
            }
            if($c_tax->zone_id != $zone_id) {
                unset($countryTax[$key]);
            }
        }

        $tax = array_merge($globalTax,$countryTax);

        return $tax;
    }

    public static function calculateTax($country_id = null, $zone_id = null, $price = 0)
    {
        if($price == 0) return;
        $locationTax = self::getTax($country_id, $zone_id);
        foreach ($locationTax as $tax){
            $tax->price =  ($tax->percent / 100) * $price;
        }
        return $locationTax;
    }
}
