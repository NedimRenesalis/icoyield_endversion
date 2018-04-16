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

namespace app\assets;

use yii\web\AssetBundle;

class SpeakingurlAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@bower/speakingurl';

    /**
     * @var array
     */
    public $css = [
    ];

    /**
     * @var array
     */
    public $js = [
        'speakingurl.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
