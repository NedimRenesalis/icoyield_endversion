<?php

if (is_file($file = __DIR__ ."/constants-local.php")) {
    require $file;
}

defined('YII_ENV') or define('YII_ENV', 'production');

define('APP_PATH', realpath(dirname(__FILE__).'/..'));
define('APP_APPS_PATH', APP_PATH);


define('APP_NAME', 'EasyAds');

// defined the app version
define('APP_VERSION', '1.3');

// flag to see if we are under cli
define('IS_CLI', php_sapi_name() == 'cli' || (!isset($_SERVER['SERVER_SOFTWARE']) && !empty($_SERVER['argv'])));

// misc
set_time_limit(0);
ini_set('memory_limit', -1);

if (YII_ENV == 'production') {

    // error reporting
    error_reporting(0);
    ini_set('display_errors', 0);

    // yii specific
    defined('YII_DEBUG') or define('YII_DEBUG', false);

} else {

    // error reporting
    error_reporting(-1);
    ini_set('display_errors', 1);

    // yii specific
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}