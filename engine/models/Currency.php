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
 * Class Currency
 * @package app\models
 */
class Currency extends \app\models\auto\Currency
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
            [['name', 'symbol', 'code'], 'required'],
            [['status'], 'string'],
            [['name', 'symbol', 'code'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_id'   => t('app','Currency Id'),
            'name'          => t('app','Currency Name'),
            'code'          => t('app','Code'),
            'symbol'        => t('app','Symbol'),
            'status'        => t('app','Status'),
            'created_at'    => t('app','Created At'),
            'updated_at'    => t('app','Updated At')
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
     * @return auto\Currency[]|array
     */
    public static function getActiveCurrencies()
    {
        return static::find()->where(['status' => self::STATUS_ACTIVE])->all();
    }

}
