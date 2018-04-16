<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%zone}}".
 *
 * @property integer $zone_id
 * @property integer $country_id
 * @property string $name
 * @property string $code
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Location[] $locations
 * @property Order[] $orders
 * @property Tax[] $taxes
 * @property Country $country
 */
class Zone extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%zone}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'name', 'code', 'created_at', 'updated_at'], 'required'],
            [['country_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 150],
            [['code'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 15],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'zone_id' => 'Zone ID',
            'country_id' => 'Country ID',
            'name' => 'Name',
            'code' => 'Code',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['zone_id' => 'zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['zone_id' => 'zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxes()
    {
        return $this->hasMany(Tax::className(), ['zone_id' => 'zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }

    /**
     * @inheritdoc
     * @return ZoneQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ZoneQuery(get_called_class());
    }
}
