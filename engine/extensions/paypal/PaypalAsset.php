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

namespace app\extensions\paypal;

use yii\web\AssetBundle;

/**
 * Class PaypalAsset
 * @package app\extensions\paypal
 */
class PaypalAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/paypal/assets';

    public $css = [

    ];
    public $js = [
        'paypal.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}