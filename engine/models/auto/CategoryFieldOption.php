<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%category_field_option}}".
 *
 * @property integer $option_id
 * @property integer $field_id
 * @property string $name
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CategoryField $field
 */
class CategoryFieldOption extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_field_option}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'name', 'created_at', 'updated_at'], 'required'],
            [['field_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['value'], 'string', 'max' => 255],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryField::className(), 'targetAttribute' => ['field_id' => 'field_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'option_id' => 'Option ID',
            'field_id' => 'Field ID',
            'name' => 'Name',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(CategoryField::className(), ['field_id' => 'field_id']);
    }

    /**
     * @inheritdoc
     * @return CategoryFieldOptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryFieldOptionQuery(get_called_class());
    }
}
