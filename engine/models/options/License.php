<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

namespace app\models\options;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class License
 * @package app\models\options
 */
class License extends Base
{
    /**
     * @var
     */
    public $firstName;
    
    /**
     * @var
     */
    public $lastName;
    
    /**
     * @var
     */
    public $email;
    
    /**
     * @var
     */
    public $purchaseCode;

    protected $categoryName = 'app.settings.license';

    public function rules()
    {
        return
        [
            [['firstName', 'lastName', 'email', 'purchaseCode'], 'required'],
            [['firstName', 'lastName', 'email', 'purchaseCode'], 'string', 'max' => 255],
            ['email', 'email', 'enableIDN' => true],
        ];

    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = array(
            'firstName'     => t('app', 'First name'),
            'lastName'      => t('app', 'Last name'),
            'email'         => t('app', 'Email'),
            'purchaseCode'  => t('app', 'Purchase code'),
        );

        return ArrayHelper::merge($labels, parent::attributeLabels());
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'purchaseCode'=> t('app', 'Please supply the purchase code for license activation'),
        ];
    }
}
