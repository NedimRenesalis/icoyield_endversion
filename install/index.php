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

if (version_compare(PHP_VERSION, '5.4', '<')) {
    exit('EasyAds requires PHP >= 5.4 in order to work. Please upgrade your PHP version!');
}

/* start the session since we will hold various data in session. */
session_start();

/* display errors if any */
ini_set('display_errors', 1);
error_reporting(-1);

/* make sure we have enough time and memory */
set_time_limit(0);
ini_set('memory_limit', -1);

/* stay utc */
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set('UTC');
}

//including app constants
require realpath(dirname(__FILE__) . '/../engine/config/constants.php');

/* a few constants that will be used later */
define('INST_ROOT_PATH', realpath(dirname(__FILE__) . '/..'));
define('INST_APP_PATH', INST_ROOT_PATH . '/engine');
define('INST_VENDOR_PATH', INST_APP_PATH . '/vendor');
define('INST_INSTALLER_PATH', INST_ROOT_PATH . '/install');
define('INST_DB_FILE', INST_APP_PATH . '/config/db.php');
define('INST_REQUEST_FILE', INST_APP_PATH . '/config/request.php');

define('INST_DB_FILE_DEFINITION', INST_APP_PATH . '/data/config/db.php');
define('INST_REQUEST_FILE_DEFINITION', INST_APP_PATH . '/data/config/request.php');

define('INSTALL_MIGRATION', TRUE);

/* if the app has been installed already, redirect back */
if (!isset($_SESSION['config_file_created']) && is_file(INST_DB_FILE) && is_file(INST_REQUEST_FILE)) {
     header('location: ../');
     exit;
}

/* composer autoloader */
require_once INST_VENDOR_PATH . '/autoload.php';

/* require base functionality files. */
require_once INST_INSTALLER_PATH . '/inc/functions.php';
require_once INST_INSTALLER_PATH . '/inc/Controller.php';

// cron jobs display handler
require_once INST_APP_PATH . '/helpers/CommonHelper.php';

$route = getQuery('route');

if (empty($route)) {
    $route = 'welcome';
}

/* just to make sure file inclusion won't work. */
$route = str_replace(array('../', '..'), '', $route);

/* find the controller and the action and apply any fallback for controller/action. */
$controller = $action = null;

if (strpos($route, '/') !== false) {
    $routeParts = explode('/', $route);
    $routeParts = array_slice($routeParts, 0, 2);
    list($controller, $action) = $routeParts;
} else {
    $controller = $route;
}

$controller = formatController($controller);
if (!empty($action)) {
    $action = formatAction($action);
}

if (!is_file($controllerFile = INST_INSTALLER_PATH . '/controllers/' . $controller . '.php')) {
    $controller = formatController('requirements');
    $controllerFile = INST_INSTALLER_PATH . '/controllers/' . $controller . '.php';
}

require_once $controllerFile;
$controller = new $controller();

if (empty($action)) {
    $action = 'actionIndex';
} elseif (!method_exists($controller, $action)) {
    $action = 'actionNot_found';
}

/* and finally run the controller action. */
$controller->$action();
