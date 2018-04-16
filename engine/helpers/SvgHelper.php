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

namespace app\helpers;

/**
 * Class SvgHelper
 * @package app\helpers
 */
class SvgHelper
{
    /**
     * @param $name
     * @return string
     */
    public static function getByName($name)
    {
        $svg      = '';
        $basePath = \Yii::getAlias('@webroot/assets/site/svg');
        if (is_file($svgFile = $basePath . '/' . $name . '.svg')) {
            $svg = file_get_contents($svgFile);
        }

        return $svg;
    }
}