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

namespace app\models;

/**
 * Class CategoryFieldType
 * @package app\models
 */
class CategoryFieldType extends \app\models\auto\CategoryFieldType
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_name'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['class_name', 'description'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 15],
        ];
    }

}