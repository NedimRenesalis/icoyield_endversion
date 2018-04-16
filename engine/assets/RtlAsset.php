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

class RtlAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@bower/bootstrap-rtl';

    /**
     * @var array
     */
    public $css = [
        'dist/css/bootstrap-rtl.css'
    ];

    /**
     * @var array
     */
    public $js = [
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
