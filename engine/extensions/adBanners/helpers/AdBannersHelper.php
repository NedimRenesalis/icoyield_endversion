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

namespace app\extensions\adBanners\helpers;

/**
 * Class AdBannersHelper
 * @package app\helpers
 */
class AdBannersHelper
{
    /**
     * @return array
     */
    public static function getStaticAdBannersProperties()
    {
        $locations = [
            [
                'optionKey' => 'homeUnderCategories',
                'event'     => 'home.under.categories',
                'label'     => t('app','Home page under categories icons'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'homeAboveFooter',
                'event'     => 'home.above.footer',
                'label'     => t('app','Home page above footer bar'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'searchAboveResults',
                'event'     => 'search.above.results',
                'label'     => t('app','Search page above the results'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'searchMapBottom',
                'event'     => 'search.map.bottom',
                'label'     => t('app','Search page map view bottom'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'categoryAboveResults',
                'event'     => 'category.above.results',
                'label'     => t('app','Category page above the results'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'categoryMapBottom',
                'event'     => 'category.map.bottom',
                'label'     => t('app','Category page map view bottom'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'adAboveGallery',
                'event'     => 'ad.above.gallery',
                'label'     => t('app','Ad page above images gallery'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'adUnderGallery',
                'event'     => 'ad.under.gallery',
                'label'     => t('app','Ad page under images gallery'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ],
            [
                'optionKey' => 'adAfterDescription',
                'event'     => 'ad.after.description',
                'label'     => t('app','Ad page next to the ad description'),
                'help'      => t('app','We recommend using Square banner for this location'),
            ],
            [
                'optionKey' => 'storeAboveResults',
                'event'     => 'store.above.results',
                'label'     => t('app','Store page above the results'),
                'help'      => t('app','We recommend using leaderboard or full banner for this location'),
            ]
        ];

        return $locations;
    }
}