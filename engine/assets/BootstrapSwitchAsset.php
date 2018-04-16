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

class BootstrapSwitchAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@vendor/nostalgiaz/bootstrap-switch/dist';

    /**
     * @var array
     */
    public $css = [
        'css/bootstrap3/bootstrap-switch.min.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/bootstrap-switch.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
