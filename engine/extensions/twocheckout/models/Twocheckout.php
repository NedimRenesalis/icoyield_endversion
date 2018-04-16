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


namespace app\extensions\twocheckout\models;

/**
 * Class Twocheckout
 * @package app\models\options\gateway
 */
class Twocheckout extends \app\models\options\Base
{
    /**
     * @var string
     */
    public $name = 'TwoCheckout';

    /**
     * @var string
     */
    public $status = 'active';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var
     */
    public $accountId;

    /**
     * @var string
     */
    public $privateKey = '';

    /**
     * @var string
     */
    public $publishableKey = '';

    /**
     * @var string
     */
    public $mode = 'sandbox';


    /**
     * @var string
     */
    protected $categoryName = 'app.gateway.twocheckout';


    public function rules()
    {
        return [
            [['accountId', 'privateKey','publishableKey', 'description'], 'required', 'when' => function ($model) {return $model->status == 'active';}],
            ['mode', 'in', 'range' => array_keys($this->getModeList())],
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
            'accountId'             => t('app', 'Account ID'),
            'privateKey'            => t('app', 'Private Key'),
            'publishableKey'        => t('app', 'Publishable Key'),
            'mode'                  => t('app', 'Mode'),
            'status'                => t('app', 'Status'),
            'description'           => t('app', 'Frontend gateway Description'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'accountId'             => t('app', 'Account ID'),
            'privateKey'            => t('app', 'Private Key'),
            'publishableKey'        => t('app', 'Publishable Key'),
            'mode'                  => t('app', 'Mode'),
            'status'                => t('app', 'Status'),
            'description'           => t('app', 'Gateway Description'),
        ];
    }

    /**
     * @return array
     */
    public static function getModeList()
    {
        return [
            'live'    => t('app', 'Live'),
            'sandbox' => t('app', 'Sandbox'),
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