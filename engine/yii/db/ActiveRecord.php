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


namespace app\yii\db;

use yii\db\ActiveRecord as BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class ActiveRecord extends BaseActiveRecord
{
    // when inactive model
    const STATUS_INACTIVE = 'inactive';

    // when active model
    const STATUS_ACTIVE = 'active';

    const STATUS_DEACTIVATED = 'deactivated';

    //
    const TEXT_YES = 'yes';

    //
    const TEXT_NO = 'no';

    // when creating the model
    const SCENARIO_CREATE = 'create';

    // when updating the model
    const SCENARIO_UPDATE = 'update';

    // when admin creates
    const SCENARIO_ADMIN_CREATE = 'create_admin';

    // yes or no fields
    const YES = 1;
    const NO = 0;


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // auto fill timestamp columns.
        if ($this->hasAttribute('created_at') || $this->hasAttribute('updated_at')) {
            $behavior = [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ];
            if ($this->hasAttribute('created_at')) {
                $behavior['createdAtAttribute'] = 'created_at';
            } else {
                $behavior['createdAtAttribute'] = null;
            }
            if ($this->hasAttribute('updated_at')) {
                $behavior['updatedAtAttribute'] = 'updated_at';
            } else {
                $behavior['updatedAtAttribute'] = null;
            }
            $behaviors[] = $behavior;
        }
        return $behaviors;
    }

    /**
     * @return array
     */
    public static function getStatusesList()
    {
        return [
            self::STATUS_ACTIVE   => t('app', 'Active'),
            self::STATUS_INACTIVE => t('app', 'Inactive'),
//            self::STATUS_DEACTIVATED  => t('app', 'Deactivated'),
        ];
    }

    /**
     * @return array
     */
    public static function getYesNoList()
    {
        return [
            self::TEXT_YES => t('app', 'Yes'),
            self::TEXT_NO  => t('app', 'No'),
        ];
    }
}