<?php

namespace twisted1919\helpers;

class Common
{
    /**
     * @param $sqlFile
     * @param null $dbPrefix
     * @return array
     */
    public static function getQueriesFromSqlFile($sqlFile, $dbPrefix = null)
    {
        if (!is_file($sqlFile) || !is_readable($sqlFile)) {
            return [];
        }

        if (!empty($dbPrefix)) {
            $searchReplace = [
                'CREATE TABLE IF NOT EXISTS `'  => 'CREATE TABLE IF NOT EXISTS `' . $dbPrefix,
                'DROP TABLE IF EXISTS `'        => 'DROP TABLE IF EXISTS `' . $dbPrefix,
                'INSERT INTO `'                 => 'INSERT INTO `' . $dbPrefix,
                'ALTER TABLE `'                 => 'ALTER TABLE `' . $dbPrefix,
                'ALTER IGNORE TABLE `'          => 'ALTER IGNORE TABLE `' . $dbPrefix,
                'REFERENCES `'                  => 'REFERENCES `' . $dbPrefix,
                'UPDATE `'                      => 'UPDATE `' . $dbPrefix,
                ' FROM `'                       => ' FROM `' . $dbPrefix,
            ];
            $search  = array_keys($searchReplace);
            $replace = array_values($searchReplace);
        }

        $queries = [];
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
     * @param $name
     * @return bool|mixed
     */
    public static function functionExists($name)
    {
        static $_exists     = [];
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
     * @param $bytes
     * @param int $precision
     * @return string
     */
    public static function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
