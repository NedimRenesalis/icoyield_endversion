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
 * Class CategoryField
 * @package app\models
 */
class CategoryField extends \app\models\auto\CategoryField
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['type_id', 'help_text', 'category_id', 'created_at', 'updated_at'], 'required'],
            ['unit', 'exist', 'skipOnEmpty' => false],
            [['type_id', 'category_id', 'sort_order'], 'integer'],
            [['created_at', 'updated_at', 'field_id'], 'safe'],
            [['label', 'default_value', 'help_text'], 'string', 'max' => 255],
            [['unit'], 'string', 'max' => 25],
            [['required'], 'string', 'max' => 3],
            [['status'], 'string', 'max' => 15],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'category_id']],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryFieldType::className(), 'targetAttribute' => ['type_id' => 'type_id']],
        ];
    }
}