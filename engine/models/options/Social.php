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

namespace app\models\options;

/**
 * Class Social
 * @package app\models\options
 */
class Social extends Base
{
    /**
     * @var string
     */
    public $facebook = '';
    public $instagram = '';
    public $twitter = '';
    public $googlePlus = '';
    public $pinterest = '';
    public $linkedin = '';
    public $vkontakte = '';

    /**
     * @var string
     */
    protected $categoryName = 'app.settings.social';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['facebook', 'instagram', 'twitter', 'googlePlus', 'pinterest', 'linkedin', 'vkontakte'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'facebook'   => t('app', 'Facebook URL'),
            'instagram'  => t('app', 'Instagram URL'),
            'twitter'    => t('app', 'Twitter URL'),
            'googlePlus' => t('app', 'Google Plus URL'),
            'pinterest'  => t('app', 'Pinterest URL'),
            'linkedin'   => t('app', 'Linkedin URL'),
            'vkontakte'  => t('app', 'Vkontakte URL'),
        ];
    }
}