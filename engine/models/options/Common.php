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

use app\models\Customer;
use app\models\CustomerStore;

/**
 * Class Common
 * @package app\models\options
 */
class Common extends Base
{
    /**
     * @var string
     */
    public $siteName = 'EasyAds';

    /**
     * @var string
     */
    public $siteEmail = 'contact@easyads.io';

    /**
     * @var
     */
    public $siteCurrency = 'USD';

    /**
     * @var
     */
    public $siteLanguage = 'en';

    /**
     * @var string
     */
    public $siteTimezone = 'UTC';

    /**
     * @var string
     */
    public $siteKeywords = 'Ads, Classified ads, sell, buy, trade, market';

    /**
     * @var string
     */
    public $siteDescription = 'Ads application';

    /**
     * @var int
     */
    public $siteStatus = 1;

    /**
     * @var string
     */
    public $siteOfflineMessage = 'Application is offline, please try again later!';

    /**
     * @var
     */
    public $googleAnalyticsCode;

    /**
     * @var string
     */
    public $siteFacebookId = '';

    /**
     * @var string
     */
    public $siteFacebookSecret = '';

    /**
     * @var int
     */
    public $adminApprovalAds = 0;

    /**
     * @var string
     */
    public $siteGoogleMapsKey = '';

    /**
     * @var string
     */
    public $relatedAds = 'yes';

    /**
     * @var string
     */
    public $footerFirstPageSectionTitle = 'Help';

    /**
     * @var string
     */
    public $footerSecondPageSectionTitle = 'About';

    /**
     * @var int
     */
    public $homePromotedNumber = 8;

    /**
     * @var int
     */
    public $homeNewNumber = 8;

    /**
     * @var int
     */
    public $prettyUrl = 0;

    /**
     * @var string
     */
    public $captchaSiteKey = '';

    /**
     * @var string
     */
    public $captchaSecretKey = '';

    /**
     * @var int
     */
    public $skipPackages = 0;

    /**
     * @var int
     */
    public $defaultPackage = 0;

    /**
     * @var int
     */
    public $freeAdsLimit = 9999;
    /**
     * @var int
     */
    public $adsImagesLimit = 4;

    /**
     * @var string
     */
    public $siteGoogleId = '';

    /**
     * @var string
     */
    public $siteGoogleSecret = '';

    /**
     * @var string
     */
    public $siteLinkedInId = '';

    /**
     * @var string
     */
    public $siteLinkedInSecret = '';

    /**
     * @var string
     */
    public $siteTwitterId = '';

    /**
     * @var string
     */
    public $siteTwitterSecret = '';

    /**
     * @var int
     */
    public $adHideZip = 0;

    /**
     * @var int
     */
    public $disableMap = 0;

    /**
     * @var int
     */
    public $disableStore = 0;

    /**
     * @var string
     */
    public $expiredDays = 0;

    /**
     * @var string
     */
    public $dailyNotification = 0;

    /**
     * @var string
     */
    protected $categoryName = 'app.settings.common';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'siteKeywords',
                'siteDescription',
                'siteStatus',
                'siteOfflineMessage',
                'siteFacebookId',
                'siteFacebookSecret',
                'adminApprovalAds',
                'siteGoogleMapsKey',
                'relatedAds',
                'homePromotedNumber',
                'homeNewNumber',
                'prettyUrl',
                'captchaSiteKey',
                'captchaSecretKey',
                'adHideZip',
                'disableMap',
                'skipPackages',
                'defaultPackage',
                'siteGoogleId',
                'siteGoogleSecret',
                'siteLinkedInId',
                'siteLinkedInSecret',
                'siteTwitterId',
                'siteTwitterSecret',
                'expiredDays',
                'dailyNotification',
                'disableStore'
            ], 'safe'],
            [['siteLanguage', 'siteCurrency', 'siteTimezone', 'siteName','siteEmail', 'footerFirstPageSectionTitle', 'footerSecondPageSectionTitle', 'adsImagesLimit'], 'required'],

            [['siteName','siteOfflineMessage', 'googleAnalyticsCode'], 'string', 'max' => 255],
            [['siteEmail'], 'string', 'max' => 100],
            [['disableMap', 'skipPackages', 'freeAdsLimit', 'adsImagesLimit', 'expiredDays', 'dailyNotification'], 'integer'],
            [['defaultPackage'],
                'integer',
                'min' => 1,
                'tooSmall' => t('app', 'Please select a default package!'),
                'when' => function ($model) {return $model->skipPackages == 1;}
                ],
            [['footerFirstPageSectionTitle', 'footerSecondPageSectionTitle'], 'string', 'max' => 25],
            [['homePromotedNumber' , 'homeNewNumber'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'siteName'                      => t('app', 'Application name'),
            'siteEmail'                     => t('app', 'Application Email'),
            'siteCurrency'                  => t('app', 'Application Currency'),
            'siteLanguage'                  => t('app', 'Application Language'),
            'siteTimezone'                  => t('app', 'Application Timezone'),
            'siteFacebookId'                => t('app', 'Facebook app id'),
            'siteFacebookSecret'            => t('app', 'Facebook app secret'),
            'siteGoogleId'                  => t('app', 'Google app id'),
            'siteGoogleSecret'              => t('app', 'Google app secret'),
            'siteLinkedInId'                => t('app', 'LinkedIn app id'),
            'siteLinkedInSecret'            => t('app', 'LinkedIn app secret'),
            'siteTwitterId'                 => t('app', 'Twitter app id'),
            'siteTwitterSecret'             => t('app', 'Twitter app secret'),
            'adminApprovalAds'              => t('app', 'Admin should approve new ads'),
            'siteGoogleMapsKey'             => t('app', 'Google maps API key'),
            'relatedAds'                    => t('app', 'Related Ads Section'),
            'footerFirstPageSectionTitle'   => t('app', 'First Section Title'),
            'footerSecondPageSectionTitle'  => t('app', 'Second Section Title'),
            'googleAnalyticsCode'           => t('app', 'Google Analytics code'),
            'homePromotedNumber'            => t('app', 'Number of promoted ads on homepage'),
            'homeNewNumber'                 => t('app', 'Number of new ads on homepage'),
            'captchaSiteKey'                => t('app', 'reCAPTCHA Site key'),
            'captchaSecretKey'              => t('app', 'reCAPTCHA Secret key'),
            'adHideZip'                     => t('app', 'Hide ZIP code'),
            'disableMap'                    => t('app', 'Hide Google Map'),
            'skipPackages'                  => t('app', 'Skip packages'),
            'defaultPackage'                => t('app', 'Default free package'),
            'adsImagesLimit'                => t('app', 'Limit of images per ads'),
            'freeAdsLimit'                  => t('app', 'Free ads limit'),
            'siteKeywords'                  => t('app', 'Site keywords'),
            'siteDescription'               => t('app', 'Site description'),
            'siteStatus'                    => t('app', 'Site status'),
            'siteOfflineMessage'            => t('app', 'Site offline message'),
            'prettyUrl'                     => t('app', 'Pretty URL'),
            'expiredDays'                   => t('app', 'Nr. days for daily notifications'),
            'dailyNotification'             => t('app', 'Send daily notification'),
            'disableStore'                  => t('app' ,'Disable Stores')

        ];
    }


    /**
     * @return bool
     */
    public function afterValidate()
    {
        parent::afterValidate();

        if ($this->disableStore == 1) {

            $stores = CustomerStore::findAll(['status' => CustomerStore::STATUS_ACTIVE]);
            foreach ($stores as $store) {
                $store->deactivate();
            }

            $customers = Customer::findAll(['group_id' => 2]);
            foreach ($customers as $customer) {
                $customer->setGroupId(1);
            }
        }
    }

}