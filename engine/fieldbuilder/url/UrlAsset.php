<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.3
 */

namespace app\fieldbuilder\url;

use yii\web\AssetBundle;

class UrlAsset extends AssetBundle
{
    public $sourcePath = '@app/fieldbuilder/url/assets';

    public $css = [

    ];
    public $js = [
        'field.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}
