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

namespace app\extensions\manual;

use yii\web\AssetBundle;

/**
 * Class ManualAsset
 * @package app\extensions\manual
 */
class ManualAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/manual/assets';

    public $css = [

    ];
    public $js = [
        'manual.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}