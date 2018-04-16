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
 * Class Country
 * @package app\models
 */
class Country extends \app\models\auto\Country
{
    /**
     * Constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['status'], 'string'],
            [['name', 'code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id'    => t('app', 'Country Id'),
            'name'          => t('app', 'Country Name'),
            'code'          => t('app', 'Country Code'),
            'status'        => t('app', 'Status'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
        ];
    }

    /**
     * @return bool
     */
    public function activate()
    {
        if ($this->status == self::STATUS_INACTIVE) {
            $this->status = self::STATUS_ACTIVE;
            $this->save(false);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function deactivate()
    {
        if ($this->status == self::STATUS_ACTIVE) {
            $this->status = self::STATUS_INACTIVE;
            $this->save(false);
        }

        return true;
    }

    /**
     * @return auto\Country[]|array
     */
    public static function getActiveCountries()
    {
        return static::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

}
