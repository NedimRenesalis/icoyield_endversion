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

class Select2Asset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@vendor/select2/select2/dist';

    /**
     * @var array
     */
    public $css = [
        'css/select2.min.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/select2.full.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
