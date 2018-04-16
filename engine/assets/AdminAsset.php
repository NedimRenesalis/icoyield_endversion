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

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/admin/css/admin.css',
    ];
    public $js = [
        'assets/admin/js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'dmstr\web\AdminLteAsset',
        'app\assets\BootstrapSwitchAsset',
        'app\assets\FileinputAsset',
        'app\assets\SpeakingurlAsset',
        'twisted1919\notify\NotifyAsset',
    ];
}
