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

namespace app\extensions\stripe;

use yii\web\AssetBundle;

class StripeAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/stripe/assets';

    public $css = [

    ];
    public $js = [
        'stripe.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}