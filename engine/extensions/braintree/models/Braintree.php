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

namespace app\extensions\braintree\models;

/**
 * Class Braintree
 * @package app\models\options\gateway
 */
class Braintree extends \app\models\options\Base
{
    /**
     * @var string
     */
    public $name = 'BrainTree';

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
    public $merchantId;

    /**
     * @var
     */
    public $merchantCurrency;

    /**
     * @var string
     */
    public $privateKey = '';

    /**
     * @var string
     */
    public $publicKey = '';

    /**
     * @var string
     */
    public $mode = 'sandbox';


    /**
     * @var string
     */
    protected $categoryName = 'app.gateway.braintree';


    public function rules()
    {
        return [
            [['merchantId', 'merchantCurrency', 'privateKey','publicKey', 'description'], 'required', 'when' => function ($model) {return $model->status == 'active';}],
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
            'merchantId'            => t('app', 'Merchant ID'),
            'merchantCurrency'      => t('app', 'Merchant Currency'),
            'privateKey'            => t('app', 'Private Key'),
            'publicKey'             => t('app', 'Public Key'),
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
            'merchantId'            => t('app', 'Merchant ID'),
            'privateKey'            => t('app', 'Private Key'),
            'merchantCurrency'      => t('app', 'Merchant Currency'),
            'publicKey'             => t('app', 'Public Key'),
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