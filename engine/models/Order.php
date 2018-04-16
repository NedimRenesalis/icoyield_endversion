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

use yii\db\Expression;

class Order extends \app\models\auto\Order
{

    const STATUS_UNPAID = 'unpaid';

    const STATUS_PAID = 'paid';

    const STATUS_FAILED = 'failed';

    const STATUS_PENDING = 'pending';

    /**
     * @var
     */
    public $month;
    /**
     * @var
     */
    public $sales;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'city', 'zip', 'phone', 'company_name', 'company_no', 'vat', 'order_title'], 'trim'],

            [['first_name', 'last_name', 'zone_id', 'city', 'phone'], 'required'],

            ['zip', 'string', 'max' => 10],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => 'country_id'],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => Zone::className(), 'targetAttribute' => 'zone_id', 'filter' => function($query) {
                $query->andWhere(['country_id' => (int)$this->country_id]);
            }],
            ['status', 'in', 'range' => array_keys(self::getOrderStatusesList())],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id'          => t('app', 'Order ID'),
            'first_name'        => t('app', 'First Name'),
            'last_name'         => t('app', 'Last Name'),
            'order_title'       => t('app', 'Order Title'),
            'company_name'      => t('app', 'Company Name'),
            'company_no'        => t('app', 'Company No'),
            'vat'               => t('app', 'VAT'),
            'phone'             => t('app', 'Phone'),
            'status'            => t('app', 'Status'),
            'created_at'        => t('app', 'Created At'),
            'updated_at'        => t('app', 'Updated At'),
        ];
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim(sprintf('%s %s', $this->first_name, $this->last_name));
    }
    /**
     * @inheritdoc
     */
    public static function getOrderStatusesList()
    {
        return [
            self::STATUS_PAID   => t('app', 'Paid'),
            self::STATUS_UNPAID => t('app', 'Unpaid'),
            self::STATUS_PENDING => t('app', 'Pending'),
            self::STATUS_FAILED => t('app', 'Failed'),
        ];
    }

    /**
     * @param null $ad
     * @param null $package
     * @return Order|void
     */
    public static function createNew($ad = null, $package = null, $cart = null)
    {
        if($ad == null || $package == null || $cart == null){
            return;
        }

        $locationDetails = Location::findOne(['location_id'=> $ad->location_id]);
        $customerDetails = Customer::findOne(['customer_id'=> $ad->customer_id]);
        $storeDetails = CustomerStore::findOne(['customer_id'=> $ad->customer_id]);

        //make new order
        $discount               = 0;
        $order                  = new self();
        $order->listing_id      = $ad->listing_id;
        $order->customer_id     = $ad->customer_id;
        $order->order_title     = $package->title;
        $order->first_name      = $customerDetails->first_name;
        $order->last_name       = $customerDetails->last_name;
        if($storeDetails) {
            $order->company_name = $storeDetails->company_name;
            $order->company_no = $storeDetails->company_no;
            $order->vat = $storeDetails->vat;
        }
        $order->country_id      = $locationDetails->country_id;
        $order->zone_id         = $locationDetails->zone_id;
        $order->city            = $locationDetails->city;
        $order->zip             = $locationDetails->zip;
        $order->phone           = $customerDetails->phone;
        $order->subtotal        = $cart['subtotal'];
        $order->total           = ($cart['total'] - (int)$discount);

        $order->save();
        return $order;
    }

    /**
     * @return auto\Order[]|array
     */
    public static function getLastMonthsSales()
    {
        return $query = static::find()
        ->select([
            'month' => new Expression("DATE_FORMAT(created_at, '%c')"),
            'sales' => new Expression('SUM(total)')
        ])
        ->where(['>', 'created_at', new Expression('DATE_SUB(now(), INTERVAL 6 MONTH)')])
        ->andWhere(['status' => self::STATUS_PAID])
        ->groupBy('month')
        ->all();
    }

    /**
     * @return array
     */
    public static function getLastMonthsSalesAsArray()
    {
        $sales = static::getLastMonthsSales();
        $salesArray = [];
        $salesArray['months'] = [];
        $salesArray['sales'] = [];
        if($sales) {
            foreach ($sales as $sale) {
                $dateObject = \DateTime::createFromFormat('!m', $sale->month);
                $salesArray['months'][] = $dateObject->format('F');
                $salesArray['sales'][] = (int)$sale->sales;
            }
        }
        return $salesArray;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTax()
    {
        return $this->hasMany(OrderTax::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAd()
    {
        return $this->hasOne(Listing::className(), ['listing_id' => 'listing_id']);
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
