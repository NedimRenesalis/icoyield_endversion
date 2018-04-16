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


class UploadHelper
{
    public static function getErrorMessageFromErrorCode($code)
    {
        if($code === UPLOAD_ERR_INI_SIZE)
        {
            return t('app', 'One of the files you are trying to upload is too large');
        }
        if($code === UPLOAD_ERR_FORM_SIZE)
        {
            return t('app', 'One of the files you are trying to upload is too large');
        }
        if($code === UPLOAD_ERR_PARTIAL)
        {
            return t('app', 'The uploaded file was only partially uploaded');
        }
        if($code === UPLOAD_ERR_NO_FILE)
        {
            return t('app', 'No file was uploaded');
        }
        if($code === UPLOAD_ERR_NO_TMP_DIR)
        {
            return t('app', 'Missing a temporary folder');
        }
        if($code === UPLOAD_ERR_CANT_WRITE)
        {
            return t('app', 'Failed to save your file');
        }
        if($code === UPLOAD_ERR_EXTENSION)
        {
            return t('app', 'Upload was stopped due some internal problems');
        }
        return '';
    }
}