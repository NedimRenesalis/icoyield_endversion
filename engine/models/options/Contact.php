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

use yii\helpers\ArrayHelper;

/**
 * Class Contact
 * @package app\models\options
 */
class Contact extends Base
{
    // identifiers of sections
    const SECTION_ONE = 1;
    const SECTION_TWO = 2;

    /**
     * @var string
     */
    public $keywords = '';

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string
     */
    public $contactEmail = '';

    /**
     * @var string
     */
    public $shortDescription = '';

    /**
     * @var int
     */
    public $enableMap = 0;

    /**
     * @var string
     */
    public $country = '';

    /**
     * @var string
     */
    public $zone = '';

    /**
     * @var string
     */
    public $city = '';

    /**
     * @var integer
     */
    public $zip = 0;

    /**
     * @var string
     */
    public $section = 0;

    /**
     * @var string
     */
    public $sort_order = 0;

    /**
     * @var string
     */
    public $latitude = 0;

    /**
     *@var string
     */
    public $longitude = 0;

    /**
     *
     */
    public $senderEmail = 0;

    /**
     * @var string
     */
    protected $categoryName = 'app.content.contact';


    public function rules()
    {
        return [
            [['enableMap','country','zone','city','senderEmail'],'safe'],
            [['zip'], 'integer'],
            [['keywords'], 'string', 'max' => 160],
            [['description'], 'string', 'max' => 255],
            [['contactEmail','enableMap'], 'required'],
            [['section', 'sort_order'],'integer'],
            [['shortDescription'], 'string'],
            [['contactEmail'], 'email'],
            [['latitude', 'longitude'], 'required', 'when' => function ($model) {return $model->enableMap == 1;}]
        ];
    }
    /**
     * Return list of available statuses of page
     *
     * @return array
     */

    /**
     * Return list of available sections, could be filtered by section id
     *
     * @param null $filter
     *
     * @return array|string
     */
    public static function getSectionsList($filter = null)
    {
        $sections = [
            self::SECTION_ONE => options()->get('app.settings.common.footerFirstPageSectionTitle', 'Help'),
            self::SECTION_TWO => options()->get('app.settings.common.footerSecondPageSectionTitle', 'About'),
        ];

        return $filter ? $sections[$filter] : $sections;
    }

    /**
     * Get name of section
     *
     * @return mixed
     */
    public function getSection()
    {
        $sections = $this->getSectionsList();

        return $this->section ? $sections[$this->section] : '';
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'keywords'          => t('app', 'Page Meta Keywords'),
            'description'       => t('app', 'Page Meta Description'),
            'contactEmail'      => t('app', 'Contact Email'),
            'shortDescription'  => t('app', 'Site Description'),
            'enableMap'         => t('app', 'Enable Map'),
            'country'           => t('app', 'Country'),
            'city'              => t('app', 'City'),
            'zone'              => t('app', 'Zone'),
            'zip'               => t('app', 'Zip Code'),
            'section'           => t('app', 'Section'),
            'sort_order'        => t('app', 'Sort Order'),
            'latitude'          => t('app', 'Latitude'),
            'longitude'         => t('app', 'Longitude'),
            'senderEmail'       => t('app', 'Send Email to Sender')
        ]);
    }

    /**
     * @return array
     */
    public function attributeHelpTexts()
    {
        return [
            'keywords'          => t('app', 'Help tell search engines what the topic of the page is.'),
            'description'       => t('app', 'Provide concise explanations of the contents of web pages.'),
            'contactEmail'      => t('app', 'Email for contact'),
            'shortDescription'  => t('app', 'Short description for page.'),
            'enableMap'         => t('app', 'Choose if enable map or not.'),
            'section'           => t('app', 'Refer to page'),
            'sort_order'        => t('app', 'Refer to page'),
            'country'           => t('app', 'Refer to Country'),
            'zone'              => t('app', 'Refer to City'),
            'city'              => t('app', 'Refer to Zone'),
            'zip'               => t('app', 'Refer to Zip Code'),
            'senderEmail'       => t('app', 'Send a copy of contact email to sender.'),
        ];
    }

    /**
     * @return mixed
     */
    public function attributePlaceholders()
    {
        return ArrayHelper::merge($this->attributeLabels(), [
            'country'   => t('app', 'Type name of your Country.'),
            'zone'      => t('app', 'Type name of your Zone'),
            'city'      => t('app', 'Type name of your City'),
            'zip'       => t('app', 'Type your Zip Code')
        ]);
    }

    /**
     * @return bool
     */
    public function afterValidate()
    {
        parent::afterValidate();
        return true;
    }

}