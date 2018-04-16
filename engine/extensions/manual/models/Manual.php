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

namespace app\extensions\manual\models;

/**
 * Class Manual
 * @package app\models\options\gateway
 */
class Manual extends \app\models\options\Base
{
    /**
     * @var string
     */
    public $name = 'Manual';

    /**
     * @var string
     */
    public $status = 'active';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string
     */
    protected $categoryName = 'app.gateway.manual';


    public function rules()
    {
        return [
            [['description'], 'required', 'when' => function ($model) {return $model->status == 'active';}],
            ['status', 'in', 'range' => array_keys($this->getStatusList())],
            [['description'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'description'   => t('app', 'Frontend gateway Description'),
            'status'        => t('app', 'Status'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'description'    => t('app', 'Gateway Description'),
            'status'         => t('app', 'Gateway Status'),
        ];
    }

    /**
     * @return array
     */
    public static function getStatusList()
    {
        return [
            'active'    => t('app', 'Active'),
            'inactive' => t('app', 'Inactive'),
        ];
    }
}