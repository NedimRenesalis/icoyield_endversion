<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%category_field_value}}".
 *
 * @property integer $value_id
 * @property integer $field_id
 * @property integer $listing_id
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CategoryField $field
 * @property Listing $listing
 */
class CategoryFieldValue extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_field_value}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'listing_id', 'value', 'created_at', 'updated_at'], 'required'],
            [['field_id', 'listing_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => CategoryField::className(), 'targetAttribute' => ['field_id' => 'field_id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'value_id' => 'Value ID',
            'field_id' => 'Field ID',
            'listing_id' => 'Listing ID',
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
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @inheritdoc
     * @return CategoryFieldValueQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryFieldValueQuery(get_called_class());
    }
}
