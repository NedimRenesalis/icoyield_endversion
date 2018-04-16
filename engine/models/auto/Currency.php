<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $currency_id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Listing[] $listings
 */
class Currency extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'code', 'symbol'], 'string', 'max' => 100],
            [['status'], 'string', 'max' => 15],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_id' => 'Currency ID',
            'name' => 'Name',
            'code' => 'Code',
            'symbol' => 'Symbol',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListings()
    {
        return $this->hasMany(Listing::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * @inheritdoc
     * @return CurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CurrencyQuery(get_called_class());
    }
}
