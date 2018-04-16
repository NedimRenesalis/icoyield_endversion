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

namespace app\fieldbuilder\text;

use yii\web\AssetBundle;

class TextAsset extends AssetBundle
{
    public $sourcePath = '@app/fieldbuilder/text/assets';

    public $css = [

    ];
    public $js = [
        'field.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}