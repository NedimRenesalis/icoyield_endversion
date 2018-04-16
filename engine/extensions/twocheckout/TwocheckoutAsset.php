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

namespace app\extensions\twocheckout;

use yii\web\AssetBundle;

class TwocheckoutAsset extends AssetBundle
{
    public $sourcePath = '@app/extensions/twocheckout/assets';

    public $css = [

    ];
    public $js = [
        'twocheckout.js',
        'https://www.2checkout.com/checkout/api/2co.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}