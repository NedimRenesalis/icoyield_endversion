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


class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/site/css/style.css',
        'assets/site/css/app.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js',
        'assets/site/js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\FontsAsset',
        'app\assets\LazysizesAsset',
        'app\assets\JqueryPluginsAsset',
        'rmrevin\yii\fontawesome\cdn\AssetBundle',
        'twisted1919\notify\NotifyAsset',
        'app\assets\Select2Asset',
        'app\assets\MCustomScrollbarAsset',
        'kartik\select2\Select2Asset',
        'kartik\select2\ThemeKrajeeAsset',
    ];
}
