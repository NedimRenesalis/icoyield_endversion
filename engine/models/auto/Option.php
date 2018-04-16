<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%option}}".
 *
 * @property string $category
 * @property string $key
 * @property resource $value
 * @property integer $serialized
 * @property string $created_at
 * @property string $updated_at
 */
class Option extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'key'], 'required'],
            [['value'], 'string'],
            [['serialized'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['category', 'key'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'category' => 'Category',
            'key' => 'Key',
            'value' => 'Value',
            'serialized' => 'Serialized',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @inheritdoc
     * @return OptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OptionQuery(get_called_class());
    }
}
