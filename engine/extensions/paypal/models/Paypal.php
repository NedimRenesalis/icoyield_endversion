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

namespace app\extensions\paypal\models;

/**
 * Class Paypal
 * @package app\models\options\gateway
 */
class Paypal extends \app\models\options\Base
{
    /**
     * @var string
     */
    public $name = 'PayPal';

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
    public $email = '';

    /**
     * @var string
     */
    public $username = '';

    /**
     * @var string
     */
    public $password = '';

    /**
     * @var string
     */
    public $signature = '';

    /**
     * @var string
     */
    public $mode = 'sandbox';


    /**
     * @var string
     */
    protected $categoryName = 'app.gateway.paypal';


    public function rules()
    {
        return [
            [['email','username', 'password', 'signature', 'description'], 'required', 'when' => function ($model) {return $model->status == 'active';}],
            [['username', 'password', 'signature'], 'string'],
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
            'email'         => t('app', 'Email'),
            'description'   => t('app', 'Frontend gateway Description'),
            'username'      => t('app', 'API Username'),
            'password'      => t('app', 'API Password'),
            'signature'     => t('app', 'API Signature'),
            'mode'          => t('app', 'Mode'),
            'status'        => t('app', 'Status'),
        ];
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'email'          => t('app', 'Paypal Email'),
            'description'    => t('app', 'Gateway Description'),
            'username'       => t('app', 'Paypal Username'),
            'password'       => t('app', 'Paypal Password'),
            'signature'      => t('app', 'Paypal Signature'),
            'mode'           => t('app', 'Gateway Mode'),
            'status'         => t('app', 'Gateway Status'),
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