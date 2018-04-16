<?php

namespace twisted1919\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * @param $string
     * @param $length
     * @param string $replacement
     * @return mixed
     */
    public static function truncateMiddle($string, $length, $replacement = '...')
    {
        if (($stringLength = strlen($string)) > $length) {
            return substr_replace($string, $replacement, $length / 2, $stringLength - $length);
        }
        return $string;
    }

    /**
     * @param $string
     * @param string $separator
     * @return array|trim
     */
    public static function getArrayFromString($string, $separator = ',')
    {
        $string = trim($string);
        if (empty($string)) {
            return [];
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
}
