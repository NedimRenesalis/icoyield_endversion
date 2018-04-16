<?php

namespace twisted1919\notify;

use yii\web\AssetBundle;

class NotifyAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/twisted1919/yii2-notify/assets';

    /**
     * @var array
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [
        'js/notify.min.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}
