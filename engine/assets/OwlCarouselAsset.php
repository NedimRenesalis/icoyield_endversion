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

class OwlCarouselAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@bower/owl.carousel/dist';

    /**
     * @var array
     */
    public $css = [
        'assets/owl.carousel.min.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'owl.carousel.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
