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

namespace app\helpers;
use app\models\options\License;
use app\models\Order;

/**
 * Class CommonHelper
 * @package app\helpers
 */
class CommonHelper
{
    /**
     * self::getQueriesFromSqlFile()
     *
     * @param string $sqlFile
     * @param string $dbPrefix
     * @return array
     */
    public static function getQueriesFromSqlFile($sqlFile, $dbPrefix = null)
    {
        if (!is_file($sqlFile) || !is_readable($sqlFile)) {
            return array();
        }

        if (!empty($dbPrefix)) {
            $searchReplace = array(
                'CREATE TABLE IF NOT EXISTS `'  => 'CREATE TABLE IF NOT EXISTS `' . $dbPrefix,
                'DROP TABLE IF EXISTS `'        => 'DROP TABLE IF EXISTS `' . $dbPrefix,
                'INSERT INTO `'                 => 'INSERT INTO `' . $dbPrefix,
                'ALTER TABLE `'                 => 'ALTER TABLE `' . $dbPrefix,
                'ALTER IGNORE TABLE `'          => 'ALTER IGNORE TABLE `' . $dbPrefix,
                'REFERENCES `'                  => 'REFERENCES `' . $dbPrefix,
                'UPDATE `'                      => 'UPDATE `' . $dbPrefix,
                ' FROM `'                       => ' FROM `' . $dbPrefix,
            );
            $search  = array_keys($searchReplace);
            $replace = array_values($searchReplace);
        }

        $queries = array();
        $query   = '';
        $lines   = file($sqlFile);

        foreach ($lines as $line) {

            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '#') === 0 || strpos($line, '/*!') === 0) {
                continue;
            }

            $query .= $line;

            if (!preg_match('/;\s*$/', $line)) {
                continue;
            }

            if (!empty($dbPrefix)) {
                $query = str_replace($search, $replace, $query);
            }

            if (!empty($query)) {
                $queries[] = $query;
            }

            $query = '';
        }

        return $queries;
    }

    /**
     * @param $string
     * @param string $separator
     * @return array
     */
    public static function getArrayFromString($string, $separator = ',')
    {
        $string = trim($string);
        if (empty($string)) {
            return array();
        }
        $array = explode($separator, $string);
        $array = array_map('trim', $array);
        $array = array_unique($array);
        return $array;
    }

    /**
     * @param array $array
     * @param string $glue
     * @return string
     */
    public static function getStringFromArray(array $array, $glue = ', ')
    {
        if (empty($array)) {
            return '';
        }
        return implode($glue, $array);
    }

    /**
     * @param bool $active
     * @return array
     */
    public static function getAvailableGateways($active = false){
        $command = db()->createCommand(
            'SELECT `category`, `key`, `value`, `serialized` FROM '.$prefix = db()->tablePrefix.'option WHERE `category` LIKE "%app.gateway%"');

        $rows = $command->queryAll();
        $_gateways = [];
        foreach ($rows as $row) {
            if($row['key'] == 'name' || $row['key'] == 'status'){
                $_gateways[$row['category']][] = $row['value'];
            }
        }
        $activeGateways = [];
        foreach ($_gateways as $gateway){
            if($active) {
                if ($gateway[1] == 'active') {
                    $activeGateways[] = strtolower($gateway[0]);
                }
            }else{
                $activeGateways[] = strtolower($gateway[0]);
            }
        }
        return $activeGateways;
    }

    /**
     * @return array
     */
    public static function getActiveAvailableGatewaysHandlePayments(){
        $command = db()->createCommand(
            'SELECT `category`, `key`, `value`, `serialized` FROM '.$prefix = db()->tablePrefix.'option WHERE `category` LIKE "%app.gateway%"');

        $rows = $command->queryAll();
        $_gateways = [];
        foreach ($rows as $row) {
            if($row['key'] == 'name' || $row['key'] == 'status'){
                $_gateways[$row['category']][] = $row['value'];
            }
        }
        $activeGateways = [];
        foreach ($_gateways as $gateway){
            if ($gateway[1] == 'active') {
                $activeGateways[strtolower($gateway[0])]['paymentCallback'] = ['\app\extensions\\' . strtolower($gateway[0]) . '\\' . ucfirst(strtolower($gateway[0])),'handlePayment'];
            }
        }
        return $activeGateways;
    }

    /**
     * @param $locationTax
     * @param $price
     * @return array
     */
    public static function getCartTotal($locationTax, $price){
        $total = 0;
        $total += $price;
        if($locationTax) foreach ($locationTax as $tax){
            $total += $tax->price;
        }

        return [
            'subtotal'      => $price,
            'total'         => $total,
            'locationTax'   => $locationTax,
            ];
    }

    /**
     * CommonHelper::functionExists()
     *
     * @param string $name
     * @return bool
     */
    public static function functionExists($name)
    {
        static $_exists     = array();
        static $_disabled   = null;
        static $_shDisabled = null;

        if (isset($_exists[$name]) || array_key_exists($name, $_exists)) {
            return $_exists[$name];
        }

        if (!function_exists($name)) {
            return $_exists[$name] = false;
        }

        if ($_disabled === null) {
            $_disabled = ini_get('disable_functions');
            $_disabled = explode(',', $_disabled);
            $_disabled = array_map('trim', $_disabled);
        }

        if (is_array($_disabled) && in_array($name, $_disabled)) {
            return $_exists[$name] = false;
        }

        if ($_shDisabled === null) {
            $_shDisabled = ini_get('suhosin.executor.func.blacklist');
            $_shDisabled = explode(',', $_shDisabled);
            $_shDisabled = array_map('trim', $_shDisabled);
        }

        if (is_array($_shDisabled) && in_array($name, $_shDisabled)) {
            return $_exists[$name] = false;
        }

        return $_exists[$name] = true;
    }

    /**
     * @return string
     */
    public static function findPhpCliPath()
    {
        static $cliPath;

        if ($cliPath !== null) {
            return $cliPath;
        }

        $cliPath = '/usr/bin/php';

        if (!self::functionExists('exec')) {
            return $cliPath;
        }

        $variants = array('php-cli', 'php5-cli', 'php5', 'php');
        foreach ($variants as $variant) {
            $out = @exec(sprintf('command -v %s 2>&1', $variant), $lines, $status);
            if ($status != 0 || empty($out)) {
                continue;
            }
            $cliPath = $out;
            break;
        }

        return $cliPath;
    }

    /**
     * @param License|null $model
     * @return mixed
     */
    public static function verifyLicense(License $model = null)
    {
        if ($model === null) {
            $model = new License();
        }

        $domain = (IS_CLI) ? parse_url(app()->urlManager->scriptUrl, PHP_URL_HOST) : $_SERVER['HTTP_HOST'];

        $response = CommonHelper::simpleCurlPost('https://api.codinbit.com/v1/license',
            [
                'first_name'        => $model->firstName,
                'last_name'         => $model->lastName,
                'email'             => $model->email,
                'purchase_code'     => $model->purchaseCode,
                'product_domain'    => $domain,
                'product_id'        => '1',
                'order_count'       => Order::find()->where(['>','total', 0])->andWhere(['status' => Order::STATUS_PAID])->count()
            ]);

        return $response;
    }

    /**
     * @return array
     */
    public static function getCronJobsList()
    {
        static $cronJobs;
        if ($cronJobs !== null) {
            return $cronJobs;
        }

        $cronJobs = array(
            array(
                'frequency'     => '* * * * *',
                'phpBinary'     => self::findPhpCliPath(),
                'consolePath'   => APP_APPS_PATH . '/console.php',
                'command'       => 'minute',
                'description'   => 'This command is for minutely cron',
            ),
            array(
                'frequency'     => '0 * * * *',
                'phpBinary'     => self::findPhpCliPath(),
                'consolePath'   => APP_APPS_PATH . '/console.php',
                'command'       => 'hour',
                'description'   => 'This command is for hourly cron',
            ),
            array(
                'frequency'     => '0 0 * * *',
                'phpBinary'     => self::findPhpCliPath(),
                'consolePath'   => APP_APPS_PATH . '/console.php',
                'command'       => 'day',
                'description'   => 'This command is for daily cron',
            ),
        );

        foreach ($cronJobs as $index => $data) {
            if (!isset($data['frequency'], $data['phpBinary'], $data['consolePath'], $data['command'], $data['description'])) {
                unset($cronJobs[$index]);
                continue;
            }
            $cronJobs[$index]['cronjob'] = sprintf('%s %s -q %s %s >/dev/null 2>&1', $data['frequency'], $data['phpBinary'], $data['consolePath'], $data['command']);
        }

        return $cronJobs;
    }

    /**
     * AppInitHelper::simpleCurlPost()
     *
     * @param string $requestUrl
     * @param array $postData
     * @param int $timeout
     * @return array
     */
    public static function simpleCurlPost($requestUrl, array $postData = array(), $timeout = 30)
    {
        return self::makeRemoteRequest($requestUrl, array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_POST           => count($postData),
            CURLOPT_POSTFIELDS     => http_build_query($postData, '', '&'),
        ));
    }

    /**
     * AppInitHelper::simpleCurlGet()
     *
     * @since 1.2
     * @param string $requestUrl
     * @param int $timeout
     * @return array
     */
    public static function simpleCurlGet($requestUrl, $timeout = 30)
    {
        return self::makeRemoteRequest($requestUrl, array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT        => $timeout,
        ));
    }

    /**
     * AppInitHelper::simpleCurlPut()
     *
     * @param string $requestUrl
     * @param array $postData
     * @param int $timeout
     * @return array
     */
    public static function simpleCurlPut($requestUrl, array $postData = array(), $timeout = 30)
    {
        return self::makeRemoteRequest($requestUrl, array(
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLOPT_POSTFIELDS     => http_build_query($postData, '', '&'),
        ));
    }

    /**
     * AppInitHelper::makeRemoteRequest()
     *
     * @param string $requestUrl
     * @param array $curlOptions
     * @return array
     * @since 1.3.5.9
     */
    public static function makeRemoteRequest($requestUrl, array $curlOptions = array())
    {
        if (!self::functionExists('curl_exec')) {
            return array('status' => 'error', 'message' => 'cURL not available, please install cURL and try again!');
        }

        $ch = curl_init($requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_AUTOREFERER , true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if (ini_get('open_basedir') == '' && ini_get('safe_mode') != 'On') {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }

        foreach ($curlOptions as $key => $value) {
            curl_setopt($ch, $key, $value);
        }

        $body        = curl_exec($ch);
        $curlCode    = curl_errno($ch);
        $curlMessage = curl_error($ch);

        curl_close($ch);

        if ($curlCode !== 0) {
            return array('status' => 'error', 'message' => $curlMessage);
        }

        return array('status' => 'success', 'message' => $body);
    }

    /**
     * @param $trackingCode
     */
    public static function getGoogleTrackingCode($trackingCode)
    {
        if (empty($trackingCode)) {
            return;
        }
        echo sprintf("<script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
          ga('create', '%s', 'auto');
          ga('send', 'pageview');
        </script>", $trackingCode);
    }



    /**
     * @param $array
     * @param $column_name
     * @return array
     */
    public static function ArrayColumn($array, $column_name)
    {
        return array_map(function($element) use($column_name){return $element[$column_name];}, $array);
    }

    /**
     * @return bool
     */
    public static function isModRewriteEnabled()
    {
        return self::functionExists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules()) : true;
    }
}
