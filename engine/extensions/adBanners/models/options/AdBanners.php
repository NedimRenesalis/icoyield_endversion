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

namespace app\extensions\adBanners\models\options;

use app\extensions\adBanners\helpers\AdBannersHelper;
use app\models\options\Base;

/**
 * Class AdBanners
 * @package app\extensions\adBanners\models\options
 */
class AdBanners extends Base
{
    /**
     * @var array
     */
    public $locations = [];

    public $headScripts = '';

    /**
     * @var string
     */
    protected $categoryName = 'app.extensions.adBanners';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['locations', 'headScripts'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $adBannersLocations = AdBannersHelper::getStaticAdBannersProperties();
        $labels = [];

        foreach ($adBannersLocations as $attribute => $adBannersLocation) {
            $labels[$attribute] = $adBannersLocation['label'];
        }

        return $labels;
    }

    /**
     * @return bool
     */
    public function afterValidate()
    {
        parent::afterValidate();
        return true;
    }

    /**
     * manually saving attributes
     */
    public function save()
    {
        foreach ($this->locations as $key => $value) {
            options()->set($this->categoryName . '.' . $key, $value);
        }
        $this->locations = [];
        parent::save();
    }
}