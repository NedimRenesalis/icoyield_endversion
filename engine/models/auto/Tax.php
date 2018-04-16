<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%tax}}".
 *
 * @property integer $tax_id
 * @property integer $country_id
 * @property integer $zone_id
 * @property string $name
 * @property string $percent
 * @property string $is_global
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Country $country
 * @property Zone $zone
 */
class Tax extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tax}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'zone_id'], 'integer'],
            [['name', 'percent', 'created_at', 'updated_at'], 'required'],
            [['percent'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['is_global'], 'string', 'max' => 3],
            [['status'], 'string', 'max' => 15],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zone::className(), 'targetAttribute' => ['zone_id' => 'zone_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_id' => 'Tax ID',
            'country_id' => 'Country ID',
            'zone_id' => 'Zone ID',
            'name' => 'Name',
            'percent' => 'Percent',
            'is_global' => 'Is Global',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zone::className(), ['zone_id' => 'zone_id']);
    }

    /**
     * @inheritdoc
     * @return TaxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TaxQuery(get_called_class());
    }
}
