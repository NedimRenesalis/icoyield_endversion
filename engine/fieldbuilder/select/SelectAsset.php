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

namespace app\fieldbuilder\select;

use yii\web\AssetBundle;

class SelectAsset extends AssetBundle
{
    public $sourcePath = '@app/fieldbuilder/select/assets';

    public $css = [

    ];
    public $js = [
        'field.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}