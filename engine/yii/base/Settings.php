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
namespace app\yii\base;

use Yii;
use yii\i18n\Formatter;
use yii\base\BootstrapInterface;

/**
 * Class Settings
 *
 * @package app\yii\base
 */
class Settings implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * Loads the settings from db into app
     * @param Application $app the application currently running
     */
    public function bootstrap($app) {
        // if install is in progress then return;
        if(defined('INSTALL_MIGRATION')){
            return;
        }

        Yii::$app->formatter->currencyCode      = html_encode(options()->get('app.settings.common.siteCurrency', 'usd'));

        $dateTime = new \IntlDateFormatter(html_encode(options()->get('app.settings.common.siteLanguage', 'en')), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::NONE);
        Yii::$app->formatter->dateFormat        = $dateTime->getPattern();

        $date = new \IntlDateFormatter(html_encode(options()->get('app.settings.common.siteLanguage', 'en')), \IntlDateFormatter::MEDIUM, \IntlDateFormatter::SHORT);
        Yii::$app->formatter->datetimeFormat    = $date->getPattern();

        $time = new \IntlDateFormatter(html_encode(options()->get('app.settings.common.siteLanguage', 'en')), \IntlDateFormatter::NONE, \IntlDateFormatter::SHORT);
        Yii::$app->formatter->timeFormat        = $time->getPattern();

        Yii::$app->formatter->locale            = html_encode(options()->get('app.settings.common.siteLanguage', 'en'));

        Yii::$app->formatter->timeZone          = html_encode(options()->get('app.settings.common.siteTimezone', 'UTC'));
    }
}