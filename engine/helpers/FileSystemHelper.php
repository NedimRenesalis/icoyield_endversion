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

class FileSystemHelper
{

    /**
     * FileSystemHelper::readDirectoryContents()
     *
     * @param string $sourceDir
     * @param bool $includePath
     * @param bool $recursive
     * @return mixed
     */
    public static function readDirectoryContents($sourceDir, $includePath = false, $recursive = false)
    {
        static $fileData = array();

        if ($fp = @opendir($sourceDir)) {
            if ($recursive === false) {
                $fileData = array();
                $sourceDir = rtrim(realpath($sourceDir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            }

            while (FALSE !== ($file = readdir($fp))) {
                if (@is_dir($sourceDir.$file) && strncmp($file, '.', 1) !== 0) {
                    self::readDirectoryContents($sourceDir.$file.DIRECTORY_SEPARATOR, $includePath, true);
                }
                elseif (strncmp($file, '.', 1) !== 0) {
                    $fileData[] = $includePath ? $sourceDir.$file : $file;
                }
            }
            return $fileData;
        } else {
            return false;
        }
    }

    /**
     * FileSystemHelper::getDirectoryNames()
     *
     * @param string $path
     * @return array
     */
    public static function getDirectoryNames($path)
    {
        return array_map('basename', array_values(self::getDirectoriesRecursive($path)));
    }

    /**
     * FileSystemHelper::getDirectoriesRecursive()
     *
     * @param string $path
     * @param integer $maxDepth
     * @return array
     */
    public static function getDirectoriesRecursive($path, $maxDepth = 0)
    {
        $directories = array();
        if (!is_dir($path)) {
            return $directories;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path), \RecursiveIteratorIterator::CHILD_FIRST
        );
        $iterator->setMaxDepth($maxDepth);
        foreach ($iterator as $file) {
            if (!$file->isDir() || in_array($file->getFilename(), array('.', '..')) ) {
                continue;
            }

            $directories[] = $file->__toString();
        }

        return $directories;
    }

    /**
     * @param $src
     * @param $dst
     * @return bool
     */
    public static function copyFilesRecursive($src,$dst)
    {
        if (!file_exists($src) || !is_dir($src) || !is_readable($src)) {
            return false;
        }

        if ((!file_exists($dst) || !is_dir($dst)) && !@mkdir($dst, 0777, true)) {
            rmdir($dst);
            return false;
        }

        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
        return true;
    }

    /**
     * @param $sourceDir
     */
    public static function deleteDirectoryWithContent($sourceDir)
    {
        $it = new \RecursiveDirectoryIterator($sourceDir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($sourceDir);
    }

}