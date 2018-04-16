<?php

require(__DIR__ . '/engine/config/constants.php');
require(__DIR__ . '/engine/vendor/autoload.php');
require(__DIR__ . '/engine/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/engine/config/web.php');

(new yii\web\Application($config))->run();
