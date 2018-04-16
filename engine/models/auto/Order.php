<?php

namespace app\models\auto;

use Yii;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $order_id
 * @property integer $listing_id
 * @property integer $customer_id
 * @property integer $promo_code_id
 * @property string $order_title
 * @property string $first_name
 * @property string $last_name
 * @property string $company_name
 * @property string $company_no
 * @property string $vat
 * @property integer $country_id
 * @property integer $zone_id
 * @property string $city
 * @property string $zip
 * @property string $phone
 * @property integer $discount
 * @property integer $subtotal
 * @property integer $total
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Invoice[] $invoices
 * @property Country $country
 * @property Customer $customer
 * @property Listing $listing
 * @property Zone $zone
 * @property OrderTax[] $orderTaxes
 * @property OrderTransaction[] $orderTransactions
 */
class Order extends \app\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['listing_id', 'customer_id', 'promo_code_id', 'country_id', 'zone_id', 'discount', 'subtotal', 'total'], 'integer'],
            [['customer_id', 'first_name', 'last_name', 'city', 'zip', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['order_title', 'first_name', 'last_name', 'city'], 'string', 'max' => 100],
            [['company_name'], 'string', 'max' => 30],
            [['company_no', 'vat', 'phone'], 'string', 'max' => 20],
            [['zip', 'status'], 'string', 'max' => 10],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['listing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Listing::className(), 'targetAttribute' => ['listing_id' => 'listing_id']],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zone::className(), 'targetAttribute' => ['zone_id' => 'zone_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'listing_id' => 'Listing ID',
            'customer_id' => 'Customer ID',
            'promo_code_id' => 'Promo Code ID',
            'order_title' => 'Order Title',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'company_name' => 'Company Name',
            'company_no' => 'Company No',
            'vat' => 'Vat',
            'country_id' => 'Country ID',
            'zone_id' => 'Zone ID',
            'city' => 'City',
            'zip' => 'Zip',
            'phone' => 'Phone',
            'discount' => 'Discount',
            'subtotal' => 'Subtotal',
            'total' => 'Total',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['order_id' => 'order_id']);
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
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getListing()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(Zone::className(), ['zone_id' => 'zone_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTaxes()
    {
        return $this->hasMany(OrderTax::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderTransactions()
    {
        return $this->hasMany(OrderTransaction::className(), ['order_id' => 'order_id']);
    }

    /**
     * @inheritdoc
     * @return OrderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrderQuery(get_called_class());
    }
}
