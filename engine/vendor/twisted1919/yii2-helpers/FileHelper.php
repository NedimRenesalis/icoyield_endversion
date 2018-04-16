<?php

namespace twisted1919\helpers;

use yii\helpers\FileHelper as BaseFileHelper;

class FileHelper extends BaseFileHelper
{
    /**
     * @param $path
     * @param bool $delDir
     * @param int $level
     * @return bool
     */
    public static function deleteDirectoryContents($path, $delDir = false, $level = 0)
    {
        $path = rtrim($path, DIRECTORY_SEPARATOR);

        if (!($currentDir = @opendir($path))) {
            return false;
        }

        while (false !== ($fileName = @readdir($currentDir))) {
            if ($fileName != "." and $fileName != "..") {
                if (is_dir($path . DIRECTORY_SEPARATOR . $fileName)) {
                    if (substr($fileName, 0, 1) != '.') {
                        self::deleteDirectoryContents($path . DIRECTORY_SEPARATOR . $fileName, $delDir, $level + 1);
                    }
                } else {
                    @unlink($path . DIRECTORY_SEPARATOR . $fileName);
                }
            }
        }
        @closedir($currentDir);

        if ($delDir == true AND $level > 0) {
            return @rmdir($path);
        }

        return true;
    }
}
