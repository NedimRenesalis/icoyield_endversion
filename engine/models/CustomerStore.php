<?php

/**
 *
 * @package    EasyAds
 * @author     CodinBit <contact@codinbit.com>
 * @link       https://www.easyads.io
 * @copyright  2017 EasyAds (https://www.easyads.io)
 * @license    https://www.easyads.io
 * @since      1.0.1
 */

namespace app\models;

use yii\behaviors\SluggableBehavior;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;


/**
 * Class CustomerStore
 * @package app\models
 */
class CustomerStore extends \app\models\auto\CustomerStore
{
    // when inactive model
    const STATUS_INACTIVE = 'inactive';

    // when active model
    const STATUS_ACTIVE = 'active';

    // when deactivated
    const STATUS_DEACTIVATED = 'deactivated';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_name', 'company_name', 'company_no', 'vat'], 'trim'],
            [['store_name', 'company_name'], 'required'],

            [['store_name', 'company_name'], 'string', 'max' => 30],
            [['company_no', 'vat'], 'string', 'max' => 20],

            [['status'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class'     => SluggableBehavior::className(),
            'value' => [$this, 'getSlug'] //https://github.com/yiisoft/yii2/issues/7773
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'store_id'      => t('app', 'Store ID'),
            'customer_id'   => t('app', 'Customer'),
            'store_name'    => t('app', 'Store Name'),
            'company_name'  => t('app', 'Company Name'),
            'company_no'    => t('app', 'Company No'),
            'vat'           => t('app', 'VAT'),
            'status'        => t('app', 'Status'),
            'created_at'    => t('app', 'Created At'),
            'updated_at'    => t('app', 'Updated At'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * @return bool
     */
    public function deactivate()
    {
        $this->status = self::STATUS_INACTIVE;
        $this->save(false);
        return true;
    }

    /**
     * @return bool
     */
    public function activate()
    {
        if($this->status == self::STATUS_DEACTIVATED || $this->status == self::STATUS_INACTIVE) {
            $this->status = self::STATUS_ACTIVE;
            $this->save(false);
        }
        return true;
    }

    /**
     * @param $slug
     * @return array|null|\yii\db\ActiveRecord
     */
    public function findBySlug($slug)
    {
        return $this->find()->where(array(
            'slug' => $slug,
        ))->one();
    }

    /**
     * @param $event
     * @return string
     * //https://github.com/yiisoft/yii2/issues/7773
     */
    public function getSlug($event)
    {
        if(!empty($event->sender->slug)) {
            return $event->sender->slug;
        }
        return Inflector::slug($event->sender->store_name);
    }
}