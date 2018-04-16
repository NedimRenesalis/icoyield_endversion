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
 * Class Group
 * @package app\models
 */
class Group extends \app\models\auto\Group
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['status'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'name'          => t('app', 'Group Name'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
            'users'         => t('app', 'Users count'),
            'status'        => t('app', 'Status'),

        ]);
    }

    /**
     * @return array
     */
    public function getAllRoutesAccess()
    {
        return GroupRouteAccess::findAllByGroupId((int)$this->group_id);
    }
}