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

namespace app\fieldbuilder\checkbox;

use yii\web\AssetBundle;

class CheckboxAsset extends AssetBundle
{
    public $sourcePath = '@app/fieldbuilder/checkbox/assets';

    public $css = [

    ];
    public $js = [
        'field.js',
    ];

    public $depends = [
        'app\assets\AdminLteAsset',
    ];
}