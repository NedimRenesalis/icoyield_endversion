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

class FileinputAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@vendor/kartik-v/bootstrap-fileinput';

    /**
     * @var array
     */
    public $css = [
        'css/fileinput.min.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/fileinput.min.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
